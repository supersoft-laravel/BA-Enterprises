<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
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
            $query = VehicleCase::query();

            if ($request->filled('refer_to')) {
                $query->where('city', $request->refer_to);
            }

            $cases = $query->latest()->get();

            return view('dashboard.cases.index', compact('cases'));
        } catch (\Throwable $th) {
            Log::error("Case Index Failed:" . $th->getMessage());
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
            $case = VehicleCase::with('transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness', 'fileReturn', 'other')->findOrFail($id);
            $caseActivities = $case->activities()->latest()->get();
            return view('dashboard.cases.show', compact('case', 'caseActivities'));
        } catch (\Throwable $th) {
            // throw $th;
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
            $case = VehicleCase::findOrFail($id);
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
        $this->authorize('update alteration');
        $validator = Validator::make($request->all(), [
            'vehicle_reg_no'   => 'required|string|max:50|unique:vehicle_cases,vehicle_reg_no,' . $id,
            'make'             => 'nullable|string|max:100',
            'year'             => 'nullable|integer|min:1900|max:2100',
            'submitted_by'     => 'required|string|max:150',
            'mobile_no'        => 'required|string|max:20',
            'submission_date'  => 'required|date',
            'tentative_return_date' => 'nullable|date|after_or_equal:submission_date',
            'case_refer_to'    => 'required|in:Karachi,Lasbella,Quetta,Peshawar,Gilgit,Punjab,Other',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $case = VehicleCase::findOrFail($id);

            $case->update([
                'vehicle_reg_no'        => $request->vehicle_reg_no,
                'make'                  => $request->make,
                'year'                  => $request->year,
                'submitted_by'          => $request->submitted_by,
                'mobile_no'             => $request->mobile_no,
                'submission_date'       => $request->submission_date,
                'tentative_return_date' => $request->tentative_return_date,
                'case_refer_to'         => $request->case_refer_to,
            ]);

            DB::commit();
            return redirect()
                ->route('dashboard.cases.show', $case->id)
                ->with('success', 'Basic details updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Case Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete case');
        try {
            $case = VehicleCase::findOrFail($id);
            $case->delete();
            return redirect()->route('dashboard.cases.index')->with('success', 'Case Deleted Successfully');
        } catch (\Throwable $th) {
            // throw $th;
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
            'common.city'   => 'required|string|max:255',
            'common.vehicleNo' => 'required|string|max:255',
            'common.partyName' => 'required|string|max:255',
            'common.partyMobile' => 'nullable|string|max:255',
            'common.vehicleMake' => 'nullable|string|max:255',
            'common.vehicleModel' => 'nullable|string|max:255',
            'common.engineNo' => 'nullable|string|max:255',
            'common.chassisNo' => 'nullable|string|max:255',
            'common.date' => 'required|date',
            'common.comment' => 'nullable|string',
            'services' => 'required|array',
            'totals' => 'required|array',
            'totals.totalAmount' => 'required|numeric',
            'totals.receivedAmount' => 'required|numeric',
            'totals.remainingAmount' => 'required|numeric',
            'submittedAt' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Create the vehicle case
            $vehicleCase = VehicleCase::create([
                'city' => $request->input('common.city'),
                'vehicle_no' => $request->input('common.vehicleNo'),
                'vehicle_make' => $request->input('common.vehicleMake'),
                'vehicle_model' => $request->input('common.vehicleModel'),
                'engine_no' => $request->input('common.engineNo'),
                'chassis_no' => $request->input('common.chassisNo'),
                'party_name' => $request->input('common.partyName'),
                'party_mobile' => $request->input('common.partyMobile'),
                'case_date' => $request->input('common.date'),
                'comment' => $request->input('common.comment'),
                'submitted_at' => $request->has('submittedAt')
                    ? date('Y-m-d H:i:s', strtotime($request->input('submittedAt')))
                    : now(),
            ]);

            // 2. Create billing record
            $billing = Billing::create([
                'vehicle_case_id' => $vehicleCase->id,
                'billing_type' => 'local',
                'bill_no' => $this->generateBillNumber(),
                'total_amount' => $request->input('totals.totalAmount'),
                'paid_amount' => $request->input('totals.receivedAmount'),
                'remaining_amount' => $request->input('totals.remainingAmount'),
                'billing_date' => now(),
                'billing_name' => $request->input('common.partyName'),
                'description' => $request->input('common.comment'),
                'status' => $this->determinePaymentStatus(
                    $request->input('totals.receivedAmount'),
                    $request->input('totals.totalAmount')
                ),
            ]);

            // 3. Process services and create billing items
            $services = $request->input('services', []);
            foreach ($services as $service) {
                // Create billing item
                BillingItem::create([
                    'billing_id' => $billing->id,
                    'item_name' => $service['serviceType'],
                    'item_amount' => $service['amount'],
                ]);

                // Create service-specific records
                $this->createServiceRecord($vehicleCase->id, $service);
            }

            // 4. Create payment record if received amount > 0
            if ($request->input('totals.receivedAmount') > 0) {
                Payment::create([
                    'transaction_id' => $this->generateTransactionId(), // Implement this helper method
                    'billing_id' => $billing->id,
                    'amount' => $request->input('totals.receivedAmount'),
                    'payment_date' => now(),
                    'payment_method' => 'cash', // You might want to get this from request
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
     * Create service-specific record based on service type
     */
    private function createServiceRecord($caseId, $service)
    {
        $serviceType = strtolower(str_replace(' ', '_', $service['serviceType']));
        $details = $service['details'];

        switch ($serviceType) {
            case 'transfer':
            case 'alteration':
            case 'file_return':
                $modelClass = $this->getServiceModelClass($serviceType);
                if ($modelClass) {
                    $modelClass::create([
                        'vehicle_case_id' => $caseId,
                        'from_name' => $details['fromName'] ?? null,
                        'from_s_o' => $details['fromSo'] ?? null,
                        'from_nic' => $details['fromNic'] ?? null,
                        'to_name' => $details['toName'] ?? null,
                        'to_s_o' => $details['toSo'] ?? null,
                        'to_nic' => $details['toNic'] ?? null,
                    ]);
                }
                break;

            case 'route_permit':
                CasePermit::create([
                    'vehicle_case_id' => $caseId,
                    'type' => $details['rtaPta'] ?? 'Others',
                    'details' => $details['details'] ?? null,
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
