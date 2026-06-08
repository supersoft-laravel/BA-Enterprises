<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view invoice');
        try {
            $invoices = Invoice::all();
            return view('dashboard.invoices.index', compact('invoices'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Invoice Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create invoice');
        try {
            return view('dashboard.invoices.create');
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Invoice Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create invoice');
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'customer_id' => 'required|exists:other_users,id',
            'vehicle_export_id' => 'required|exists:vehicle_exports,id',
            'subtotal' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:paid,unpaid,overdue',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $invoice = new Invoice();
            $invoice->invoice_number = $request->invoice_number;
            $invoice->customer_id = $request->customer_id;
            $invoice->vehicle_export_id = $request->vehicle_export_id;
            $invoice->subtotal = $request->subtotal;
            $invoice->tax = $request->tax;
            $invoice->total = $request->total;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->notes = $request->notes;
            $invoice->status = $request->status;
            $invoice->save();

            DB::commit();
            return redirect()->route('dashboard.invoices.index')->with('success', 'Invoice Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Invoice Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
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
        $this->authorize('update invoice');
        try {
            $invoice = Invoice::findOrFail($id);
            return view('dashboard.invoices.edit', compact('invoice'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Invoice Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update invoice');
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $id,
            'customer_id' => 'required|exists:other_users,id',
            'vehicle_export_id' => 'required|exists:vehicle_exports,id',
            'subtotal' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:paid,unpaid,overdue',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $invoice = Invoice::findOrFail($id);
            $invoice->invoice_number = $request->invoice_number;
            $invoice->customer_id = $request->customer_id;
            $invoice->vehicle_export_id = $request->vehicle_export_id;
            $invoice->subtotal = $request->subtotal;
            $invoice->tax = $request->tax;
            $invoice->total = $request->total;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->notes = $request->notes;
            $invoice->status = $request->status;
            $invoice->save();

            DB::commit();
            return redirect()->route('dashboard.invoices.index')->with('success', 'Invoice Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Invoice Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete invoice');
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();
            return redirect()->route('dashboard.invoices.index')->with('success', 'Invoice Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error("Invoice Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
