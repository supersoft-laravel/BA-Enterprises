@extends('layouts.master')

@section('title', __('Customer Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.customers.index') }}">{{ __('Customers') }}</a></li>
    <li class="breadcrumb-item active">{{ $customer->customer_code }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Customer Info Card --}}
        <div class="card mb-4">
            <div class="card-header bg-label-primary border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-primary fw-semibold">
                    <i class="ti ti-user me-2"></i>Customer Profile
                </h5>
                <div class="d-flex gap-2">
                    <a href="javascript:history.back()" class="btn btn-sm btn-label-secondary waves-effect waves-light">
                        <i class="ti ti-arrow-left me-1"></i>Back
                    </a>
                    @can('update customer')
                        <a href="{{ route('dashboard.customers.edit', $customer->id) }}"
                            class="btn btn-sm btn-primary waves-effect waves-light">
                            <i class="ti ti-edit me-1"></i>Edit
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <p class="mb-1 text-muted small">Customer Code</p>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-label-primary fs-6">{{ $customer->customer_code }}</span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted small">Name</p>
                        <p class="fw-semibold mb-0">{{ $customer->name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted small">Mobile</p>
                        <p class="fw-semibold mb-0">{{ $customer->mobile ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Linked Cases --}}
        <div class="card mb-4">
            <div class="card-header border-bottom py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="ti ti-car me-2"></i>Cases
                    <span class="badge bg-label-info ms-2">{{ $customer->vehicleCases->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                @if ($customer->vehicleCases->isEmpty())
                    <p class="text-muted p-4 mb-0">No cases linked to this customer.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle No.</th>
                                    <th>City</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer->vehicleCases as $i => $case)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $case->vehicle_no ?? '—' }}</td>
                                        <td>{{ ucfirst($case->city ?? '—') }}</td>
                                        <td>{{ $case->case_date ? \Carbon\Carbon::parse($case->case_date)->format('d/m/Y') : '—' }}</td>
                                        <td>
                                            <span class="badge bg-label-{{ $case->status === 'open' ? 'success' : 'danger' }}">
                                                {{ ucfirst($case->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @can('view case')
                                                <a href="{{ route('dashboard.cases.show', $case->id) }}"
                                                    class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill"
                                                    data-bs-toggle="tooltip" title="View Case">
                                                    <i class="ti ti-eye ti-md"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Billing Summary --}}
        <div class="card mb-4">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="ti ti-receipt me-2"></i>Billing
                </h5>
                @if ($customer->billing)
                    @can('view billing')
                        <a href="{{ route('dashboard.billings.show', $customer->billing->id) }}"
                            class="btn btn-sm btn-label-info waves-effect waves-light">
                            <i class="ti ti-eye me-1"></i>View Full Bill
                        </a>
                    @endcan
                @endif
            </div>
            <div class="card-body p-4">
                @if (!$customer->billing)
                    <p class="text-muted mb-0">No bill created for this customer yet.</p>
                @else
                    @php $billing = $customer->billing; @endphp
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <p class="mb-1 text-muted small">Bill No.</p>
                            <p class="fw-semibold mb-0">{{ $billing->bill_no ?? '—' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1 text-muted small">Total</p>
                            <p class="fw-semibold mb-0">{{ \App\Helpers\Helper::formatCurrency($billing->total_amount) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1 text-muted small">Paid</p>
                            <p class="fw-semibold text-success mb-0">{{ \App\Helpers\Helper::formatCurrency($billing->paid_amount) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1 text-muted small">Remaining</p>
                            <p class="fw-semibold text-{{ $billing->remaining_amount > 0 ? 'danger' : 'success' }} mb-0">
                                {{ \App\Helpers\Helper::formatCurrency($billing->remaining_amount) }}
                            </p>
                        </div>
                    </div>

                    @php
                        $statusClass = ['paid' => 'success', 'partial' => 'warning', 'unpaid' => 'danger'];
                    @endphp
                    <span class="badge bg-label-{{ $statusClass[$billing->status] ?? 'secondary' }}">
                        {{ ucfirst($billing->status) }}
                    </span>

                    @if ($billing->items->isNotEmpty())
                        <hr>
                        <h6 class="fw-semibold mb-3">Billing Items</h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->items as $j => $item)
                                        <tr>
                                            <td>{{ $j + 1 }}</td>
                                            <td>{{ $item->item_name }}</td>
                                            <td>{{ \App\Helpers\Helper::formatCurrency($item->item_amount) }}</td>
                                            <td>{{ $item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d/m/Y') : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if ($billing->payments->isNotEmpty())
                        <hr>
                        <h6 class="fw-semibold mb-3">Payments</h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->payments as $payment)
                                        <tr>
                                            <td><code>{{ $payment->transaction_id }}</code></td>
                                            <td>{{ \App\Helpers\Helper::formatCurrency($payment->amount) }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            </div>
        </div>

    </div>
@endsection

@section('script')
@endsection
