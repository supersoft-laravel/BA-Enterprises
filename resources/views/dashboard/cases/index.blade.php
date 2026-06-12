@extends('layouts.master')

@section('title', __('Cases'))

@section('css')
<style>
    .customer-row { cursor: pointer; transition: background 0.12s; }
    .customer-row:hover td { background: rgba(29,78,216,0.04); }
    .bill-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.65rem; border-radius: 9999px;
        font-size: 0.74rem; font-weight: 600;
    }
</style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Cases') }}</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0 fw-bold">Customers &amp; Cases</h5>
            <span class="badge bg-label-secondary">{{ $customerGroups->count() }} customers
                @if($legacyCount > 0)
                    <span class="ms-1 text-warning">+ {{ $legacyCount }} uncategorized</span>
                @endif
            </span>
        </div>

        <div class="card-datatable table-responsive">
            <table class="table border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Mobile</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Remaining</th>
                        <th class="text-center">Bill</th>
                        <th class="text-center">Cases</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customerGroups as $index => $customer)
                        @php $bill = $customer->billing; @endphp
                        <tr class="customer-row"
                            onclick="window.location='{{ route('dashboard.cases.customer-cases', $customer->id) }}'">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-label-primary" style="font-size:0.72rem;">{{ $customer->customer_code }}</span>
                            </td>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td class="text-secondary">{{ $customer->mobile ?? '—' }}</td>
                            <td class="text-end fw-semibold">
                                {{ $bill ? '₨ ' . number_format($bill->total_amount, 0) : '—' }}
                            </td>
                            <td class="text-end fw-semibold" style="color:#059669;">
                                {{ $bill ? '₨ ' . number_format($bill->paid_amount, 0) : '—' }}
                            </td>
                            <td class="text-end fw-semibold" style="color:#dc2626;">
                                {{ $bill ? '₨ ' . number_format($bill->remaining_amount, 0) : '—' }}
                            </td>
                            <td class="text-center">
                                @if($bill)
                                    @php
                                        $billColors = [
                                            'paid'    => ['bg-label-success',  'Paid'],
                                            'partial' => ['bg-label-warning',  'Partial'],
                                            'unpaid'  => ['bg-label-danger',   'Unpaid'],
                                        ];
                                        [$cls, $lbl] = $billColors[$bill->status] ?? ['bg-label-secondary', ucfirst($bill->status)];
                                    @endphp
                                    <span class="bill-badge {{ $cls }}">{{ $lbl }}</span>
                                @else
                                    <span class="bill-badge bg-label-secondary">No Bill</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-info">{{ $customer->vehicle_cases_count }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-4">
                                <i class="ti ti-folder-off ti-lg me-2"></i>No cases found. Create the first case from the Dashboard.
                            </td>
                        </tr>
                    @endforelse

                    @if($legacyCount > 0)
                        <tr>
                            <td>—</td>
                            <td colspan="8" class="text-warning fw-semibold">
                                <i class="ti ti-alert-triangle me-1"></i>
                                {{ $legacyCount }} uncategorized case(s) with no customer linked (legacy data)
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
