@extends('layouts.master')
@section('title', 'Case Details #' . $case->id)

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.cases.index') }}">Cases</a></li>
    <li class="breadcrumb-item active">Case #{{ $case->id }}</li>
@endsection

@section('css')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 60px;
        text-align: center;
    }

    .timeline-item-marker-indicator {
        width: 32px;
        height: 32px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline-item-marker-indicator i {
        font-size: 16px;
    }

    .timeline-item-content {
        padding-left: 20px;
        border-left: 2px solid #e9ecef;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header bg-label-primary d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 text-primary">
                    <i class="ti ti-file-info me-2"></i>
                    {{ $case->vehicle_no }} • {{ $case->party_name }}
                </h5>
                <small class="text-muted">Case Details #{{ $case->id }}</small>
            </div>
            <div>
                <a href="{{ route('dashboard.billings.show', $case->billing->id) }}" class="btn btn-info btn-sm me-2" target="_blank">
                    <i class="ti ti-receipt me-1"></i> View Billing Invoice
                </a>
                <a href="{{ route('dashboard.cases.index') }}" class="btn btn-label-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body m-3">

            {{-- Basic Information --}}
            <div class="row mb-5">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="ti ti-info-circle me-2"></i> Basic Information
                    </h6>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">City</label>
                    <p class="mb-0 fw-semibold">{{ $case->city ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Vehicle No.</label>
                    <p class="mb-0 fw-semibold">{{ $case->vehicle_no ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Party Name</label>
                    <p class="mb-0 fw-semibold">{{ $case->party_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Party Mobile</label>
                    <p class="mb-0 fw-semibold">{{ $case->party_mobile ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Vehicle Make</label>
                    <p class="mb-0 fw-semibold">{{ $case->vehicle_make ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Vehicle Model</label>
                    <p class="mb-0 fw-semibold">{{ $case->vehicle_model ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Engine No.</label>
                    <p class="mb-0 fw-semibold">{{ $case->engine_no ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Chassis No.</label>
                    <p class="mb-0 fw-semibold">{{ $case->chassis_no ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Case Date</label>
                    <p class="mb-0 fw-semibold">{{ $case->case_date ? \Carbon\Carbon::parse($case->case_date)->format('d M, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-medium text-muted">Comment</label>
                    <p class="mb-0 fw-semibold">{{ $case->comment ?? 'No comments' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-medium text-muted">Submitted At</label>
                    <p class="mb-0 fw-semibold">{{ $case->submitted_at ? \Carbon\Carbon::parse($case->submitted_at)->format('d M, Y h:i A') : 'N/A' }}</p>
                </div>
            </div>

            {{-- Billing Information --}}
            @if($case->billing)
            <div class="row mb-5">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="ti ti-currency-dollar me-2"></i> Billing Information
                    </h6>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="fw-medium text-muted">Bill No.</label>
                    <p class="mb-0 fw-semibold">
                        <span class="badge bg-primary">{{ $case->billing->bill_no ?? 'N/A' }}</span>
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="fw-medium text-muted">Billing Type</label>
                    <p class="mb-0 fw-semibold">{{ ucfirst($case->billing->billing_type ?? 'N/A') }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="fw-medium text-muted">Billing Date</label>
                    <p class="mb-0 fw-semibold">{{ $case->billing->billing_date ? \Carbon\Carbon::parse($case->billing->billing_date)->format('d M, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="fw-medium text-muted">Status</label>
                    <p class="mb-0">
                        @php
                            $statusClass = match($case->billing->status) {
                                'paid' => 'success',
                                'partial' => 'warning',
                                default => 'danger'
                            };
                        @endphp
                        <span class="badge bg-label-{{ $statusClass }} fs-6">
                            {{ ucfirst($case->billing->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Total Amount</label>
                    <p class="mb-0 fw-semibold text-primary fs-5">Rs. {{ number_format($case->billing->total_amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Paid Amount</label>
                    <p class="mb-0 fw-semibold text-success">Rs. {{ number_format($case->billing->paid_amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Remaining Amount</label>
                    <p class="mb-0 fw-semibold text-danger">Rs. {{ number_format($case->billing->remaining_amount, 2) }}</p>
                </div>
                @if($case->billing->description)
                <div class="col-12 mb-3">
                    <label class="fw-medium text-muted">Description</label>
                    <p class="mb-0">{{ $case->billing->description }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Services / Billing Items --}}
            @if($case->billing && $case->billing->items && $case->billing->items->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="ti ti-shopping-cart me-2"></i> Services Rendered
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Service Name</th>
                                    <th class="text-end">Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($case->billing->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td class="text-end">{{ number_format($item->item_amount, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td colspan="2" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">Rs. {{ number_format($case->billing->total_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Work Details Section --}}
            <h6 class="text-primary border-bottom pb-2 mb-4">
                <i class="ti ti-list-details me-2"></i> Work Details
            </h6>

            <div class="row g-4">

                {{-- Transfer Details --}}
                @if($case->transfer)
                <div class="col-12">
                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-exchange me-2"></i> Transfer Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-dark">From</h6>
                                    <p><strong>Name:</strong> {{ $case->transfer->from_name }}</p>
                                    <p><strong>S/O:</strong> {{ $case->transfer->from_s_o ?? 'N/A' }}</p>
                                    <p><strong>NIC:</strong> {{ $case->transfer->from_nic }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-dark">To</h6>
                                    <p><strong>Name:</strong> {{ $case->transfer->to_name }}</p>
                                    <p><strong>S/O:</strong> {{ $case->transfer->to_s_o ?? 'N/A' }}</p>
                                    <p><strong>NIC:</strong> {{ $case->transfer->to_nic }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Alteration Details --}}
                @if($case->alteration)
                <div class="col-12">
                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-adjustments me-2"></i> Alteration Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-dark">From</h6>
                                    <p><strong>Name:</strong> {{ $case->alteration->from_name }}</p>
                                    <p><strong>S/O:</strong> {{ $case->alteration->from_s_o ?? 'N/A' }}</p>
                                    <p><strong>NIC:</strong> {{ $case->alteration->from_nic }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-dark">To</h6>
                                    <p><strong>Name:</strong> {{ $case->alteration->to_name }}</p>
                                    <p><strong>S/O:</strong> {{ $case->alteration->to_s_o ?? 'N/A' }}</p>
                                    <p><strong>NIC:</strong> {{ $case->alteration->to_nic }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- File Return Details --}}
                @if($case->fileReturn)
                <div class="col-12">
                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-file-return me-2"></i> File Return Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-dark">From</h6>
                                    <p><strong>Name:</strong> {{ $case->fileReturn->from_name }}</p>
                                    <p><strong>S/O:</strong> {{ $case->fileReturn->from_s_o ?? 'N/A' }}</p>
                                    <p><strong>NIC:</strong> {{ $case->fileReturn->from_nic }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-dark">To</h6>
                                    <p><strong>Name:</strong> {{ $case->fileReturn->to_name }}</p>
                                    <p><strong>S/O:</strong> {{ $case->fileReturn->to_s_o ?? 'N/A' }}</p>
                                    <p><strong>NIC:</strong> {{ $case->fileReturn->to_nic }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Route Permit Details --}}
                @if($case->permit)
                <div class="col-md-6">
                    <div class="card border-primary shadow-sm h-100">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-map me-2"></i> Route Permit Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <p><strong>Type:</strong>
                                <span class="badge bg-info">{{ $case->permit->type ?? 'N/A' }}</span>
                            </p>
                            <p><strong>Details:</strong> {{ $case->permit->details ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- FC (Fitness) Details --}}
                @if($case->fitness)
                <div class="col-md-6">
                    <div class="card border-primary shadow-sm h-100">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-health me-2"></i> Fitness Certificate (FC) Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <p><strong>Truck Type:</strong>
                                <span class="badge bg-success">{{ $case->fitness->type ?? 'N/A' }}</span>
                            </p>
                            <p><strong>Details:</strong> {{ $case->fitness->details ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tax Details --}}
                @if($case->tax)
                <div class="col-md-6">
                    <div class="card border-primary shadow-sm h-100">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-calendar me-2"></i> Tax Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <p><strong>From Period:</strong> {{ $case->tax->tax_from ?? 'N/A' }}</p>
                            <p><strong>Upto:</strong> {{ $case->tax->tax_to ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Insurance Details --}}
                @if($case->insurance)
                <div class="col-md-6">
                    <div class="card border-primary shadow-sm h-100">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-shield me-2"></i> Insurance Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <p><strong>Details:</strong> {{ $case->insurance->details ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Others Details --}}
                @if($case->other)
                <div class="col-12">
                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-dots me-2"></i> Other Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <p>{{ $case->other->details ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Payment History --}}
                @if($case->billing && $case->billing->payments && $case->billing->payments->count() > 0)
                <div class="col-12 mt-3">
                    <div class="card border-info shadow-sm">
                        <div class="card-header bg-label-info">
                            <strong><i class="ti ti-credit-card me-2"></i> Payment History</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Amount</th>
                                            <th>Payment Date</th>
                                            <th>Method</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($case->billing->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->transaction_id }}</td>
                                            <td class="text-success">Rs. {{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                                            <td>{{ ucfirst($payment->payment_method) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- If no work details added yet --}}
                @if(!$case->transfer && !$case->alteration && !$case->fileReturn && !$case->permit && !$case->fitness && !$case->tax && !$case->insurance && !$case->other)
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            <i class="ti ti-info-circle me-2"></i>
                            No additional work details have been added yet for this case.
                        </div>
                    </div>
                @endif

            </div>

            {{-- Activities Section --}}
            <div class="row mb-5 mt-5">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h6 class="text-primary mb-0">
                            <i class="ti ti-activity me-2"></i> Recent Activities
                        </h6>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                            <i class="ti ti-plus me-1"></i> Add Activity
                        </button>
                    </div>

                    {{-- Activities Timeline --}}
                    <div class="timeline">
                        @if($caseActivities && $caseActivities->count() > 0)
                            @foreach($caseActivities as $activity)
                                <div class="timeline-item mb-3">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-indicator bg-label-primary">
                                            <i class="ti ti-activity"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-item-content">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                            <div>
                                                <span class="badge bg-primary me-2">{{ $activity->activity_type }}</span>
                                                <small class="text-muted">
                                                    <i class="ti ti-calendar me-1"></i>
                                                    {{ $activity->created_at->format('d M, Y h:i A') }}
                                                </small>
                                            </div>
                                        </div>
                                        @if($activity->description)
                                            <p class="mb-0 text-dark">{{ $activity->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-info-circle me-1"></i>
                                No activities recorded yet. Click "Add Activity" to get started.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Add Activity Modal --}}
            <div class="modal fade" id="addActivityModal" tabindex="-1" aria-labelledby="addActivityModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addActivityModalLabel">
                                <i class="ti ti-activity me-2"></i> Add New Activity
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addActivityForm" action="{{ route('dashboard.cases.activities.store', $case->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="case_id" value="{{ $case->id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="activity_type" class="form-label required">Activity Type</label>
                                    <input type="text"
                                        class="form-control"
                                        id="activity_type"
                                        name="activity_type"
                                        placeholder="e.g., Document Submitted, Status Update, Meeting Scheduled"
                                        required>
                                    <div class="invalid-feedback" id="activity_type_error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control"
                                            id="description"
                                            name="description"
                                            rows="4"
                                            placeholder="Enter detailed description of the activity..."></textarea>
                                    <div class="invalid-feedback" id="description_error"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitActivityBtn">
                                    <i class="ti ti-check me-1"></i> Add Activity
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        {{-- <div class="card-footer text-end">
            <a href="{{ route('dashboard.cases.edit', $case->id) }}" class="btn btn-warning me-2">
                <i class="ti ti-edit me-2"></i> Edit Case
            </a>
            <a href="{{ route('dashboard.cases.next-steps', $case->id) }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i> Add More Work Details
            </a>
        </div> --}}
    </div>
</div>
@endsection
