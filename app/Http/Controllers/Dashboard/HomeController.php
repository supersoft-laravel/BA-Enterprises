<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\VehicleCase;
use App\Models\CaseTransfer;
use App\Models\CaseAlteration;
use App\Models\CasePermit;
use App\Models\CaseTax;
use App\Models\CaseInsurance;
use App\Models\CaseFileReturn;
use App\Models\CaseOther;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the main dashboard with pending payments and cases
     */
    public function index()
    {
        // Get pending payments from billings table
        $pendingPayments = Billing::with('vehicleCase', 'items')
            ->whereIn('status', ['unpaid', 'partial'])
            ->where('remaining_amount', '>', 0)
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($billing) {
                $serviceName = $billing->items->first()->item_name ?? 'Unknown';

                return (object)[
                    'billing_id' => $billing->id,
                    'city' => $billing->vehicleCase->city ?? 'N/A',
                    'service' => $serviceName,
                    'party' => $billing->vehicleCase->party_name ?? 'N/A',
                    'vehicle' => $billing->vehicleCase->vehicle_no ?? 'N/A',
                    'amount' => $billing->remaining_amount,
                    'created_at' => $billing->created_at
                ];
            });

        // Get pending cases from vehicle_cases table
        $pendingCases = VehicleCase::with(['transfer', 'alteration', 'permit', 'tax', 'insurance', 'fileReturn', 'other'])
            ->where('status', 'open')
            ->latest()
            ->get()
            ->map(function ($case) {
                // Determine service type and description
                $serviceType = 'Unknown';
                $title = 'Pending';
                $description = 'Awaiting processing';

                if ($case->transfer) {
                    $serviceType = 'Transfer';
                    $title = 'Transfer Pending';
                    $description = "From: {$case->transfer->from_name} → To: {$case->transfer->to_name}";
                } elseif ($case->alteration) {
                    $serviceType = 'Alteration';
                    $title = 'Alteration Required';
                    $description = "From: {$case->alteration->from_name} → To: {$case->alteration->to_name}";
                } elseif ($case->fileReturn) {
                    $serviceType = 'File Return';
                    $title = 'File Return Pending';
                    $description = "From: {$case->fileReturn->from_name} → To: {$case->fileReturn->to_name}";
                } elseif ($case->permit) {
                    $serviceType = 'Route Permit';
                    $title = 'Permit ' . ($case->permit->type ?? 'Application');
                    $description = $case->permit->details ?? 'Awaiting approval';
                } elseif ($case->tax) {
                    $serviceType = 'Tax';
                    $title = 'Tax Period';
                    $description = "From: {$case->tax->tax_from} To: {$case->tax->tax_to}";
                } elseif ($case->insurance) {
                    $serviceType = 'Insurance';
                    $title = 'Insurance Pending';
                    $description = $case->insurance->details ?? 'Policy required';
                } elseif ($case->other) {
                    $serviceType = 'Others';
                    $title = 'Other Work Pending';
                    $description = $case->other->details ?? 'Additional work required';
                }

                return (object)[
                    'case_id' => $case->id,
                    'city' => $case->city ?? 'N/A',
                    'vehicle_no' => $case->vehicle_no ?? 'N/A',
                    'party_name' => $case->party_name ?? 'N/A',
                    'service' => $serviceType,
                    'title' => $title,
                    'desc' => $description,
                    'created_at' => $case->created_at // Include timestamp for reference
                ];
            });

        return view('dashboard.index', compact('pendingPayments', 'pendingCases'));
    }

    /**
     * Get pending items for AJAX refresh
     */
    public function getPendingItems(Request $request)
    {
        // Get pending payments
        $pendingPayments = Billing::with('vehicleCase', 'items')
            ->whereIn('status', ['unpaid', 'partial'])
            ->where('remaining_amount', '>', 0)
            ->latest()
            ->get()
            ->map(function ($billing) {
                $serviceName = $billing->items->first()->item_name ?? 'Unknown';

                return [
                    'billing_id' => $billing->id,
                    'city' => $billing->vehicleCase->city ?? 'N/A',
                    'service' => $serviceName,
                    'party' => $billing->vehicleCase->party_name ?? 'N/A',
                    'vehicle' => $billing->vehicleCase->vehicle_no ?? 'N/A',
                    'amount' => $billing->remaining_amount,
                    'created_at' => $billing->created_at->format('Y-m-d H:i:s') // Include formatted timestamp
                ];
            });

        // Get pending cases
        $pendingCases = VehicleCase::with(['transfer', 'alteration', 'permit', 'tax', 'insurance', 'fileReturn', 'other'])
            ->where('status', 'open')
            ->latest()
            ->get()
            ->map(function ($case) {
                $serviceType = 'Unknown';
                $title = 'Pending';
                $description = 'Awaiting processing';

                if ($case->transfer) {
                    $serviceType = 'Transfer';
                    $title = 'Transfer Pending';
                    $description = "From: {$case->transfer->from_name} → To: {$case->transfer->to_name}";
                } elseif ($case->alteration) {
                    $serviceType = 'Alteration';
                    $title = 'Alteration Required';
                    $description = "From: {$case->alteration->from_name} → To: {$case->alteration->to_name}";
                } elseif ($case->fileReturn) {
                    $serviceType = 'File Return';
                    $title = 'File Return Pending';
                    $description = "From: {$case->fileReturn->from_name} → To: {$case->fileReturn->to_name}";
                } elseif ($case->permit) {
                    $serviceType = 'Route Permit';
                    $title = 'Permit ' . ($case->permit->type ?? 'Application');
                    $description = $case->permit->details ?? 'Awaiting approval';
                } elseif ($case->tax) {
                    $serviceType = 'Tax';
                    $title = 'Tax Period';
                    $description = "From: {$case->tax->tax_from} To: {$case->tax->tax_to}";
                } elseif ($case->insurance) {
                    $serviceType = 'Insurance';
                    $title = 'Insurance Pending';
                    $description = $case->insurance->details ?? 'Policy required';
                } elseif ($case->other) {
                    $serviceType = 'Others';
                    $title = 'Other Work Pending';
                    $description = $case->other->details ?? 'Additional work required';
                }

                return [
                    'case_id' => $case->id,
                    'city' => $case->city ?? 'N/A',
                    'vehicle_no' => $case->vehicle_no ?? 'N/A',
                    'party_name' => $case->party_name ?? 'N/A',
                    'service' => $serviceType,
                    'title' => $title,
                    'desc' => $description,
                    'created_at' => $case->created_at->format('Y-m-d H:i:s') // Include formatted timestamp
                ];
            });

        return response()->json([
            'payments' => $pendingPayments,
            'cases' => $pendingCases
        ]);
    }



    /**
     * Display the statistics dashboard
     */
    public function dashboardStatsIndex()
    {
        return view('dashboard.dashboard-stats');
    }

    /**
     * Refresh data for AJAX calls
     */
    public function refreshData(Request $request)
    {
        $type = $request->get('type');
        $data = $this->getRefreshData($type);

        return response()->json($data);
    }

    /**
     * Get refresh data based on type
     */
    private function getRefreshData($type)
    {
        switch($type) {
            case 'summary':
                return [
                    'total_transfers' => CaseTransfer::count(),
                    'total_alterations' => CaseAlteration::count(),
                    'total_permits' => CasePermit::count(),
                    'active_vehicles' => VehicleCase::where('status', 'open')->count(),
                    'total_revenue' => Billing::sum('paid_amount'),
                    'pending_payments' => Billing::where('status', '!=', 'paid')->sum('remaining_amount'),
                    'monthly_revenue' => Billing::whereMonth('billing_date', now()->month)
                        ->whereYear('billing_date', now()->year)
                        ->sum('paid_amount'),
                ];
            case 'transfers':
                return $this->getTransferChartData();
            case 'permits':
                return $this->getPermitStatusData();
            case 'recent':
                return $this->getRecentTransfers();
            case 'revenue':
                return $this->getMonthlyRevenue();
            case 'work_types':
                return $this->getWorkTypeDistribution();
            default:
                return ['error' => 'Invalid type'];
        }
    }

    /**
     * Get transfer chart data for last X months
     */
    private function getTransferChartData($months = 6)
    {
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M');

            $count = CaseTransfer::whereHas('vehicleCase', function($query) use ($date) {
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
            })->count();

            $data[] = $count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Get permit status distribution
     */
    private function getPermitStatusData()
    {
        $rta = CasePermit::where('type', 'RTA')->count();
        $pta = CasePermit::where('type', 'PTA')->count();
        $others = CasePermit::where('type', 'Others')->count();

        return [
            'labels' => ['RTA', 'PTA', 'Others'],
            'data' => [$rta, $pta, $others]
        ];
    }

    /**
     * Get recent transfers
     */
    private function getRecentTransfers($limit = 10)
    {
        $transfers = CaseTransfer::with(['vehicleCase' => function($query) {
            $query->select('id', 'vehicle_no', 'status', 'created_at');
        }])
        ->latest()
        ->take($limit)
        ->get()
        ->map(function($transfer) {
            $case = $transfer->vehicleCase;
            return [
                'id' => $transfer->id,
                'case_no' => 'VC-' . str_pad($case->id ?? 0, 5, '0', STR_PAD_LEFT),
                'vehicle_reg_no' => $case->vehicle_no ?? 'N/A',
                'from_name' => $transfer->from_name,
                'to_name' => $transfer->to_name,
                'date' => optional($transfer->created_at)->format('d M Y') ?? 'N/A',
                'status' => $case->status ?? 'open',
            ];
        });

        return $transfers;
    }

    /**
     * Get monthly revenue for chart
     */
    private function getMonthlyRevenue($months = 6)
    {
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $revenue = Billing::whereYear('billing_date', $date->year)
                ->whereMonth('billing_date', $date->month)
                ->sum('paid_amount');

            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get work type distribution
     */
    private function getWorkTypeDistribution()
    {
        $workTypes = [
            'Transfers' => CaseTransfer::count(),
            'Alterations' => CaseAlteration::count(),
            'Tax' => CaseTax::count(),
            'Insurance' => CaseInsurance::count(),
            'Permits' => CasePermit::count(),
            'File Returns' => CaseFileReturn::count(),
            'Others' => CaseOther::count(),
        ];

        // Filter out zero values
        $filtered = array_filter($workTypes);

        return [
            'labels' => array_keys($filtered),
            'data' => array_values($filtered)
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
