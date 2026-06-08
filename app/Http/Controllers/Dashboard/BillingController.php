<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\VehicleCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view billing');

        try {
            $billings = Billing::with('items', 'vehicleCase')->latest()->get();
            return view('dashboard.billings.index', compact('billings'));
        } catch (\Throwable $th) {
            Log::error("Billing Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create billing');
        try {
            $cases = VehicleCase::whereDoesntHave('billing')->latest()->get();
            return view('dashboard.billings.create', compact('cases'));
        } catch (\Throwable $th) {
            Log::error("Billing Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create billing');
        $validator = Validator::make($request->all(), [
            'vehicle_case_id' => 'required|exists:vehicle_cases,id',
            'billing_type' => 'required|in:local,out_of_city',
            'billing_name' => 'required|string|max:255',
            'billing_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $billing = new Billing();
            $billing->vehicle_case_id = $request->vehicle_case_id;
            $billing->billing_type = $request->billing_type;
            $billing->billing_name = $request->billing_name;
            $billing->billing_date = $request->billing_date;
            $billing->total_amount = $request->total_amount;
            $billing->paid_amount = $request->paid_amount;
            $billing->remaining_amount = $request->remaining_amount;
            $billing->status = $request->remaining_amount == 0 ? 'paid' : ($request->paid_amount > 0 ? 'partial' : 'unpaid');
            $billing->description = $request->description;
            $billing->save();

            $typeCode = match ($billing->billing_type) {
                'local' => 'X',
                'out_of_city' => 'Y',
                default => 'Z',
            };

            do {
                $billNo = 'BA-' . $typeCode . '-' . str_pad($billing->id, 5, '0', STR_PAD_LEFT);
            } while (Billing::where('bill_no', $billNo)->exists());

            $billing->bill_no = $billNo;
            $billing->save();

            foreach ($request->items as $item) {
                $billingItem = new BillingItem();
                $billingItem->billing_id = $billing->id;
                $billingItem->item_name = $item['name'];
                $billingItem->item_amount = $item['amount'];
                $billingItem->save();
            }

            if ($request->paid_amount > 0) {
                $payment = new Payment();
                $payment->transaction_id = 'TXN-' . time() . rand(100, 999);
                $payment->billing_id = $billing->id;
                $payment->amount = $request->paid_amount;
                $payment->payment_date = $request->billing_date;
                $payment->payment_method = $request->payment_method ?? 'cash';
                $payment->save();
            }

            $adminUsers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'super-admin']);
            })->get();

            app('notificationService')->notifyUsers(
                $adminUsers,
                'New Bill #' . $billing->bill_no . ' created for Case #' . $billing->vehicleCase->case_no . ' by ' . auth()->user()->name,
                'billings',
                $billing->id
            );

            DB::commit();
            return redirect()->route('dashboard.billings.index')->with('success', 'Billing Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Billing Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view billing');
        try {
            $billing = Billing::with('items', 'vehicleCase')->findOrFail($id);
            $payments = Payment::where('billing_id', $billing->id)->get();
            return view('dashboard.billings.show', compact('billing', 'payments'));
        } catch (\Throwable $th) {
            Log::error("Billing Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update billing');
        try {
            $billing = Billing::with('items', 'vehicleCase')->findOrFail($id);
            $cases = VehicleCase::latest()->get();
            return view('dashboard.billings.edit', compact('billing', 'cases'));
        } catch (\Throwable $th) {
            Log::error("Billing Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update billing');

        $validator = Validator::make($request->all(), [
            'vehicle_case_id' => 'required|exists:vehicle_cases,id',
            'billing_type' => 'required|in:local,out_of_city',
            'billing_name' => 'required|string|max:255',
            'billing_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $billing = Billing::findOrFail($id);
            $billing->billing_type = $request->billing_type;
            $billing->billing_name = $request->billing_name;
            $billing->billing_date = $request->billing_date;
            $billing->total_amount = $request->total_amount;
            $billing->paid_amount = $request->paid_amount;
            $billing->remaining_amount = $request->remaining_amount;

            $billing->status =
                $request->remaining_amount == 0
                ? 'paid'
                : ($request->paid_amount > 0 ? 'partial' : 'unpaid');

            $billing->description = $request->description;
            $billing->save();

            BillingItem::where('billing_id', $billing->id)->delete();
            foreach ($request->items as $item) {
                $billingItem = new BillingItem();
                $billingItem->billing_id = $billing->id;
                $billingItem->item_name = $item['name'];
                $billingItem->item_amount = $item['amount'];
                $billingItem->save();
            }

            $payment = Payment::where('billing_id', $billing->id)->first();

            if ($request->paid_amount > 0) {
                if ($payment) {
                    $payment->amount = $request->paid_amount;
                    $payment->payment_date = $request->billing_date;
                    $payment->payment_method = $request->payment_method ?? 'cash';
                    $payment->save();
                } else {
                    $payment = new Payment();
                    $payment->transaction_id = 'TXN-' . time() . rand(100, 999);
                    $payment->billing_id = $billing->id;
                    $payment->amount = $request->paid_amount;
                    $payment->payment_date = $request->billing_date;
                    $payment->payment_method = $request->payment_method ?? 'cash';
                    $payment->save();
                }
            } else {
                if ($payment) {
                    $payment->delete();
                }
            }

            DB::commit();

            $adminUsers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'super-admin']);
            })->get();

            app('notificationService')->notifyUsers(
                $adminUsers,
                'Bill #' . $billing->bill_no . ' has been updated by ' . auth()->user()->name,
                'billings',
                $billing->id
            );

            return redirect()
                ->route('dashboard.billings.index')
                ->with('success', 'Billing Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Billing Update Failed', [
                'error' => $th->getMessage()
            ]);

            return redirect()->back()
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete billing');
        try {
            $billing = Billing::findOrFail($id);
            $billing->delete();
            return redirect()->route('dashboard.billings.index')->with('success', 'Billing Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error("Billing Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function verifyBilling(string $bill_no)
    {
        try {
            $billing = Billing::with('items', 'vehicleCase')->where('bill_no', $bill_no)->first();
            if (!$billing) {
                return redirect()->back()->with('error', "Bill not found!");
            }

            $payments = Payment::where('billing_id', $billing->id)->orderBy('payment_date', 'desc')->get();
            return view('frontend.bill', compact('billing', 'payments'));
        } catch (\Throwable $th) {
            Log::error("Billing Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function testing()
    {

        try {
            return view('frontend.testing');
        } catch (\Throwable $th) {
            Log::error("Billing Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
