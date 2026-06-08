<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view payment');
        try {
            $payments = Payment::with('billing.vehicleCase')->latest()->get();
            return view('dashboard.payments.index', compact('payments'));
        } catch (\Throwable $th) {
            Log::error("Payment Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create payment');
        try {
            $billings = Billing::with('vehicleCase')->where('status', '!=', 'paid')->latest()->get();
            return view('dashboard.payments.create', compact('billings'));
        } catch (\Throwable $th) {
            Log::error("Payment Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create payment');

        $validator = Validator::make($request->all(), [
            'billing_id'     => 'required|exists:billings,id',
            'amount'         => 'required|numeric|min:0.01',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $billing = Billing::lockForUpdate()->findOrFail($request->billing_id);

            if ($billing->status === 'paid') {
                return back()->with('error', 'This billing is already fully paid.');
            }

            if ($request->amount > $billing->remaining_amount) {
                return back()->with('error', 'Amount cannot exceed remaining amount.');
            }

            $payment = new Payment();
            $payment->transaction_id = 'TXN-' . time() . rand(100, 999);
            $payment->billing_id = $billing->id;
            $payment->amount = $request->amount;
            $payment->payment_date = $request->payment_date;
            $payment->payment_method = $request->payment_method;
            $payment->save();

            $adminUsers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'super-admin']);
            })->get();
            app('notificationService')->notifyUsers(
                $adminUsers,
                'New payment of Rs. ' . $payment->amount . ' received for Bill #' . $billing->bill_no . ' by ' . auth()->user()->name,
                'payments',
                $payment->id
            );

            $billing->paid_amount += $request->amount;
            $billing->remaining_amount -= $request->amount;

            $billing->remaining_amount = round($billing->remaining_amount, 2);

            if ($billing->remaining_amount <= 0) {
                $billing->remaining_amount = 0;
                $billing->status = 'paid';

                app('notificationService')->notifyUsers(
                    $adminUsers,
                    'Bill #' . $billing->bill_no . ' has been fully paid by ' . auth()->user()->name,
                    'billings',
                    $billing->id
                );
            } elseif ($billing->paid_amount > 0) {
                $billing->status = 'partial';
            } else {
                $billing->status = 'unpaid';
            }

            $billing->save();

            DB::commit();

            return redirect()
                ->route('dashboard.payments.index')
                ->with('success', 'Payment added successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Payment Store Failed', [
                'error' => $th->getMessage()
            ]);

            return back()->with('error', 'Something went wrong! Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete payment');

        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($id);

            // 🔹 Get related billing
            $billing = Billing::lockForUpdate()->findOrFail($payment->billing_id);

            // 🔹 Reverse payment effect
            $billing->paid_amount -= $payment->amount;
            $billing->remaining_amount += $payment->amount;

            // 🔹 Fix negative issues
            if ($billing->paid_amount < 0) {
                $billing->paid_amount = 0;
            }

            if ($billing->remaining_amount < 0) {
                $billing->remaining_amount = 0;
            }

            // 🔹 Update status
            if ($billing->remaining_amount == 0 && $billing->paid_amount > 0) {
                $billing->status = 'paid';
            } elseif ($billing->paid_amount > 0) {
                $billing->status = 'partial';
            } else {
                $billing->status = 'unpaid';
            }

            $billing->save();

            // 🔹 Delete payment
            $payment->delete();

            $adminUsers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'super-admin']);
            })->get();

            app('notificationService')->notifyUsers(
                $adminUsers,
                'Payment of Rs. ' . $payment->amount . ' deleted for Bill #' . $billing->bill_no . ' by ' . auth()->user()->name,
                'payments',
                $payment->id
            );

            DB::commit();

            return redirect()
                ->route('dashboard.payments.index')
                ->with('success', 'Payment Deleted Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error("Payment Delete Failed: " . $th->getMessage());

            return redirect()->back()
                ->with('error', "Something went wrong! Please try again later");
        }
    }
}
