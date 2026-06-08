@extends('layouts.master')
@section('title', 'Edit Basic Details - Case #' . $case->case_no)

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.cases.index') }}">Cases</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard.cases.show', $case->id) }}">Case #{{ $case->case_no }}</a></li>
    <li class="breadcrumb-item active">Edit Basic</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header bg-label-primary">
            <h5 class="mb-0">
                <i class="ti ti-edit me-2"></i>
                Edit Basic Details - Case #{{ $case->case_no }}
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('dashboard.cases.update', $case->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Vehicle Info -->
                    <div class="col-md-4">
                        <label class="form-label">Vehicle Registration No. <span class="text-danger">*</span></label>
                        <input type="text" name="vehicle_reg_no" class="form-control"
                               value="{{ old('vehicle_reg_no', $case->vehicle_reg_no) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Make</label>
                        <input type="text" name="make" class="form-control"
                               value="{{ old('make', $case->make) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Year</label>
                        <input type="number" name="year" class="form-control"
                               value="{{ old('year', $case->year) }}" min="1900" max="2030">
                    </div>

                    <!-- Submitted By -->
                    <div class="col-md-6">
                        <label class="form-label">Submitted By <span class="text-danger">*</span></label>
                        <input type="text" name="submitted_by" class="form-control"
                               value="{{ old('submitted_by', $case->submitted_by) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                        <input type="tel" name="mobile_no" class="form-control"
                               value="{{ old('mobile_no', $case->mobile_no) }}" required>
                    </div>

                    <!-- Dates -->
                    <div class="col-md-6">
                        <label class="form-label">Submission Date <span class="text-danger">*</span></label>
                        <input type="date" name="submission_date" class="form-control"
                               value="{{ old('submission_date', \Carbon\Carbon::parse($case->submission_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tentative Return Date</label>
                        <input type="date" name="tentative_return_date" class="form-control"
                               value="{{ old('tentative_return_date', $case->tentative_return_date ? \Carbon\Carbon::parse($case->tentative_return_date)->format('Y-m-d') : null) }}">
                    </div>

                    <!-- Case Refer To -->
                    <div class="col-md-12">
                        <label class="form-label">Case Referred To <span class="text-danger">*</span></label>
                        <select name="case_refer_to" class="form-select" required>
                            <option value="Karachi" {{ $case->case_refer_to == 'Karachi' ? 'selected' : '' }}>Karachi</option>
                            <option value="Lasbella" {{ $case->case_refer_to == 'Lasbella' ? 'selected' : '' }}>Lasbella</option>
                            <option value="Quetta" {{ $case->case_refer_to == 'Quetta' ? 'selected' : '' }}>Quetta</option>
                            <option value="Peshawar" {{ $case->case_refer_to == 'Peshawar' ? 'selected' : '' }}>Peshawar</option>
                            <option value="Gilgit" {{ $case->case_refer_to == 'Gilgit' ? 'selected' : '' }}>Gilgit</option>
                            <option value="Punjab" {{ $case->case_refer_to == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            <option value="Other" {{ $case->case_refer_to == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top d-flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-2"></i> Update Basic Details
                    </button>

                    <a href="{{ route('dashboard.cases.show', $case->id) }}"
                       class="btn btn-label-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
