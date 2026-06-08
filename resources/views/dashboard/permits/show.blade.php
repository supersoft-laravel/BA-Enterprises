@extends('layouts.master')

@section('title', __('Permit Details'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.permits.index') }}">{{ __('Permits') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Permit Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ __('Permit Details') }}</h4>

                <a href="{{ route('dashboard.permits.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">

                <!-- BASIC INFO -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <strong>Type:</strong>
                        <span class="badge bg-label-primary">{{ ucfirst($permit->type) }}</span>
                    </div>

                    <div class="col-md-3">
                        <strong>Vehicle:</strong><br>
                        {{ $permit->vehicle->vehicle_name ?? '-' }}
                        <small class="text-muted d-block">
                            {{ $permit->vehicle->reg_no ?? '' }}
                        </small>
                    </div>

                    <div class="col-md-3">
                        <strong>Date:</strong><br>
                        {{ \Carbon\Carbon::parse($permit->permit_date)->format('d M Y') }}
                    </div>

                    <div class="col-md-3">
                        <strong>Created At:</strong><br>
                        {{ $permit->created_at->format('d M Y H:i') }}
                    </div>
                </div>

                <hr>

                <!-- FROM & TO USERS -->
                <div class="row">

                    <!-- FROM USER -->
                    <div class="col-md-12">
                        <div class="card border">
                            <div class="card-header bg-label-info">
                                <strong>Permit Holder</strong>
                            </div>
                            <div class="card-body mt-3">

                                <p><strong>Name:</strong> {{ $permit->permitHolder->name ?? '-' }}</p>
                                <p><strong>Father Name:</strong> {{ $permit->permitHolder->father_name ?? '-' }}</p>
                                <p><strong>Email:</strong> {{ $permit->permitHolder->email ?? '-' }}</p>
                                <p><strong>Phone:</strong> {{ $permit->permitHolder->phone ?? '-' }}</p>
                                <p><strong>CNIC:</strong> {{ $permit->permitHolder->cnic ?? '-' }}</p>
                                <p><strong>Company:</strong> {{ $permit->permitHolder->company ?? '-' }}</p>
                                <p><strong>Country:</strong> {{ $permit->permitHolder->country ?? '-' }}</p>
                                <p><strong>Address:</strong> {{ $permit->permitHolder->address ?? '-' }}</p>

                            </div>
                        </div>
                    </div>

                </div>

                <hr>

                <!-- NOTES -->
                <div class="row">
                    <div class="col-md-12">
                        <strong>Permit Details:</strong>
                        <div class="p-3 border rounded bg-light mt-2">
                            {{ $permit->permit_details ?? 'No details available' }}
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-12">
                        <strong>Notes:</strong>
                        <div class="p-3 border rounded bg-light mt-2">
                            {{ $permit->notes ?? 'No notes available' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('script')

@endsection
