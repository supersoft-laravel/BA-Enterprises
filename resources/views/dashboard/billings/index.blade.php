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
            <div class="card-header">
                @canany(['view billing'])
                    <a href="{{ route('dashboard.billings.custom-invoice') }}"
                       class="btn btn-primary waves-effect waves-light">
                        <i class="ti ti-file-invoice me-1"></i>Customer Invoice
                    </a>
                @endcan
            </div>
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
