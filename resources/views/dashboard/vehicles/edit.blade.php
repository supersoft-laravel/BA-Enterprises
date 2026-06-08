@extends('layouts.master')

@section('title', __('Edit Vehicle'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.vehicles.index') }}">{{ __('Vehicles') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.vehicles.update', $vehicle->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>{{ __('Edit Vehicle') }}</h3>
                        <div class="mb-4 col-md-6">
                            <label for="vehicle_name" class="form-label">{{ __('Vehicle Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('vehicle_name') is-invalid @enderror" type="text" id="vehicle_name"
                                name="vehicle_name" required placeholder="{{ __('Enter vehicle name') }}" autofocus
                                value="{{ old('vehicle_name', $vehicle->vehicle_name) }}" />
                            @error('vehicle_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="reg_no" class="form-label">{{ __('Regd. No.') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('reg_no') is-invalid @enderror" type="text" id="reg_no"
                                name="reg_no" required placeholder="{{ __('Enter registration no') }}"
                                value="{{ old('reg_no', $vehicle->reg_no) }}" />
                            @error('reg_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="make" class="form-label">{{ __('Make (optional)') }}</label>
                            <input class="form-control @error('make') is-invalid @enderror" type="text" id="make"
                                name="make" placeholder="{{ __('Enter make') }}" value="{{ old('make', $vehicle->make) }}" />
                            @error('make')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="model" class="form-label">{{ __('Model (optional)') }}</label>
                            <input class="form-control @error('model') is-invalid @enderror" type="text" id="model"
                                name="model" placeholder="{{ __('Enter model') }}" value="{{ old('model', $vehicle->model) }}" />
                            @error('model')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="year" class="form-label">{{ __('Year (optional)') }}</label>
                            <input class="form-control @error('year') is-invalid @enderror" type="text" id="year"
                                name="year" placeholder="{{ __('Enter year') }}" value="{{ old('year', $vehicle->year) }}" />
                            @error('year')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="chesis_number" class="form-label">{{ __('Chesis No (optional)') }}</label>
                            <input class="form-control @error('chesis_number') is-invalid @enderror" type="text" id="chesis_number"
                                name="chesis_number" placeholder="{{ __('Enter chesis number') }}" value="{{ old('chesis_number', $vehicle->chesis_number) }}" />
                            @error('chesis_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="engine_number" class="form-label">{{ __('Engine No (optional)') }}</label>
                            <input class="form-control @error('engine_number') is-invalid @enderror" type="text" id="engine_number"
                                name="engine_number" placeholder="{{ __('Enter engine number') }}" value="{{ old('engine_number', $vehicle->engine_number) }}" />
                            @error('engine_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="wheel" class="form-label">{{ __('Wheel (optional)') }}</label>
                            <input class="form-control @error('wheel') is-invalid @enderror" type="text" id="wheel"
                                name="wheel" placeholder="{{ __('Enter wheel') }}" value="{{ old('wheel', $vehicle->wheel) }}" />
                            @error('wheel')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="weight" class="form-label">{{ __('Weight (optional)') }}</label>
                            <input class="form-control @error('weight') is-invalid @enderror" type="text" id="weight"
                                name="weight" placeholder="{{ __('Enter weight') }}" value="{{ old('weight', $vehicle->weight) }}" />
                            @error('weight')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-4">
                            <label for="color" class="form-label">{{ __('Color (optional)') }}</label>
                            <input class="form-control @error('color') is-invalid @enderror" type="text" id="color"
                                name="color" placeholder="{{ __('Enter color') }}" value="{{ old('color', $vehicle->color) }}" />
                            @error('color')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="tax_history" class="form-label">{{ __('Tax History (optional)') }}</label>
                            <textarea class="form-control @error('tax_history') is-invalid @enderror" id="tax_history"
                                name="tax_history" placeholder="{{ __('Enter tax history...') }}">{{ old('tax_history', $vehicle->tax_history) }}</textarea>
                            @error('tax_history')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="penalties" class="form-label">{{ __('Penalties (optional)') }}</label>
                            <textarea class="form-control @error('penalties') is-invalid @enderror" id="penalties"
                                name="penalties" placeholder="{{ __('Enter penalties...') }}">{{ old('penalties', $vehicle->penalties) }}</textarea>
                            @error('penalties')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Update Vehicle') }}</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
