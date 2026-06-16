@extends('layouts.master')
@section('title', 'Case Details #' . $case->id)

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:history.back()">Cases</a></li>
    <li class="breadcrumb-item active">Case #{{ $case->id }}</li>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}">
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
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('dashboard.cases.print', $case->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                    <i class="ti ti-printer me-1"></i> Print Case
                </a>
                <a href="{{ route('dashboard.cases.invoice', $case->id) }}" class="btn btn-info btn-sm" target="_blank">
                    <i class="ti ti-receipt me-1"></i> Case Invoice
                </a>
                <a href="javascript:history.back()" class="btn btn-label-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Back
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
                    <label class="fw-medium text-muted">New Vehicle No.</label>
                    <p class="mb-0 fw-semibold">{{ $case->new_vehicle_no ?? '—' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Customer Name</label>
                    <p class="mb-0 fw-semibold">{{ $case->party_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Customer Mobile</label>
                    <p class="mb-0 fw-semibold">{{ $case->party_mobile ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Vendor Name</label>
                    <p class="mb-0 fw-semibold">{{ $case->vendor_name ?? '—' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="fw-medium text-muted">Vendor Mobile</label>
                    <p class="mb-0 fw-semibold">{{ $case->vendor_mobile ?? '—' }}</p>
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
                    <p class="mb-0 fw-semibold">{{ $case->case_date ? \Carbon\Carbon::parse($case->case_date)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-medium text-muted">Comment</label>
                    <p class="mb-0 fw-semibold">{{ $case->comment ?? 'No comments' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-medium text-muted">Submitted At</label>
                    <p class="mb-0 fw-semibold">{{ $case->submitted_at ? \Carbon\Carbon::parse($case->submitted_at)->format('d/m/Y h:i A') : 'N/A' }}</p>
                </div>
            </div>

            {{-- Case Services & Amount --}}
            <div class="row mb-5">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="ti ti-shopping-cart me-2"></i> Services for This Case
                    </h6>
                    @if($caseItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($caseItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d/m/Y') : '—' }}</td>
                                    <td class="text-end">{{ number_format($item->item_amount, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-active fw-bold">
                                    <td colspan="3" class="text-end">Case Total:</td>
                                    <td class="text-end">Rs. {{ number_format($caseItems->sum('item_amount'), 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-secondary mb-0">
                        <i class="ti ti-info-circle me-1"></i> No billing items recorded for this case.
                    </p>
                    @endif
                </div>
            </div>

            {{-- Work Details Section --}}
            @php
                $allServices   = ['Transfer', 'Alteration', 'Route Permit', 'FC', 'Insurance', 'Tax', 'File Return', 'Others'];
                $addedServices = [];
                if ($case->transfer)   $addedServices[] = 'Transfer';
                if ($case->alteration) $addedServices[] = 'Alteration';
                if ($case->permit)     $addedServices[] = 'Route Permit';
                if ($case->fitness)    $addedServices[] = 'FC';
                if ($case->insurance)  $addedServices[] = 'Insurance';
                if ($case->tax)        $addedServices[] = 'Tax';
                if ($case->fileReturn) $addedServices[] = 'File Return';
                if ($case->other)      $addedServices[] = 'Others';
                $availableServices = array_values(array_diff($allServices, $addedServices));
            @endphp
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
                <h6 class="text-primary mb-0">
                    <i class="ti ti-list-details me-2"></i> Work Details
                </h6>
                @can('update case')
                    @if($customerBilling && count($availableServices) > 0)
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                            <i class="ti ti-plus me-1"></i> Add More Service
                        </button>
                    @endif
                @endcan
            </div>

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
                <div class="col-md-6">
                    <div class="card border-primary shadow-sm h-100">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-edit me-2"></i> Alteration Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <p><strong>Alteration Type:</strong>
                                <span class="badge bg-warning">{{ $case->alteration->alteration_type ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Route Permit Details --}}
                @if($case->permit)
                @php
                    $permitProvinces = $case->permit->province
                        ? array_values(array_filter(array_map('trim', explode(',', $case->permit->province))))
                        : [];
                    $provinceStatus  = $case->permit->province_status ?? [];
                @endphp
                <div class="col-12">
                    <div class="card border-primary shadow-sm">
                        <div class="card-header bg-label-primary">
                            <strong><i class="ti ti-map me-2"></i> Route Permit Details</strong>
                        </div>
                        <div class="card-body mt-3">
                            <p><strong>Type:</strong>
                                <span class="badge bg-info">{{ $case->permit->type ?? 'N/A' }}</span>
                            </p>
                            <p><strong>Details:</strong> {{ $case->permit->details ?? 'N/A' }}</p>
                            @if(count($permitProvinces) > 0)
                            <hr class="my-3">
                            <h6 class="text-primary mb-3"><i class="ti ti-map-pin me-1"></i> Province Status</h6>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($permitProvinces as $prov)
                                @php $pStatus = $provinceStatus[$prov] ?? 'incomplete'; @endphp
                                <div class="d-flex align-items-center gap-2 border rounded px-3 py-2">
                                    <span class="fw-semibold">{{ $prov }}</span>
                                    <span class="badge {{ $pStatus === 'complete' ? 'bg-label-success' : 'bg-label-danger' }}">
                                        {{ ucfirst($pStatus) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @endif
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

                {{-- If no work details added yet --}}
                @if(!$case->transfer && !$case->fileReturn && !$case->permit && !$case->fitness && !$case->tax && !$case->insurance && !$case->other)
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
                                                    {{ $activity->created_at->format('d/m/Y h:i A') }}
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

        {{-- Add More Service Modal --}}
        @can('update case')
            @if(isset($availableServices) && $customerBilling && count($availableServices) > 0)
            <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addServiceModalLabel">
                                <i class="ti ti-plus me-2"></i> Add More Service
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addServiceForm" action="{{ route('dashboard.cases.add-service', $case->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">

                                {{-- Row 1: Type / Date / Amount --}}
                                <div class="row g-3 mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                                        <select name="service_type" id="addSvcType" class="form-select" required>
                                            <option value="">— Select Service —</option>
                                            @foreach($availableServices as $svc)
                                                <option value="{{ $svc }}">{{ $svc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Service Date</label>
                                        <input type="text" name="service_date" id="addSvcDate"
                                               class="form-control" placeholder="DD/MM/YYYY" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Amount (Rs.) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" id="addSvcAmount"
                                               class="form-control" min="0" step="0.01"
                                               placeholder="e.g. 5000" required>
                                    </div>
                                </div>

                                {{-- Service-specific field groups --}}
                                {{-- Transfer & File Return: From/To fields --}}
                                <div id="svcGrpTransfer" class="svc-fields-group d-none mt-3 p-3 border rounded">
                                    <h6 class="text-primary mb-3"><i class="ti ti-users me-1"></i> From / To Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">From Name</label>
                                            <input type="text" name="from_name" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">From S/O</label>
                                            <input type="text" name="from_s_o" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">From NIC</label>
                                            <input type="text" name="from_nic" class="form-control" placeholder="XXXXX-XXXXXXX-X" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">To Name</label>
                                            <input type="text" name="to_name" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">To S/O</label>
                                            <input type="text" name="to_s_o" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">To NIC</label>
                                            <input type="text" name="to_nic" class="form-control" placeholder="XXXXX-XXXXXXX-X" disabled>
                                        </div>
                                    </div>
                                </div>

                                {{-- Alteration: Type only (no From/To) --}}
                                <div id="svcGrpAlteration" class="svc-fields-group d-none mt-3 p-3 border rounded">
                                    <h6 class="text-primary mb-3"><i class="ti ti-edit me-1"></i> Alteration Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Alteration Type</label>
                                            <select name="alteration_type" class="form-select" disabled>
                                                <option value="">— None —</option>
                                                <option value="Body">Body</option>
                                                <option value="Engine">Engine</option>
                                                <option value="Wheel">Wheel</option>
                                                <option value="Weight">Weight</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Route Permit --}}
                                <div id="svcGrpRoutePermit" class="svc-fields-group d-none mt-3 p-3 border rounded">
                                    <h6 class="text-primary mb-3"><i class="ti ti-map me-1"></i> Route Permit Details</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Route Details</label>
                                            <textarea name="route_details" class="form-control" rows="2" disabled></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label d-block mb-1">Province <span class="text-muted" style="font-size:0.8rem;">(select one or more)</span></label>
                                            <div class="d-flex flex-wrap gap-3">
                                                @foreach(['Sindh','Balochistan','Punjab','KPK'] as $prov)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="province[]" value="{{ $prov }}"
                                                               class="form-check-input" id="prov_{{ $prov }}" disabled>
                                                        <label class="form-check-label" for="prov_{{ $prov }}">{{ $prov }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">RTA / PTA</label>
                                            <select name="rta_pta" class="form-select" disabled>
                                                <option value="RTA">RTA</option>
                                                <option value="PTA">PTA</option>
                                                <option value="RTA to PTA">RTA to PTA</option>
                                                <option value="PTA to RTA">PTA to RTA</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- FC --}}
                                <div id="svcGrpFC" class="svc-fields-group d-none mt-3 p-3 border rounded">
                                    <h6 class="text-primary mb-3"><i class="ti ti-truck me-1"></i> FC Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Truck / Trailer</label>
                                            <select name="truck_type" class="form-select" disabled>
                                                <option value="Truck">Truck</option>
                                                <option value="Trailer">Trailer</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">FC Details</label>
                                            <textarea name="fc_details" class="form-control" rows="2" disabled></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Insurance --}}
                                <div id="svcGrpInsurance" class="svc-fields-group d-none mt-3 p-3 border rounded">
                                    <h6 class="text-primary mb-3"><i class="ti ti-shield me-1"></i> Insurance Details</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Remarks</label>
                                            <textarea name="remarks" class="form-control" rows="3" disabled></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tax --}}
                                <div id="svcGrpTax" class="svc-fields-group d-none mt-3 p-3 border rounded">
                                    <h6 class="text-primary mb-3"><i class="ti ti-calendar me-1"></i> Tax Details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">From (Period)</label>
                                            <input type="text" name="tax_from" id="addSvcTaxFrom"
                                                   class="form-control" placeholder="DD/MM/YYYY" autocomplete="off" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upto (Period)</label>
                                            <input type="text" name="tax_to" id="addSvcTaxTo"
                                                   class="form-control" placeholder="DD/MM/YYYY" autocomplete="off" disabled>
                                        </div>
                                    </div>
                                </div>

                                {{-- Others --}}
                                <div id="svcGrpOthers" class="svc-fields-group d-none mt-3 p-3 border rounded">
                                    <h6 class="text-primary mb-3"><i class="ti ti-dots me-1"></i> Other Details</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">Details</label>
                                            <textarea name="other_details" class="form-control" rows="3"
                                                      placeholder="Enter extra details" disabled></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Missing vehicle fields — shown for Transfer / Alteration if values are currently empty --}}
                                @php
                                    $missingVehicleFields = array_filter([
                                        'new_vehicle_no' => empty($case->new_vehicle_no) ? 'New Vehicle No.' : null,
                                        'vehicle_make'   => empty($case->vehicle_make)   ? 'Vehicle Make'    : null,
                                        'vehicle_model'  => empty($case->vehicle_model)  ? 'Vehicle Model'   : null,
                                        'engine_no'      => empty($case->engine_no)      ? 'Engine No.'      : null,
                                        'chassis_no'     => empty($case->chassis_no)     ? 'Chassis No.'     : null,
                                    ]);
                                @endphp
                                @if(count($missingVehicleFields) > 0)
                                <div id="svcGrpVehicle" class="svc-fields-group d-none mt-3 p-3 border border-warning rounded" style="background:#fffbf0">
                                    <h6 class="text-warning mb-3"><i class="ti ti-truck me-1"></i> Missing Vehicle Details</h6>
                                    <div class="row g-3">
                                        @foreach($missingVehicleFields as $field => $label)
                                        <div class="col-md-4">
                                            <label class="form-label">{{ $label }}</label>
                                            <input type="text" name="{{ $field }}" class="form-control" disabled>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Bill info banner --}}
                                <div class="alert alert-info py-2 mt-3 mb-0">
                                    <small>
                                        <i class="ti ti-info-circle me-1"></i>
                                        Customer bill total: <strong>Rs. {{ number_format($customerBilling->total_amount, 2) }}</strong>.
                                        The new service amount will be added to the customer's bill. Existing payments are untouched.
                                    </small>
                                </div>

                            </div>{{-- /modal-body --}}
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i> Add Service
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endcan

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

@section('script')
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
(function () {
    const svcTypeEl  = document.getElementById('addSvcType');
    const modalEl    = document.getElementById('addServiceModal');
    if (!svcTypeEl || !modalEl) return;

    const GROUP_MAP = {
        'Transfer':     'svcGrpTransfer',
        'File Return':  null,
        'Alteration':   'svcGrpAlteration',
        'Route Permit': 'svcGrpRoutePermit',
        'FC':           'svcGrpFC',
        'Insurance':    'svcGrpInsurance',
        'Tax':          'svcGrpTax',
        'Others':       'svcGrpOthers',
    };

    let datepicker    = null;
    let taxFromPicker = null;
    let taxToPicker   = null;

    const TAX_FP_OPTS = { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', allowInput: false };

    // Show the matching field group, disable all others
    function showServiceFields(selected) {
        document.querySelectorAll('.svc-fields-group').forEach(function (grp) {
            grp.classList.add('d-none');
            grp.querySelectorAll('input, textarea, select').forEach(function (el) {
                el.disabled = true;
            });
        });

        if (!selected) return;

        // Show service-specific group
        const groupId = GROUP_MAP[selected];
        if (groupId) {
            const grp = document.getElementById(groupId);
            if (grp) {
                grp.classList.remove('d-none');
                grp.querySelectorAll('input, textarea, select').forEach(function (el) {
                    el.disabled = false;
                });
            }
        }

        // Also show missing vehicle fields for Transfer and Alteration
        if (selected === 'Transfer' || selected === 'Alteration') {
            const vehicleGrp = document.getElementById('svcGrpVehicle');
            if (vehicleGrp) {
                vehicleGrp.classList.remove('d-none');
                vehicleGrp.querySelectorAll('input, textarea, select').forEach(function (el) {
                    el.disabled = false;
                });
            }
        }
    }

    svcTypeEl.addEventListener('change', function () {
        showServiceFields(this.value);
    });

    function destroyAllPickers() {
        if (datepicker)    { datepicker.destroy();    datepicker    = null; }
        if (taxFromPicker) { taxFromPicker.destroy(); taxFromPicker = null; }
        if (taxToPicker)   { taxToPicker.destroy();   taxToPicker   = null; }
    }

    // Init Flatpickr on modal open; destroy on close
    modalEl.addEventListener('shown.bs.modal', function () {
        destroyAllPickers();
        datepicker    = flatpickr('#addSvcDate',   { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', allowInput: false });
        taxFromPicker = flatpickr('#addSvcTaxFrom', TAX_FP_OPTS);
        taxToPicker   = flatpickr('#addSvcTaxTo',   TAX_FP_OPTS);
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('addServiceForm');
        if (form) form.reset();
        showServiceFields('');
        svcTypeEl.value = '';
        destroyAllPickers();
    });
})();
</script>
@endsection
