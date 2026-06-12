@extends('layouts.master')

@section('title', __('Create Customer'))

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.customers.index') }}">{{ __('Customers') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header bg-label-primary border-bottom py-3">
                <h5 class="mb-0 text-primary fw-semibold">
                    <i class="ti ti-user-plus me-2"></i>Add New Customer
                </h5>
                <p class="mb-0 text-muted small">Customer code is auto-generated on save.</p>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('dashboard.customers.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Full Name"
                                required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="mobile"
                                class="form-control @error('mobile') is-invalid @enderror"
                                value="{{ old('mobile') }}"
                                placeholder="03XX-XXXXXXX"
                                required>
                            @error('mobile')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            <i class="ti ti-check me-1"></i>Save Customer
                        </button>
                        <a href="{{ route('dashboard.customers.index') }}"
                            class="btn btn-label-secondary waves-effect waves-light">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
