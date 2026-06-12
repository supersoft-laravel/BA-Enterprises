<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $this->authorize('view customer');
        try {
            $customers = Customer::withCount('vehicleCases')
                ->with('billing')
                ->latest()
                ->get();
            return view('dashboard.customers.index', compact('customers'));
        } catch (\Throwable $th) {
            Log::error('Customer Index Failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function create()
    {
        $this->authorize('create customer');
        return view('dashboard.customers.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create customer');

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:customers,mobile',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation Error!');
        }

        try {
            $customer = Customer::create([
                'customer_code' => $this->generateCustomerCode(),
                'name'          => $request->name,
                'mobile'        => $request->mobile,
            ]);

            return redirect()->route('dashboard.customers.index')
                ->with('success', 'Customer ' . $customer->customer_code . ' created successfully');
        } catch (\Throwable $th) {
            Log::error('Customer Store Failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function show(string $id)
    {
        $this->authorize('view customer');
        try {
            $customer = Customer::with([
                'vehicleCases',
                'billing.items',
                'billing.payments',
            ])->findOrFail($id);

            return view('dashboard.customers.show', compact('customer'));
        } catch (\Throwable $th) {
            Log::error('Customer Show Failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function edit(string $id)
    {
        $this->authorize('update customer');
        try {
            $customer = Customer::findOrFail($id);
            return view('dashboard.customers.edit', compact('customer'));
        } catch (\Throwable $th) {
            Log::error('Customer Edit Failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function update(Request $request, string $id)
    {
        $this->authorize('update customer');

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:customers,mobile,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation Error!');
        }

        try {
            $customer = Customer::findOrFail($id);
            $customer->update([
                'name'   => $request->name,
                'mobile' => $request->mobile,
            ]);

            return redirect()->route('dashboard.customers.index')
                ->with('success', 'Customer updated successfully');
        } catch (\Throwable $th) {
            Log::error('Customer Update Failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    public function destroy(string $id)
    {
        $this->authorize('delete customer');
        try {
            $customer = Customer::withCount('vehicleCases')->findOrFail($id);

            if ($customer->vehicle_cases_count > 0 || $customer->billing()->exists()) {
                return redirect()->back()
                    ->with('error', 'Customer has existing cases/billings and cannot be deleted.');
            }

            $customer->delete();
            return redirect()->route('dashboard.customers.index')
                ->with('success', 'Customer deleted successfully');
        } catch (\Throwable $th) {
            Log::error('Customer Delete Failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again later');
        }
    }

    // AJAX endpoint — used by the quick-add customer modal in case create/edit forms (Phase 2)
    public function storeAjax(Request $request)
    {
        $this->authorize('create customer');

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:customers,mobile',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $customer = Customer::create([
                'customer_code' => $this->generateCustomerCode(),
                'name'          => $request->name,
                'mobile'        => $request->mobile,
            ]);

            return response()->json([
                'success'  => true,
                'customer' => [
                    'id'            => $customer->id,
                    'customer_code' => $customer->customer_code,
                    'name'          => $customer->name,
                    'mobile'        => $customer->mobile,
                    'label'         => $customer->customer_code . ' — ' . $customer->name . ' — ' . $customer->mobile,
                ],
            ]);
        } catch (\Throwable $th) {
            Log::error('Customer Ajax Store Failed: ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    private function generateCustomerCode(): string
    {
        $last = Customer::orderBy('id', 'desc')->first();
        $next = 1;

        if ($last) {
            $num  = (int) ltrim(str_replace('CUST-', '', $last->customer_code), '0');
            $next = max($num + 1, 1);
        }

        do {
            $code = 'CUST-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            $next++;
        } while (Customer::where('customer_code', $code)->exists());

        return $code;
    }
}
