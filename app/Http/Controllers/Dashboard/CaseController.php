<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\Customer;
use App\Models\VehicleCase;
use App\Models\CaseTransfer;
use App\Models\CaseAlteration;
use App\Models\CaseFileReturn;
use App\Models\CaseTax;
use App\Models\CaseInsurance;
use App\Models\CasePermit;
use App\Models\CaseFitness;
use App\Models\CaseOther;
use App\Models\Payment;
use App\Models\User;
use App\Models\CaseActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view case');

        try {
            // Customer-grouped view: each customer row shows their billing summary + case count
            $customerGroups = Customer::withCount('vehicleCases')
                ->with('billing')
                ->whereHas('vehicleCases')
                ->orderBy('customer_code')
                ->get();

            // Legacy/uncategorized cases (no customer linked)
            $legacyCount = VehicleCase::whereNull('customer_id')->count();

            return view('dashboard.cases.index', compact('customerGroups', 'legacyCount'));
        } catch (\Throwable $th) {
            Log::error("Case Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show all cases belonging to a single customer (drill-down from grouped index).
     */
    public function customerCases(Customer $customer)
    {
        $this->authorize('view case');

        try {
            $cases   = $customer->vehicleCases()->latest()->get();
            $billing = $customer->billing;

            // Per-case totals from billing items — keyed by case ID, no schema change needed
            $caseAmounts = BillingItem::whereIn('vehicle_case_id', $cases->pluck('id'))
                ->groupBy('vehicle_case_id')
                ->selectRaw('vehicle_case_id, SUM(item_amount) as total')
                ->pluck('total', 'vehicle_case_id');

            return view('dashboard.cases.customer-cases', compact('customer', 'cases', 'billing', 'caseAmounts'));
        } catch (\Throwable $th) {
            Log::error("Customer Cases Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create case');
        try {
            return view('dashboard.cases.create');
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Case Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->authorize('create case');
        $validator = Validator::make($request->all(), [
            'vehicle_reg_no'   => 'required|string|max:50|unique:vehicle_cases',
            'make'             => 'nullable|string|max:100',
            'year'             => 'nullable|integer|min:1900|max:2100',
            'submitted_by'     => 'required|string|max:150',
            'mobile_no'        => 'required|string|max:20',
            'submission_date'  => 'required|date',
            'tentative_return_date' => 'nullable|date|after_or_equal:submission_date|after:today',
            'case_refer_to'    => 'required|in:Karachi,Lasbella,Quetta,Peshawar,Gilgit,Punjab,Other',
            'work_type'        => 'required|in:transfer,alteration,tax,insurance,permit,fitness',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();
            $caseNo = 'CASE-' . now()->format('Y') . '-' . Str::padLeft(VehicleCase::count() + 1, 4, '0');

            // Create Main Vehicle Case
            $vehicleCase = VehicleCase::create([
                'case_no'               => $caseNo,
                'vehicle_reg_no'        => $request->vehicle_reg_no,
                'make'                  => $request->make,
                'year'                  => $request->year,
                'submitted_by'          => $request->submitted_by,
                'mobile_no'             => $request->mobile_no,
                'submission_date'       => $request->submission_date,
                'tentative_return_date' => $request->tentative_return_date,
                'case_refer_to'         => $request->case_refer_to,
                'work_type'             => $request->work_type,
            ]);

            // Create related record based on work_type
            switch ($request->work_type) {
                case 'transfer':
                    CaseTransfer::create([
                        'vehicle_case_id' => $vehicleCase->id,
                        'from_name'       => $request->from_name,
                        'from_s_o'        => $request->from_s_o,
                        'from_nic'        => $request->from_nic,
                        'from_biometric'  => $request->boolean('from_biometric'),
                        'to_name'         => $request->to_name,
                        'to_s_o'          => $request->to_s_o,
                        'to_nic'          => $request->to_nic,
                        'to_biometric'    => $request->boolean('to_biometric'),
                        'engine_no'       => $request->engine_no,
                        'chassis_no'      => $request->chassis_no,
                        'wheels'          => $request->wheels,
                        'weight'          => $request->weight,
                        'last_tax'        => $request->last_tax,
                    ]);
                    break;

                case 'alteration':
                    CaseAlteration::create([
                        'vehicle_case_id' => $vehicleCase->id,
                        'engine_no'       => $request->engine_no,
                        'chassis_no'      => $request->chassis_no,
                        'wheels'          => $request->wheels,
                        'weight'          => $request->weight,
                        'last_tax'        => $request->last_tax,
                        'other'           => $request->other,
                        'alt_from'        => $request->alt_from,
                        'alt_to'          => $request->alt_to,
                        'alt_wheels'      => $request->alt_wheels,
                        'alt_engine'      => $request->alt_engine,
                        'alt_body'        => $request->alt_body,
                        'alt_docs'        => $request->alt_docs,
                    ]);
                    break;

                case 'tax':
                    CaseTax::create([
                        'vehicle_case_id' => $vehicleCase->id,
                        'tax_from'        => $request->tax_from,
                        'tax_to'          => $request->tax_to,
                    ]);
                    break;

                case 'insurance':
                    CaseInsurance::create([
                        'vehicle_case_id' => $vehicleCase->id,
                        'details'         => $request->details,
                    ]);
                    break;

                case 'permit':
                    CasePermit::create([
                        'vehicle_case_id' => $vehicleCase->id,
                        'region'          => $request->region,
                        'docs'            => $request->docs,
                        'expiry_date'     => $request->expiry_date,
                    ]);
                    break;

                case 'fitness':
                    CaseFitness::create([
                        'vehicle_case_id' => $vehicleCase->id,
                        'fitness_from'    => $request->fitness_from,
                        'docs'            => $request->docs,
                    ]);
                    break;
            }

            DB::commit();

            $adminUsers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'super-admin']);
            })->get();

            app('notificationService')->notifyUsers(
                $adminUsers,
                'A new Case #' . $vehicleCase->case_no . ' has been created by ' . auth()->user()->name . '. Click to check details.',
                'cases',
                $vehicleCase->id
            );

            return redirect()
                ->route('dashboard.cases.next-steps', $vehicleCase->id)
                ->with('success', "Case #{$caseNo} created successfully! Now proceed with remaining works.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Case Creation Failed', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Failed to create case: ' . $e->getMessage());
        }
    }

    // ====================== NEXT STEPS PAGE ======================
    public function nextSteps(VehicleCase $case)
    {
        $doneWorks = [];

        if ($case->transfer) $doneWorks[] = 'transfer';
        if ($case->alteration) $doneWorks[] = 'alteration';
        if ($case->tax) $doneWorks[] = 'tax';
        if ($case->insurance) $doneWorks[] = 'insurance';
        if ($case->permit) $doneWorks[] = 'permit';
        if ($case->fitness) $doneWorks[] = 'fitness';

        $allWorks = ['transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness'];
        $remainingWorks = array_diff($allWorks, $doneWorks);

        return view('dashboard.cases.next-steps', compact('case', 'remainingWorks', 'doneWorks'));
    }

    public function showAddWorkForm(VehicleCase $case, $workType)
    {
        $validTypes = ['transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness'];

        if (!in_array($workType, $validTypes)) {
            abort(404);
        }

        return view('dashboard.cases.add-work', compact('case', 'workType'));
    }

    // ====================== ADD SPECIFIC WORK ======================
    public function addWork(Request $request, VehicleCase $case, $workType)
    {
        $validTypes = ['transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness'];
        if (!in_array($workType, $validTypes)) {
            abort(404);
        }

        // You can add validation per work type here if needed

        DB::beginTransaction();
        try {
            switch ($workType) {
                case 'transfer':
                    CaseTransfer::create([
                        'vehicle_case_id' => $case->id,
                        'from_name'       => $request->from_name,
                        'from_s_o'        => $request->from_s_o,
                        'from_nic'        => $request->from_nic,
                        'from_biometric'  => $request->boolean('from_biometric'),
                        'to_name'         => $request->to_name,
                        'to_s_o'          => $request->to_s_o,
                        'to_nic'          => $request->to_nic,
                        'to_biometric'    => $request->boolean('to_biometric'),
                        'engine_no'       => $request->engine_no,
                        'chassis_no'      => $request->chassis_no,
                        'wheels'          => $request->wheels,
                        'weight'          => $request->weight,
                        'last_tax'        => $request->last_tax,
                    ]);
                    break;

                case 'alteration':
                    CaseAlteration::create([
                        'vehicle_case_id' => $case->id,
                        'engine_no'       => $request->engine_no,
                        'chassis_no'      => $request->chassis_no,
                        'wheels'          => $request->wheels,
                        'weight'          => $request->weight,
                        'last_tax'        => $request->last_tax,
                        'other'           => $request->other,
                        'alt_from'        => $request->alt_from,
                        'alt_to'          => $request->alt_to,
                        'alt_wheels'      => $request->alt_wheels,
                        'alt_engine'      => $request->alt_engine,
                        'alt_body'        => $request->alt_body,
                        'alt_docs'        => $request->alt_docs,
                    ]);
                    break;

                case 'tax':
                    CaseTax::create([
                        'vehicle_case_id' => $case->id,
                        'tax_from'        => $request->tax_from,
                        'tax_to'          => $request->tax_to,
                    ]);
                    break;

                case 'insurance':
                    CaseInsurance::create([
                        'vehicle_case_id' => $case->id,
                        'details'         => $request->details,
                    ]);
                    break;

                case 'permit':
                    CasePermit::create([
                        'vehicle_case_id' => $case->id,
                        'region'          => $request->region,
                        'docs'            => $request->docs,
                        'expiry_date'     => $request->expiry_date,
                    ]);
                    break;

                case 'fitness':
                    CaseFitness::create([
                        'vehicle_case_id' => $case->id,
                        'fitness_from'    => $request->fitness_from,
                        'docs'            => $request->docs,
                    ]);
                    break;
            }

            DB::commit();

            return redirect()
                ->route('dashboard.cases.next-steps', $case->id)
                ->with('success', ucfirst($workType) . ' details added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add Work Failed', ['workType' => $workType, 'caseId' => $case->id, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }

    // ====================== SKIP WORK ======================
    public function skipWork(VehicleCase $case, $workType)
    {
        return redirect()
            ->route('dashboard.cases.next-steps', $case->id)
            ->with('info', ucfirst($workType) . ' skipped.');
    }

    // ====================== FINISH ALL ======================
    public function finishAll(VehicleCase $case)
    {
        return redirect()
            ->route('dashboard.cases.index')
            ->with('success', "All steps completed for Case #{$case->case_no}. Case is now ready.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view case');
        try {
            $case = VehicleCase::with(
                'transfer', 'alteration', 'tax', 'insurance',
                'permit', 'fitness', 'fileReturn', 'other', 'customer'
            )->findOrFail($id);

            $caseActivities  = $case->activities()->latest()->get();
            $caseItems       = BillingItem::where('vehicle_case_id', $id)->get();
            $customerBilling = optional($case->customer)->billing;

            // Fallback: if items have no vehicle_case_id (legacy data), load via billing FK
            if ($caseItems->isEmpty() && $case->billing) {
                $caseItems = $case->billing->items;
            }

            return view('dashboard.cases.show', compact(
                'case', 'caseActivities', 'caseItems', 'customerBilling'
            ));
        } catch (\Throwable $th) {
            Log::error("Case Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update case');
        try {
            $case = VehicleCase::with('transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness', 'fileReturn', 'other')->findOrFail($id);
            return view('dashboard.cases.edit', compact('case'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Case Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update case');

        $validator = Validator::make($request->all(), [
            'city'          => 'nullable|string|max:255',
            'vehicle_no'    => 'nullable|string|max:255',
            'new_vehicle_no'=> 'nullable|string|max:255',
            'vehicle_make'  => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'engine_no'     => 'nullable|string|max:255',
            'chassis_no'    => 'nullable|string|max:255',
            'party_name'    => 'nullable|string|max:255',
            'party_mobile'  => 'nullable|string|max:50',
            'vendor_name'   => 'nullable|string|max:255',
            'vendor_mobile' => 'nullable|string|max:50',
            'case_date'     => 'nullable|date',
            'comment'       => 'nullable|string',
            'status'        => 'required|in:open,closed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $case = VehicleCase::findOrFail($id);

            $case->update([
                'city'          => $request->city,
                'vehicle_no'    => $request->vehicle_no,
                'new_vehicle_no'=> $request->new_vehicle_no,
                'vehicle_make'  => $request->vehicle_make,
                'vehicle_model' => $request->vehicle_model,
                'engine_no'     => $request->engine_no,
                'chassis_no'    => $request->chassis_no,
                'party_name'    => $request->input('party_name') ?? '',
                'party_mobile'  => $request->input('party_mobile') ?? '',
                'vendor_name'   => $request->input('vendor_name'),
                'vendor_mobile' => $request->input('vendor_mobile'),
                'case_date'     => $request->case_date,
                'comment'       => $request->comment,
                'status'        => $request->status,
            ]);

            DB::commit();

            if ($case->customer_id) {
                return redirect()
                    ->route('dashboard.cases.customer-cases', $case->customer_id)
                    ->with('success', 'Case updated successfully!');
            }

            return redirect()
                ->route('dashboard.cases.index')
                ->with('success', 'Case updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Case Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', 'Something went wrong! Please try again later.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete case');
        try {
            DB::beginTransaction();

            $case       = VehicleCase::with('customer')->findOrFail($id);
            $customerId = $case->customer_id;

            // Find the customer's billing via customer_id (not vehicle_case_id FK which would be stale)
            $billing = $customerId ? Billing::where('customer_id', $customerId)->first() : null;

            if ($billing) {
                // Sum what this case contributed
                $caseItemsTotal = BillingItem::where('vehicle_case_id', $case->id)->sum('item_amount');

                // Remove only this case's billing items
                BillingItem::where('vehicle_case_id', $case->id)->delete();

                $remainingItemsTotal = BillingItem::where('billing_id', $billing->id)->sum('item_amount');

                if ($remainingItemsTotal > 0) {
                    // Other cases still have items — recalculate totals
                    $newPaid      = min($billing->paid_amount, $remainingItemsTotal);
                    $newRemaining = $remainingItemsTotal - $newPaid;
                    $billing->update([
                        'total_amount'     => $remainingItemsTotal,
                        'paid_amount'      => $newPaid,
                        'remaining_amount' => $newRemaining,
                        'status'           => $this->determinePaymentStatus($newPaid, $remainingItemsTotal),
                    ]);
                } else {
                    // No items left at all — remove the billing and its payments
                    $billing->payments()->delete();
                    $billing->delete();
                }
            }

            $case->delete();

            DB::commit();

            // Return to customer-cases if customer exists, otherwise cases index
            if ($customerId) {
                return redirect()
                    ->route('dashboard.cases.customer-cases', $customerId)
                    ->with('success', 'Case deleted and bill recalculated.');
            }

            return redirect()->route('dashboard.cases.index')->with('success', 'Case deleted successfully.');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Case Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function getCaseItems($id)
    {
        $case = VehicleCase::with([
            'transfer',
            'tax',
            'insurance',
            'permit',
            'fitness',
            'alteration'
        ])->findOrFail($id);

        $map = [
            'transfer'   => 'Transfer Fee',
            'tax'        => 'Tax Fee',
            'insurance'  => 'Insurance Fee',
            'permit'     => 'Permit Fee',
            'fitness'    => 'Fitness Fee',
            'alteration' => 'Alteration Fee',
        ];

        $items = [];

        foreach ($map as $relation => $label) {
            if ($case->$relation) {
                $items[] = ['name' => $label];
            }
        }

        return response()->json($items);
    }


    public function storeCaseViaApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'common.city'        => 'required|string|max:255',
            'common.vehicleNo'      => 'nullable|string|max:255',
            'common.newVehicleNo'   => 'nullable|string|max:255',
            'common.customerId'     => 'nullable|exists:customers,id',
            'common.partyName'      => 'nullable|string|max:255',
            'common.partyMobile'    => 'nullable|string|max:255',
            'common.vendorName'     => 'nullable|string|max:255',
            'common.vendorMobile'   => 'nullable|string|max:255',
            'common.alterationType' => 'nullable|string|max:100',
            'common.vehicleMake' => 'nullable|string|max:255',
            'common.vehicleModel'=> 'nullable|string|max:255',
            'common.engineNo'    => 'nullable|string|max:255',
            'common.chassisNo'   => 'nullable|string|max:255',
            'common.date'        => 'nullable|date',
            'common.comment'     => 'nullable|string',
            'services'           => 'required|array',
            'totals'             => 'required|array',
            'totals.totalAmount'     => 'required|numeric',
            'totals.receivedAmount'  => 'required|numeric',
            'totals.remainingAmount' => 'required|numeric',
            'submittedAt'        => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();

            // Normalize date to YYYY-MM-DD regardless of what format arrives
            $rawDate = $request->input('common.date');
            $safeDate = null;
            if ($rawDate) {
                try {
                    $safeDate = \Carbon\Carbon::parse($rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $safeDate = null;
                }
            }

            // Resolve customer — prefer linked record, fall back to raw input
            $customerId = $request->input('common.customerId');
            $customer   = $customerId ? Customer::find($customerId) : null;
            $partyName   = $customer ? $customer->name          : ($request->input('common.partyName')   ?? '');
            $partyMobile = $customer ? ($customer->mobile ?? '') : ($request->input('common.partyMobile') ?? '');

            // 1. Create the vehicle case
            $vehicleCase = VehicleCase::create([
                'customer_id'   => $customer?->id,
                'city'          => $request->input('common.city'),
                'vehicle_no'    => $request->input('common.vehicleNo'),
                'new_vehicle_no' => $request->input('common.newVehicleNo'),
                'vehicle_make'  => $request->input('common.vehicleMake'),
                'vehicle_model' => $request->input('common.vehicleModel'),
                'engine_no'     => $request->input('common.engineNo'),
                'chassis_no'    => $request->input('common.chassisNo'),
                'party_name'    => $partyName,
                'party_mobile'  => $partyMobile,
                'vendor_name'   => $request->input('common.vendorName'),
                'vendor_mobile' => $request->input('common.vendorMobile'),
                'case_date'     => $safeDate,
                'comment'       => $request->input('common.comment'),
                'submitted_at'  => $request->has('submittedAt')
                    ? date('Y-m-d H:i:s', strtotime($request->input('submittedAt')))
                    : now(),
            ]);

            // 2. Find-or-create billing — 1 Customer = 1 Bill rule
            $receivedAmount  = (float) $request->input('totals.receivedAmount', 0);
            $newServiceTotal = (float) $request->input('totals.totalAmount', 0);

            $existingBilling = $customer ? Billing::where('customer_id', $customer->id)->first() : null;

            if ($existingBilling) {
                // Append to the customer's existing bill
                $existingBilling->total_amount    += $newServiceTotal;
                $existingBilling->paid_amount     += $receivedAmount;
                $existingBilling->remaining_amount = $existingBilling->total_amount - $existingBilling->paid_amount;
                $existingBilling->status           = $this->determinePaymentStatus(
                    $existingBilling->paid_amount, $existingBilling->total_amount
                );
                $existingBilling->save();
                $billing = $existingBilling;
            } else {
                // First bill for this customer, or no-customer legacy path
                $billing = Billing::create([
                    'vehicle_case_id'  => $vehicleCase->id, // first case only; untouched on append
                    'customer_id'      => $customer?->id,
                    'billing_type'     => 'local',
                    'bill_no'          => $this->generateBillNumber(),
                    'total_amount'     => $newServiceTotal,
                    'paid_amount'      => $receivedAmount,
                    'remaining_amount' => $newServiceTotal - $receivedAmount,
                    'billing_date'     => now(),
                    'billing_name'     => $partyName,
                    'description'      => $request->input('common.comment'),
                    'status'           => $this->determinePaymentStatus($receivedAmount, $newServiceTotal),
                ]);
            }

            // 3. Process services and create billing items
            $services = $request->input('services', []);
            foreach ($services as $service) {
                // vehicle_case_id on each item traces the line back to its case
                BillingItem::create([
                    'billing_id'      => $billing->id,
                    'vehicle_case_id' => $vehicleCase->id,
                    'item_name'       => $service['serviceType'],
                    'item_amount'     => $service['amount'],
                    'service_date'    => $safeDate,
                ]);

                // Create service-specific records
                $this->createServiceRecord($vehicleCase->id, $service, $request->input('common.alterationType'));
            }

            // 4. Create payment record if received amount > 0
            if ($receivedAmount > 0) {
                Payment::create([
                    'transaction_id' => $this->generateTransactionId(),
                    'billing_id'     => $billing->id,
                    'amount'         => $receivedAmount,
                    'payment_date'   => now(),
                    'payment_method' => 'cash',
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => "Case created successfully!",
                'case_id' => $vehicleCase->id,
                'billing_id' => $billing->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Case Creation Failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Failed to create case: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Add a new service to an existing case and update billing totals.
     */
    public function addService(Request $request, VehicleCase $case)
    {
        $this->authorize('update case');

        $validator = Validator::make($request->all(), [
            'service_type'    => 'required|string|in:Transfer,Alteration,Route Permit,FC,Insurance,Tax,File Return,Others',
            'amount'          => 'required|numeric|min:0',
            'service_date'    => 'nullable|date',
            'from_name'       => 'nullable|string|max:255',
            'from_s_o'        => 'nullable|string|max:255',
            'from_nic'        => 'nullable|string|max:255',
            'to_name'         => 'nullable|string|max:255',
            'to_s_o'          => 'nullable|string|max:255',
            'to_nic'          => 'nullable|string|max:255',
            'alteration_type' => 'nullable|string|max:100',
            'new_vehicle_no'  => 'nullable|string|max:255',
            'vehicle_make'    => 'nullable|string|max:255',
            'vehicle_model'   => 'nullable|string|max:255',
            'engine_no'       => 'nullable|string|max:255',
            'chassis_no'      => 'nullable|string|max:255',
            'rta_pta'         => 'nullable|string|max:50',
            'province'        => 'nullable|array',
            'province.*'      => 'nullable|string|max:50',
            'route_details'   => 'nullable|string',
            'truck_type'      => 'nullable|string|max:50',
            'fc_details'      => 'nullable|string',
            'remarks'         => 'nullable|string',
            'tax_from'        => 'nullable|string|max:100',
            'tax_to'          => 'nullable|string|max:100',
            'other_details'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $case->load(['transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness', 'fileReturn', 'other', 'billing', 'customer']);

        // billing() uses vehicle_case_id — null for 2nd/3rd cases; fall back to the customer's bill
        $billing = $case->billing ?? optional($case->customer)->billing;
        if (!$billing) {
            return redirect()->back()->with('error', 'No billing record found for this case.');
        }

        $serviceType = $request->service_type;

        $serviceRelationMap = [
            'Transfer'     => 'transfer',
            'Alteration'   => 'alteration',
            'Route Permit' => 'permit',
            'FC'           => 'fitness',
            'Insurance'    => 'insurance',
            'Tax'          => 'tax',
            'File Return'  => 'fileReturn',
            'Others'       => 'other',
        ];

        $relation = $serviceRelationMap[$serviceType] ?? null;
        if ($relation && $case->{$relation}) {
            return redirect()->back()->with('error', "'{$serviceType}' is already added to this case.");
        }

        // Build details array using the same keys createServiceRecord() expects
        $details = [];
        switch ($serviceType) {
            case 'Transfer':
            case 'Alteration':
            case 'File Return':
                $details = [
                    'fromName' => $request->input('from_name', ''),
                    'fromSo'   => $request->input('from_s_o', ''),
                    'fromNic'  => $request->input('from_nic', ''),
                    'toName'   => $request->input('to_name', ''),
                    'toSo'     => $request->input('to_s_o', ''),
                    'toNic'    => $request->input('to_nic', ''),
                ];
                break;
            case 'Route Permit':
                $details = [
                    'rtaPta'   => $request->input('rta_pta', 'RTA'),
                    'province' => implode(',', $request->input('province', [])),
                    'details'  => $request->input('route_details'),
                ];
                break;
            case 'FC':
                $details = [
                    'truckType' => $request->input('truck_type', 'Truck'),
                    'fcDetails' => $request->input('fc_details'),
                ];
                break;
            case 'Insurance':
                $details = ['remarks' => $request->input('remarks')];
                break;
            case 'Tax':
                $details = [
                    'fromPeriod' => $request->input('tax_from', ''),
                    'upto'       => $request->input('tax_to', ''),
                ];
                break;
            case 'Others':
                $details = ['otherDetails' => $request->input('other_details')];
                break;
        }

        $amount = (float) $request->amount;

        $serviceDate = null;
        if ($request->filled('service_date')) {
            try {
                $serviceDate = \Carbon\Carbon::parse($request->service_date)->format('Y-m-d');
            } catch (\Exception $e) {
                $serviceDate = null;
            }
        }

        try {
            DB::beginTransaction();

            // 1. Create the service-specific record
            $this->createServiceRecord($case->id, [
                'serviceType' => $serviceType,
                'details'     => $details,
            ], $serviceType === 'Alteration' ? $request->input('alteration_type') : null);

            // 2. Create billing item — vehicle_case_id traces this line back to its specific case
            BillingItem::create([
                'billing_id'      => $billing->id,
                'vehicle_case_id' => $case->id,
                'item_name'       => $serviceType,
                'item_amount'     => $amount,
                'service_date'    => $serviceDate,
            ]);

            // 3. Recalculate billing totals — paid_amount and payments never touched
            $billing  = Billing::where('id', $billing->id)->lockForUpdate()->firstOrFail();
            $newTotal = $billing->total_amount + $amount;
            $newRemaining = $newTotal - $billing->paid_amount;

            $newStatus = 'unpaid';
            if ($billing->paid_amount >= $newTotal) {
                $newStatus = 'paid';
            } elseif ($billing->paid_amount > 0) {
                $newStatus = 'partial';
            }

            $billing->update([
                'total_amount'     => $newTotal,
                'remaining_amount' => $newRemaining,
                'status'           => $newStatus,
            ]);

            // Update any previously empty vehicle fields (Transfer / Alteration only)
            if (in_array($serviceType, ['Transfer', 'Alteration'])) {
                $vehicleUpdates = [];
                foreach (['new_vehicle_no', 'vehicle_make', 'vehicle_model', 'engine_no', 'chassis_no'] as $field) {
                    if ($request->filled($field) && empty($case->{$field})) {
                        $vehicleUpdates[$field] = $request->input($field);
                    }
                }
                if (!empty($vehicleUpdates)) {
                    $case->update($vehicleUpdates);
                }
            }

            DB::commit();

            return redirect()
                ->route('dashboard.cases.show', $case->id)
                ->with('success', "'{$serviceType}' service added. Bill updated to Rs. " . number_format($newTotal, 2) . ".");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add Service Failed', ['case_id' => $case->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to add service. Please try again.');
        }
    }

    public function printCase(VehicleCase $case)
    {
        $this->authorize('view case');
        $case->load('transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness', 'fileReturn', 'other', 'customer', 'billing');
        $caseItems = BillingItem::where('vehicle_case_id', $case->id)->get();
        if ($caseItems->isEmpty() && $case->billing) {
            $caseItems = $case->billing->items;
        }
        return view('dashboard.cases.print', compact('case', 'caseItems'));
    }

    public function caseInvoice(VehicleCase $case)
    {
        $this->authorize('view case');
        $case->load('customer', 'billing');
        $caseItems       = BillingItem::where('vehicle_case_id', $case->id)->get();
        if ($caseItems->isEmpty() && $case->billing) {
            $caseItems = $case->billing->items;
        }
        $customerBilling = optional($case->customer)->billing;
        return view('dashboard.cases.invoice', compact('case', 'caseItems', 'customerBilling'));
    }

    public function updateService(Request $request, VehicleCase $case, string $type)
    {
        $this->authorize('update case');

        try {
            DB::beginTransaction();

            switch ($type) {
                case 'transfer':
                    if (!$case->transfer) abort(404);
                    $case->transfer->update([
                        'from_name' => $request->input('from_name'),
                        'from_s_o'  => $request->input('from_s_o'),
                        'from_nic'  => $request->input('from_nic'),
                        'to_name'   => $request->input('to_name'),
                        'to_s_o'    => $request->input('to_s_o'),
                        'to_nic'    => $request->input('to_nic'),
                    ]);
                    break;

                case 'alteration':
                    if (!$case->alteration) abort(404);
                    $case->alteration->update([
                        'alteration_type' => $request->input('alteration_type'),
                    ]);
                    break;

                case 'tax':
                    if (!$case->tax) abort(404);
                    $case->tax->update([
                        'tax_from' => $request->input('tax_from'),
                        'tax_to'   => $request->input('tax_to'),
                    ]);
                    break;

                case 'insurance':
                    if (!$case->insurance) abort(404);
                    $case->insurance->update([
                        'details' => $request->input('details'),
                    ]);
                    break;

                case 'permit':
                    if (!$case->permit) abort(404);
                    $selectedProvinces = array_filter(array_map('trim', (array) $request->input('province', [])));
                    $provinceStr       = implode(',', $selectedProvinces);
                    // Remove province_status entries for de-selected provinces
                    $oldStatus     = $case->permit->province_status ?? [];
                    $cleanedStatus = array_intersect_key($oldStatus, array_flip($selectedProvinces));
                    $case->permit->update([
                        'type'            => $request->input('type'),
                        'province'        => $provinceStr,
                        'details'         => $request->input('details'),
                        'province_status' => $cleanedStatus,
                    ]);
                    break;

                case 'fitness':
                    if (!$case->fitness) abort(404);
                    $case->fitness->update([
                        'type'    => $request->input('type'),
                        'details' => $request->input('details'),
                    ]);
                    break;

                case 'file-return':
                    if (!$case->fileReturn) abort(404);
                    $case->fileReturn->update([
                        'from_name' => $request->input('from_name'),
                        'from_s_o'  => $request->input('from_s_o'),
                        'from_nic'  => $request->input('from_nic'),
                        'to_name'   => $request->input('to_name'),
                        'to_s_o'    => $request->input('to_s_o'),
                        'to_nic'    => $request->input('to_nic'),
                    ]);
                    break;

                case 'other':
                    if (!$case->other) abort(404);
                    $case->other->update([
                        'details' => $request->input('details'),
                    ]);
                    break;

                default:
                    abort(404);
            }

            DB::commit();

            if ($case->customer_id) {
                return redirect()
                    ->route('dashboard.cases.customer-cases', $case->customer_id)
                    ->with('success', ucfirst(str_replace('-', ' ', $type)) . ' updated successfully!');
            }

            return redirect()
                ->route('dashboard.cases.index')
                ->with('success', ucfirst(str_replace('-', ' ', $type)) . ' updated successfully!');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Update Service Failed', ['type' => $type, 'case_id' => $case->id, 'error' => $th->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update. Please try again.');
        }
    }

    public function updateProvinceStatus(Request $request, VehicleCase $case)
    {
        $this->authorize('update case');

        $permit = $case->permit;
        if (!$permit) {
            if ($request->expectsJson()) return response()->json(['error' => 'No route permit found.'], 404);
            return redirect()->back()->with('error', 'No route permit found for this case.');
        }

        $province = $request->input('province');
        $status   = $request->input('status');

        if (!in_array($status, ['incomplete', 'complete'])) {
            if ($request->expectsJson()) return response()->json(['error' => 'Invalid status value.'], 422);
            return redirect()->back()->with('error', 'Invalid status value.');
        }

        $current            = $permit->province_status ?? [];
        $current[$province] = $status;

        $permit->update(['province_status' => $current]);

        if ($request->expectsJson()) return response()->json(['success' => true]);
        return redirect()->back()->with('success', "Province '{$province}' marked as {$status}.");
    }

    /**
     * Create service-specific record based on service type
     */
    private function createServiceRecord($caseId, $service, $alterationType = null)
    {
        $serviceType = strtolower(str_replace(' ', '_', $service['serviceType']));
        $details = $service['details'];

        switch ($serviceType) {
            case 'alteration':
                CaseAlteration::create([
                    'vehicle_case_id' => $caseId,
                    'alteration_type' => $alterationType,
                    'from_name'       => $details['fromName'] ?? '',
                    'from_s_o'        => $details['fromSo']   ?? '',
                    'from_nic'        => $details['fromNic']  ?? '',
                    'to_name'         => $details['toName']   ?? '',
                    'to_s_o'          => $details['toSo']     ?? '',
                    'to_nic'          => $details['toNic']    ?? '',
                ]);
                break;

            case 'transfer':
            case 'file_return':
                $modelClass = $this->getServiceModelClass($serviceType);
                if ($modelClass) {
                    $modelClass::create([
                        'vehicle_case_id' => $caseId,
                        'from_name' => $details['fromName'] ?? '',
                        'from_s_o'  => $details['fromSo']   ?? '',
                        'from_nic'  => $details['fromNic']  ?? '',
                        'to_name'   => $details['toName']   ?? '',
                        'to_s_o'    => $details['toSo']     ?? '',
                        'to_nic'    => $details['toNic']    ?? '',
                    ]);
                }
                break;

            case 'route_permit':
                CasePermit::create([
                    'vehicle_case_id' => $caseId,
                    'type'     => $details['rtaPta']   ?? null,
                    'province' => $details['province'] ?? null,
                    'details'  => $details['details']  ?? null,
                ]);
                break;

            case 'fc':
                CaseFitness::create([
                    'vehicle_case_id' => $caseId,
                    'type' => $details['truckType'] ?? 'Others',
                    'details' => $details['fcDetails'] ?? null,
                ]);
                break;

            case 'insurance':
                CaseInsurance::create([
                    'vehicle_case_id' => $caseId,
                    'details' => $details['remarks'] ?? null,
                ]);
                break;

            case 'tax':
                CaseTax::create([
                    'vehicle_case_id' => $caseId,
                    'tax_from' => $details['fromPeriod'] ?? null,
                    'tax_to' => $details['upto'] ?? null,
                ]);
                break;

            case 'others':
                CaseOther::create([
                    'vehicle_case_id' => $caseId,
                    'details' => $details['otherDetails'] ?? null,
                ]);
                break;
        }
    }

    /**
     * Get the model class for transfer/alteration/file_return services
     */
    private function getServiceModelClass($serviceType)
    {
        $models = [
            'transfer' => CaseTransfer::class,
            'alteration' => CaseAlteration::class,
            'file_return' => CaseFileReturn::class,
        ];

        return $models[$serviceType] ?? null;
    }

    /**
     * Generate unique bill number
     */
    private function generateBillNumber()
    {
        $prefix = 'BILL';
        $year = date('Y');
        $month = date('m');

        $lastBill = Billing::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->orderBy('id', 'desc')
                        ->first();

        if ($lastBill && preg_match('/BILL-' . $year . $month . '-(\d+)/', $lastBill->bill_no, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . '-' . $year . $month . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique transaction ID for payment
     */
    private function generateTransactionId()
    {
        return 'TXN-' . uniqid() . '-' . time();
    }

    /**
     * Determine payment status based on paid amount vs total amount
     */
    private function determinePaymentStatus($paidAmount, $totalAmount)
    {
        if ($paidAmount <= 0) {
            return 'unpaid';
        } elseif ($paidAmount >= $totalAmount) {
            return 'paid';
        } else {
            return 'partial';
        }
    }

    public function storeActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'case_id' => 'required|exists:vehicle_cases,id',
            'activity_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $activity = CaseActivity::create([
                'case_id' => $request->input('case_id'),
                'activity_type' => $request->input('activity_type'),
                'description' => $request->input('description'),
            ]);

            return redirect()->route('dashboard.cases.show', $request->input('case_id'))
                ->with('success', 'Activity logged successfully!');
        } catch (\Exception $e) {
            Log::error('Store Activity Failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to log activity: ' . $e->getMessage());
        }
    }
}
