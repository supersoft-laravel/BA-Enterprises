@extends('layouts.master')

@section('title', __('Alteration Details'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.alterations.index') }}">{{ __('Alterations') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Alteration Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ __('Alteration Details') }}</h4>

                <a href="{{ route('dashboard.alterations.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">

                <!-- BASIC INFO -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <strong>Type:</strong>
                        <span class="badge bg-label-primary">{{ ucfirst($alteration->type) }}</span>
                    </div>

                    <div class="col-md-3">
                        <strong>Vehicle:</strong><br>
                        {{ $alteration->vehicle->vehicle_name ?? '-' }}
                        <small class="text-muted d-block">
                            {{ $alteration->vehicle->reg_no ?? '' }}
                        </small>
                    </div>

                    <div class="col-md-3">
                        <strong>Date:</strong><br>
                        {{ \Carbon\Carbon::parse($alteration->alteration_date)->format('d M Y') }}
                    </div>

                    <div class="col-md-3">
                        <strong>Created At:</strong><br>
                        {{ $alteration->created_at->format('d M Y H:i') }}
                    </div>
                </div>

                <hr>

                <!-- FROM & TO USERS -->
                <div class="row">

                    <!-- FROM USER -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-label-info">
                                <strong>From User</strong>
                            </div>
                            <div class="card-body mt-3">

                                <p><strong>Name:</strong> {{ $alteration->fromUser->name ?? '-' }}</p>
                                <p><strong>Father Name:</strong> {{ $alteration->fromUser->father_name ?? '-' }}</p>
                                <p><strong>Email:</strong> {{ $alteration->fromUser->email ?? '-' }}</p>
                                <p><strong>Phone:</strong> {{ $alteration->fromUser->phone ?? '-' }}</p>
                                <p><strong>CNIC:</strong> {{ $alteration->fromUser->cnic ?? '-' }}</p>
                                <p><strong>Company:</strong> {{ $alteration->fromUser->company ?? '-' }}</p>
                                <p><strong>Country:</strong> {{ $alteration->fromUser->country ?? '-' }}</p>
                                <p><strong>Address:</strong> {{ $alteration->fromUser->address ?? '-' }}</p>

                            </div>
                        </div>
                    </div>

                    <!-- TO USER -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-label-success">
                                <strong>To User</strong>
                            </div>
                            <div class="card-body mt-3">

                                <p><strong>Name:</strong> {{ $alteration->toUser->name ?? '-' }}</p>
                                <p><strong>Father Name:</strong> {{ $alteration->toUser->father_name ?? '-' }}</p>
                                <p><strong>Email:</strong> {{ $alteration->toUser->email ?? '-' }}</p>
                                <p><strong>Phone:</strong> {{ $alteration->toUser->phone ?? '-' }}</p>
                                <p><strong>CNIC:</strong> {{ $alteration->toUser->cnic ?? '-' }}</p>
                                <p><strong>Company:</strong> {{ $alteration->toUser->company ?? '-' }}</p>
                                <p><strong>Country:</strong> {{ $alteration->toUser->country ?? '-' }}</p>
                                <p><strong>Address:</strong> {{ $alteration->toUser->address ?? '-' }}</p>

                            </div>
                        </div>
                    </div>

                </div>

                <hr>

                <!-- NOTES -->
                <div class="row">
                    <div class="col-md-12">
                        <strong>Alteration Details:</strong>
                        <div class="p-3 border rounded bg-light mt-2">
                            {{ $alteration->alteration_details ?? 'No details available' }}
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-12">
                        <strong>Notes:</strong>
                        <div class="p-3 border rounded bg-light mt-2">
                            {{ $alteration->notes ?? 'No notes available' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('script')

@endsection
