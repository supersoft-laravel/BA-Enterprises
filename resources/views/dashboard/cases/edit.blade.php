@extends('layouts.master')
@section('title', 'Edit Case #' . $case->id)

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}">
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.cases.index') }}">Cases</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard.cases.show', $case->id) }}">Case #{{ $case->id }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">

        <div class="card-header bg-label-primary d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 text-primary">
                    <i class="ti ti-edit me-2"></i> Edit Case
                </h5>
                <small class="text-muted">
                    {{ $case->vehicle_no ?? 'No Reg. No.' }} &bull; {{ $case->party_name ?? 'No Party' }}
                </small>
            </div>
            <a href="{{ route('dashboard.cases.show', $case->id) }}" class="btn btn-label-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Case
            </a>
        </div>

        <div class="card-body mt-4">

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="ti ti-alert-circle me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('dashboard.cases.update', $case->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ── VEHICLE INFORMATION ── --}}
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="ti ti-truck me-2"></i> Vehicle Information
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Vehicle No.</label>
                            <input type="text" name="vehicle_no" class="form-control"
                                   value="{{ old('vehicle_no', $case->vehicle_no) }}"
                                   placeholder="e.g. KHI-1234">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">New Vehicle No.</label>
                            <input type="text" name="new_vehicle_no" class="form-control"
                                   value="{{ old('new_vehicle_no', $case->new_vehicle_no) }}"
                                   placeholder="e.g. KHI-5678">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <select name="city" class="form-select">
                                <option value="">— Select City —</option>
                                @foreach(['Karachi','Lasbella','Quetta','Peshawar','Gilgit','Punjab','Other'] as $city)
                                    <option value="{{ $city }}"
                                        {{ old('city', $case->city) === $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Vehicle Make</label>
                            <input type="text" name="vehicle_make" class="form-control"
                                   value="{{ old('vehicle_make', $case->vehicle_make) }}"
                                   placeholder="e.g. Suzuki">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Vehicle Model</label>
                            <input type="text" name="vehicle_model" class="form-control"
                                   value="{{ old('vehicle_model', $case->vehicle_model) }}"
                                   placeholder="e.g. 2024">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Engine No.</label>
                            <input type="text" name="engine_no" class="form-control"
                                   value="{{ old('engine_no', $case->engine_no) }}"
                                   placeholder="e.g. EN-6545">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Chassis No.</label>
                            <input type="text" name="chassis_no" class="form-control"
                                   value="{{ old('chassis_no', $case->chassis_no) }}"
                                   placeholder="e.g. CH-12415">
                        </div>
                    </div>
                </div>

                {{-- ── CUSTOMER / VENDOR INFORMATION ── --}}
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="ti ti-user me-2"></i> Customer / Vendor Information
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="party_name" class="form-control"
                                   value="{{ old('party_name', $case->party_name) }}"
                                   placeholder="Full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer Mobile</label>
                            <input type="tel" name="party_mobile" class="form-control"
                                   value="{{ old('party_mobile', $case->party_mobile) }}"
                                   placeholder="e.g. 03001234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vendor Name</label>
                            <input type="text" name="vendor_name" class="form-control"
                                   value="{{ old('vendor_name', $case->vendor_name) }}"
                                   placeholder="Vendor / agent name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vendor Mobile</label>
                            <input type="tel" name="vendor_mobile" class="form-control"
                                   value="{{ old('vendor_mobile', $case->vendor_mobile) }}"
                                   placeholder="e.g. 03001234567">
                        </div>
                    </div>
                </div>

                {{-- ── CASE INFORMATION ── --}}
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="ti ti-file-description me-2"></i> Case Information
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Case Date</label>
                            <input type="text" id="edit_case_date" name="case_date" class="form-control"
                                   placeholder="DD/MM/YYYY"
                                   data-default="{{ old('case_date', $case->case_date ? \Carbon\Carbon::parse($case->case_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="open"   {{ old('status', $case->status) === 'open'   ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status', $case->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Comment / Remarks</label>
                            <textarea name="comment" class="form-control" rows="3"
                                      placeholder="Any additional remarks…">{{ old('comment', $case->comment) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ── ACTIONS ── --}}
                <div class="pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('dashboard.cases.show', $case->id) }}" class="btn btn-label-secondary">
                        <i class="ti ti-x me-1"></i> Cancel
                    </a>
                </div>

            </form>

            {{-- ── WORK DETAILS (EDITABLE) ── --}}
            @php
                $hasAnyWork = $case->transfer || $case->alteration || $case->tax || $case->insurance || $case->permit || $case->fitness || $case->fileReturn || $case->other;
            @endphp
            @if($hasAnyWork)
            <div class="mt-5">
                <h6 class="text-primary border-bottom pb-2 mb-4">
                    <i class="ti ti-list-details me-2"></i> Work Details
                </h6>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible mb-4" role="alert">
                        <i class="ti ti-check me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row g-4">

                    {{-- ── TRANSFER ── --}}
                    @if($case->transfer)
                    <div class="col-12">
                        <div class="card border-primary shadow-sm">
                            <div class="card-header bg-label-primary d-flex justify-content-between align-items-center">
                                <strong><i class="ti ti-exchange me-2"></i> Transfer Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'transfer']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">From</h6>
                                            <div class="mb-2">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="from_name" class="form-control" value="{{ old('from_name', $case->transfer->from_name) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">S/O</label>
                                                <input type="text" name="from_s_o" class="form-control" value="{{ old('from_s_o', $case->transfer->from_s_o) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">NIC</label>
                                                <input type="text" name="from_nic" class="form-control" value="{{ old('from_nic', $case->transfer->from_nic) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">To</h6>
                                            <div class="mb-2">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="to_name" class="form-control" value="{{ old('to_name', $case->transfer->to_name) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">S/O</label>
                                                <input type="text" name="to_s_o" class="form-control" value="{{ old('to_s_o', $case->transfer->to_s_o) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">NIC</label>
                                                <input type="text" name="to_nic" class="form-control" value="{{ old('to_nic', $case->transfer->to_nic) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="ti ti-device-floppy me-1"></i> Save Transfer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── ALTERATION ── --}}
                    @if($case->alteration)
                    <div class="col-md-6">
                        <div class="card border-primary shadow-sm h-100">
                            <div class="card-header bg-label-primary">
                                <strong><i class="ti ti-edit me-2"></i> Alteration Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'alteration']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Alteration Type</label>
                                        <select name="alteration_type" class="form-select">
                                            <option value="">— Select —</option>
                                            @foreach(['Body','Engine','Wheel','Weight'] as $opt)
                                                <option value="{{ $opt }}" {{ $case->alteration->alteration_type === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-device-floppy me-1"></i> Save Alteration
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── TAX ── --}}
                    @if($case->tax)
                    <div class="col-md-6">
                        <div class="card border-primary shadow-sm h-100">
                            <div class="card-header bg-label-primary">
                                <strong><i class="ti ti-calendar me-2"></i> Tax Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'tax']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">From Period</label>
                                            <input type="text" id="edit_tax_from" name="tax_from" class="form-control"
                                                   placeholder="DD/MM/YYYY"
                                                   data-default="{{ old('tax_from', $case->tax->tax_from ? \Carbon\Carbon::parse($case->tax->tax_from)->format('Y-m-d') : '') }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Upto</label>
                                            <input type="text" id="edit_tax_to" name="tax_to" class="form-control"
                                                   placeholder="DD/MM/YYYY"
                                                   data-default="{{ old('tax_to', $case->tax->tax_to ? \Carbon\Carbon::parse($case->tax->tax_to)->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="ti ti-device-floppy me-1"></i> Save Tax
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── INSURANCE ── --}}
                    @if($case->insurance)
                    <div class="col-md-6">
                        <div class="card border-primary shadow-sm h-100">
                            <div class="card-header bg-label-primary">
                                <strong><i class="ti ti-shield me-2"></i> Insurance Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'insurance']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Details</label>
                                        <textarea name="details" class="form-control" rows="3">{{ old('details', $case->insurance->details) }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-device-floppy me-1"></i> Save Insurance
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── ROUTE PERMIT ── --}}
                    @if($case->permit)
                    @php
                        $permitProvinces = $case->permit->province
                            ? array_values(array_filter(array_map('trim', explode(',', $case->permit->province))))
                            : [];
                        $provinceStatus  = $case->permit->province_status ?? [];
                        $allProvinces    = ['Sindh', 'Balochistan', 'Punjab', 'KPK'];
                    @endphp
                    <div class="col-12">
                        <div class="card border-primary shadow-sm">
                            <div class="card-header bg-label-primary">
                                <strong><i class="ti ti-map me-2"></i> Route Permit Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'permit']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">RTA / PTA</label>
                                            <select name="type" class="form-select">
                                                @foreach(['RTA','PTA','PTA to RTA','Other'] as $rta)
                                                    <option value="{{ $rta }}" {{ $case->permit->type === $rta ? 'selected' : '' }}>{{ $rta }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label d-block mb-2">Province <span class="text-muted" style="font-size:0.8rem;">(select one or more)</span></label>
                                            <div class="d-flex flex-wrap gap-3">
                                                @foreach($allProvinces as $prov)
                                                <div class="form-check">
                                                    <input type="checkbox" name="province[]" value="{{ $prov }}"
                                                           class="form-check-input" id="editProv_{{ $prov }}"
                                                           {{ in_array($prov, $permitProvinces) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="editProv_{{ $prov }}">{{ $prov }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Route Details</label>
                                            <textarea name="details" class="form-control" rows="2">{{ old('details', $case->permit->details) }}</textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-device-floppy me-1"></i> Save Route Permit
                                    </button>
                                </form>

                                {{-- Province Status Toggles (separate from form, AJAX) --}}
                                @if(count($permitProvinces) > 0)
                                <hr class="my-4">
                                <h6 class="text-primary mb-3"><i class="ti ti-map-pin me-1"></i> Province Status</h6>
                                <div class="d-flex flex-wrap gap-3" id="provinceStatusList">
                                    @foreach($permitProvinces as $prov)
                                    @php $pStatus = $provinceStatus[$prov] ?? 'incomplete'; @endphp
                                    <div class="d-flex align-items-center gap-2 border rounded px-3 py-2 province-row" data-province="{{ $prov }}">
                                        <span class="fw-semibold">{{ $prov }}</span>
                                        <span class="badge province-badge {{ $pStatus === 'complete' ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ ucfirst($pStatus) }}
                                        </span>
                                        @can('update case')
                                        <button type="button"
                                            class="btn btn-sm province-toggle-btn {{ $pStatus === 'complete' ? 'btn-label-warning' : 'btn-label-success' }}"
                                            data-province="{{ $prov }}"
                                            data-current="{{ $pStatus }}"
                                            data-url="{{ route('dashboard.cases.permit-province-status', $case->id) }}"
                                            style="font-size:0.75rem; padding: 2px 10px;">
                                            {{ $pStatus === 'complete' ? 'Mark Incomplete' : 'Mark Complete' }}
                                        </button>
                                        @endcan
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── FITNESS (FC) ── --}}
                    @if($case->fitness)
                    <div class="col-md-6">
                        <div class="card border-primary shadow-sm h-100">
                            <div class="card-header bg-label-primary">
                                <strong><i class="ti ti-health me-2"></i> Fitness Certificate (FC) Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'fitness']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Truck / Trailer</label>
                                        <select name="type" class="form-select">
                                            <option value="Truck"   {{ $case->fitness->type === 'Truck'   ? 'selected' : '' }}>Truck</option>
                                            <option value="Trailer" {{ $case->fitness->type === 'Trailer' ? 'selected' : '' }}>Trailer</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Details</label>
                                        <textarea name="details" class="form-control" rows="2">{{ old('details', $case->fitness->details) }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-device-floppy me-1"></i> Save FC
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── FILE RETURN ── --}}
                    @if($case->fileReturn)
                    <div class="col-md-6">
                        <div class="card border-primary shadow-sm h-100">
                            <div class="card-header bg-label-primary">
                                <strong><i class="ti ti-file-return me-2"></i> File Return Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                @if(!$case->transfer)
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'file-return']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">From</h6>
                                            <div class="mb-2">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="from_name" class="form-control" value="{{ old('from_name', $case->fileReturn->from_name) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">S/O</label>
                                                <input type="text" name="from_s_o" class="form-control" value="{{ old('from_s_o', $case->fileReturn->from_s_o) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">NIC</label>
                                                <input type="text" name="from_nic" class="form-control" value="{{ old('from_nic', $case->fileReturn->from_nic) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3">To</h6>
                                            <div class="mb-2">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="to_name" class="form-control" value="{{ old('to_name', $case->fileReturn->to_name) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">S/O</label>
                                                <input type="text" name="to_s_o" class="form-control" value="{{ old('to_s_o', $case->fileReturn->to_s_o) }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">NIC</label>
                                                <input type="text" name="to_nic" class="form-control" value="{{ old('to_nic', $case->fileReturn->to_nic) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="ti ti-device-floppy me-1"></i> Save File Return
                                        </button>
                                    </div>
                                </form>
                                @else
                                <p class="text-muted mb-0">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Party details are captured in the Transfer section.
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── OTHERS ── --}}
                    @if($case->other)
                    <div class="col-12">
                        <div class="card border-primary shadow-sm">
                            <div class="card-header bg-label-primary">
                                <strong><i class="ti ti-dots me-2"></i> Other Details</strong>
                            </div>
                            <div class="card-body mt-2">
                                <form action="{{ route('dashboard.cases.update-service', [$case->id, 'other']) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Details</label>
                                        <textarea name="details" class="form-control" rows="3">{{ old('details', $case->other->details) }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-device-floppy me-1"></i> Save Others
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
const FP_OPTS = { dateFormat: 'Y-m-d', altInput: true, altFormat: 'd/m/Y', allowInput: false, disableMobile: false };

(function () {
    const el = document.getElementById('edit_case_date');
    if (!el) return;
    flatpickr(el, { ...FP_OPTS, defaultDate: el.dataset.default || null });
})();

(function () {
    const tf = document.getElementById('edit_tax_from');
    const tt = document.getElementById('edit_tax_to');
    if (tf) flatpickr(tf, { ...FP_OPTS, defaultDate: tf.dataset.default || null });
    if (tt) flatpickr(tt, { ...FP_OPTS, defaultDate: tt.dataset.default || null });
})();

// Province status toggle
document.querySelectorAll('.province-toggle-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const province = this.dataset.province;
        const current  = this.dataset.current;
        const url      = this.dataset.url;
        const newStatus = current === 'complete' ? 'incomplete' : 'complete';
        const row       = this.closest('.province-row');
        const badge     = row.querySelector('.province-badge');

        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ province: province, status: newStatus }),
        })
        .then(function(res) {
            if (!res.ok) throw new Error('Server error');
            // Update badge
            badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            badge.className = 'badge province-badge ' + (newStatus === 'complete' ? 'bg-label-success' : 'bg-label-danger');
            // Update button
            btn.textContent = newStatus === 'complete' ? 'Mark Incomplete' : 'Mark Complete';
            btn.className   = 'btn btn-xs btn-sm province-toggle-btn ' + (newStatus === 'complete' ? 'btn-label-warning' : 'btn-label-success');
            btn.dataset.current = newStatus;
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update province status.', confirmButtonText: 'OK' });
        })
        .finally(function() {
            btn.disabled = false;
        });
    });
});
</script>
@endsection
