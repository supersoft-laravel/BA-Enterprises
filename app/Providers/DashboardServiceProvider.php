<?php

namespace App\Providers;

use App\Models\VehicleCase;
use App\Models\CaseTransfer;
use App\Models\CaseAlteration;
use App\Models\CasePermit;
use App\Models\CaseTax;
use App\Models\CaseInsurance;
use App\Models\CaseFitness;
use App\Models\CaseFileReturn;
use App\Models\CaseOther;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share dashboard data with all views that need it
        View::composer('dashboard.dashboard-stats', function ($view) {
            if (auth()->check()) {
                $view->with([
                    'summaryStats' => $this->getSummaryStats(),
                    'transferChart' => $this->getTransferChartData(),
                    'permitStatus' => $this->getPermitStatusData(),
                    'recentTransfers' => $this->getRecentTransfers(),
                    'billingSummary' => $this->getBillingSummary(),
                    'monthlyRevenue' => $this->getMonthlyRevenue(),
                    'workTypeDistribution' => $this->getWorkTypeDistribution(),
                    'recentCases' => $this->getRecentCases(),
                ]);
            }
        });
    }

    /**
     * Get summary statistics for dashboard
     */
    private function getSummaryStats(): array
    {
        return [
            'total_transfers' => CaseTransfer::count(),
            'total_alterations' => CaseAlteration::count(),
            'total_permits' => CasePermit::count(),
            'active_vehicles' => VehicleCase::where('status', 'open')->count(),
            'total_revenue' => Billing::sum('paid_amount'),
            'pending_payments' => Billing::where('status', '!=', 'paid')->sum('remaining_amount'),
            'monthly_revenue' => Billing::whereMonth('billing_date', Carbon::now()->month)
                ->whereYear('billing_date', Carbon::now()->year)
                ->sum('paid_amount'),
        ];
    }

    /**
     * Get transfer chart data
     */
    private function getTransferChartData($months = 6): array
    {
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');

            $count = CaseTransfer::whereHas('vehicleCase', function($query) use ($date) {
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
            })->count();

            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get permit status distribution
     */
    /**
 * Get permit status distribution
 */
private function getPermitStatusData(): array
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
 * Get recent transfers with relations
 */
private function getRecentTransfers($limit = 10)
{
    return CaseTransfer::with(['vehicleCase' => function($query) {
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
}

/**
 * Get work type distribution
 */
private function getWorkTypeDistribution(): array
{
    $workTypes = [
        'Transfers' => CaseTransfer::count(),
        'Alterations' => CaseAlteration::count(),
        'Tax' => CaseTax::count(),
        'Insurance' => CaseInsurance::count(),
        'Permits' => CasePermit::count(),
        'Fitness' => CaseFitness::count(),
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
     * Get billing summary
     */
    private function getBillingSummary(): array
    {
        return [
            'total_revenue' => Billing::sum('paid_amount'),
            'pending_payments' => Billing::where('status', '!=', 'paid')->sum('remaining_amount'),
            'monthly_revenue' => Billing::whereMonth('billing_date', Carbon::now()->month)
                ->whereYear('billing_date', Carbon::now()->year)
                ->sum('paid_amount'),
            'unpaid_count' => Billing::where('status', 'unpaid')->count(),
        ];
    }

    /**
     * Get monthly revenue for chart
     */
    private function getMonthlyRevenue($months = 6): array
    {
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
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
    // private function getWorkTypeDistribution(): array
    // {
    //     $workTypes = VehicleCase::select('work_type', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
    //         ->groupBy('work_type')
    //         ->get();

    //     return [
    //         'labels' => $workTypes->pluck('work_type')->toArray(),
    //         'data' => $workTypes->pluck('total')->toArray()
    //     ];
    // }

    /**
     * Get recent cases with all relations
     */
    private function getRecentCases($limit = 5)
    {
        return VehicleCase::with(['transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness', 'billing'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Public methods for AJAX calls
     */
    public function getRefreshData($type)
    {
        return match($type) {
            'summary' => $this->getSummaryStats(),
            'transfers' => $this->getTransferChartData(),
            'permits' => $this->getPermitStatusData(),
            'recent' => $this->getRecentTransfers(),
            'revenue' => $this->getMonthlyRevenue(),
            'work_types' => $this->getWorkTypeDistribution(),
            default => ['error' => 'Invalid type'],
        };
    }
}
