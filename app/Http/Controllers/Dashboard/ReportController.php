<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\Customer;
use App\Models\VehicleCase;
use Illuminate\Http\Request;


class ReportController extends Controller
{
    public function index()
    {
        return view('dashboard.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $cases = VehicleCase::with('customer')
            ->whereBetween('case_date', [$startDate, $endDate])
            ->orderBy('case_date', 'desc')
            ->get();

        $caseIds = $cases->pluck('id');

        // Items grouped by case
        $itemsByCase = BillingItem::whereIn('vehicle_case_id', $caseIds)
            ->get()
            ->groupBy('vehicle_case_id');

        // Case totals
        $caseTotals = BillingItem::whereIn('vehicle_case_id', $caseIds)
            ->selectRaw('vehicle_case_id, SUM(item_amount) as total')
            ->groupBy('vehicle_case_id')
            ->pluck('total', 'vehicle_case_id');

        // Customer billings for paid/remaining
        $customerIds = $cases->pluck('customer_id')->filter()->unique();
        $billings = Billing::whereIn('customer_id', $customerIds)
            ->get()
            ->keyBy('customer_id');

        // Customer summary: each customer with their cases in this period
        $customers = Customer::whereIn('id', $customerIds)->orderBy('name')->get();

        return view('dashboard.reports.result', compact(
            'cases', 'itemsByCase', 'caseTotals', 'billings', 'customers', 'startDate', 'endDate'
        ));
    }

}
