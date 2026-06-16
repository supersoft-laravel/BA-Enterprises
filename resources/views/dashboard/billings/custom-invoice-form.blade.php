@extends('layouts.master')

@section('title', 'Custom Invoice')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.billings.index') }}">Billings</a>
    </li>
    <li class="breadcrumb-item active">Custom Invoice</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-file-invoice me-2"></i>Generate Custom Invoice</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('dashboard.billings.custom-invoice.generate') }}" method="POST" target="_blank">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Customer</label>
                            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                <option value="">— Select Customer —</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_code }} — {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Show Last N Cases</label>
                            <select name="n_cases" class="form-select @error('n_cases') is-invalid @enderror" required>
                                <option value="">— Select —</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('n_cases') == $i ? 'selected' : '' }}>
                                        Last {{ $i }} {{ $i === 1 ? 'Case' : 'Cases' }}
                                    </option>
                                @endfor
                            </select>
                            @error('n_cases')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted mt-1">
                                Only the selected latest cases will appear as new items. The rest shows as "Old Balance".
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                <i class="ti ti-printer me-1"></i>Generate Invoice
                            </button>
                            <a href="{{ route('dashboard.billings.index') }}" class="btn btn-label-secondary waves-effect waves-light">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
