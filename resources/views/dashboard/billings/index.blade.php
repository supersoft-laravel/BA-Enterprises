@extends('layouts.master')

@section('title', __('Billings'))

@section('css')
<style>
    .billings-table th,
    .billings-table td { white-space: nowrap; }
</style>
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Billings') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Billings List Table -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                @canany(['view billing'])
                    <a href="{{ route('dashboard.billings.custom-invoice') }}"
                       class="btn btn-primary waves-effect waves-light">
                        <i class="ti ti-file-invoice me-1"></i>Custom Invoice
                    </a>
                @endcan

                <form method="GET" action="{{ route('dashboard.billings.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
                    <select name="bill_id" class="form-select form-select-sm" style="width:210px;">
                        <option value="">Select Bill</option>
                        @foreach($allBillings as $bill)
                            <option value="{{ $bill->id }}" {{ request('bill_id') == $bill->id ? 'selected' : '' }}>
                                {{ $bill->bill_no }}
                            </option>
                        @endforeach
                    </select>

                    <select name="service" class="form-select form-select-sm" style="width:190px;">
                        <option value="">Select Service</option>
                        @foreach($services as $svc)
                            <option value="{{ $svc }}" {{ request('service') == $svc ? 'selected' : '' }}>{{ $svc }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light">
                        <i class="ti ti-filter ti-xs me-1"></i>Filter
                    </button>

                    @if(request('bill_id') || request('service'))
                        <a href="{{ route('dashboard.billings.index') }}" class="btn btn-sm btn-outline-secondary waves-effect">
                            <i class="ti ti-x ti-xs me-1"></i>Clear
                        </a>
                    @endif
                </form>
            </div>
        {{-- FILTER RESULTS --}}
        @if($filteredItems !== null)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    Results — <span class="text-primary">{{ request('service') }}</span>
                    &nbsp;in&nbsp;
                    <span class="text-primary">{{ $allBillings->firstWhere('id', request('bill_id'))->bill_no ?? '' }}</span>
                    <span class="badge bg-label-secondary ms-2">{{ $filteredItems->count() }} item(s)</span>
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table border-top mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Vehicle No') }}</th>
                            <th>{{ __('Service') }}</th>
                            <th>{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filteredItems as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge bg-label-secondary">
                                        {{ $item->vehicleCase?->vehicle_no ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $item->item_name }}</td>
                                <td class="fw-semibold">{{ \App\Helpers\Helper::formatCurrency($item->item_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No items found for this filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($filteredItems->count() > 0)
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="fw-bold text-primary">{{ \App\Helpers\Helper::formatCurrency($filteredItems->sum('item_amount')) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @endif

            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables billings-table">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Bill No') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Paid') }}</th>
                            <th>{{ __('Remaining') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['update billing', 'view billing'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billings as $index => $billing)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-semibold" style="font-size:0.82rem;">{{ $billing->bill_no ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $billing->customer->name ?? ($billing->vehicleCase->party_name ?? 'N/A') }}</td>
                                <td class="fw-semibold">Rs. {{ number_format($billing->total_amount, 0) }}</td>
                                <td class="fw-semibold" style="color:#059669;">
                                    Rs. {{ number_format($billing->paid_amount, 0) }}
                                </td>
                                <td class="fw-semibold text-{{ $billing->remaining_amount > 0 ? 'danger' : 'success' }}">
                                    Rs. {{ number_format($billing->remaining_amount, 0) }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($billing->billing_date)->format('d/m/Y') }}</td>
                                @php
                                    $statusClass = [
                                        'paid' => 'success',
                                        'partial' => 'warning',
                                        'unpaid' => 'danger',
                                    ];
                                @endphp

                                <td>
                                    <span class="badge me-4 bg-label-{{ $statusClass[$billing->status] ?? 'secondary' }}">
                                        {{ ucfirst($billing->status) }}
                                    </span>
                                </td>
                                @canany(['update billing', 'view billing'])
                                    <td class="d-flex">
                                        @canany(['update billing'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.billings.edit', $billing->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Billing') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view billing'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.billings.show', $billing->id) }}"
                                                    class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Print Billing') }}" target="_blank">
                                                    <i class="ti ti-printer ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script src="{{asset('assets/js/app-user-list.js')}}"></script> --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
