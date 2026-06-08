@extends('layouts.master')

@section('title', __('Create Permit'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.permits.index') }}">{{ __('Permits') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.permits.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row p-5">
                        <h3>{{ __('Add New Permit') }}</h3>
                        <div class="mb-4 col-md-12">
                            <label for="type" class="form-label">{{ __('Permit Type') }}</label><span
                                class="text-danger">*</span>
                            <select class="form-control select2 @error('type') is-invalid @enderror" id="type"
                                name="type" required>
                                <option value="" selected disabled>{{ __('Select Permit Type') }}</option>
                                <option value="karachi" {{ old('type') == 'karachi' ? 'selected' : '' }}>
                                    {{ __('Karachi') }}
                                </option>
                                <option value="lasbella" {{ old('type') == 'lasbella' ? 'selected' : '' }}>
                                    {{ __('Lasbella') }}
                                </option>
                                <option value="peshawar" {{ old('type') == 'peshawar' ? 'selected' : '' }}>
                                    {{ __('Peshawar') }}
                                </option>
                                <option value="gilgit" {{ old('type') == 'gilgit' ? 'selected' : '' }}>
                                    {{ __('Gilgit') }}
                                </option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>
                                    {{ __('Other') }}
                                </option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="vehicle_id" class="form-label">{{ __('Vehicle') }}</label><span
                                class="text-danger">*</span>
                            <select class="form-control select2 @error('vehicle_id') is-invalid @enderror" id="vehicle_id"
                                name="vehicle_id" required>
                                <option value="" selected disabled>{{ __('Select Vehicle') }}</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}"
                                        {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->vehicle_name . ' - ' . $vehicle->reg_no }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="permit_date" class="form-label">{{ __('Permit Date') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('permit_date') is-invalid @enderror" type="date"
                                id="permit_date" name="permit_date" required
                                placeholder="{{ __('Enter permit date') }}" value="{{ old('permit_date') }}" />
                            @error('permit_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="permit_details" class="form-label">{{ __('Permit Details (optional)') }}</label>
                            <textarea class="form-control @error('permit_details') is-invalid @enderror" id="permit_details" name="permit_details"
                                placeholder="{{ __('Enter permit details...') }}">{{ old('permit_details') }}</textarea>
                            @error('permit_details')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="notes" class="form-label">{{ __('Notes (optional)') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                placeholder="{{ __('Enter notes...') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <h6>{{ __('Permit Holder Details') }}</h6>
                        <hr>

                        <div class="mb-3 col-md-12">
                            <input type="checkbox" id="from_new_user">
                            <label for="from_new_user">Add New User</label>
                        </div>

                        <div id="from_user_select" class="mb-4 col-md-12">
                            <select class="form-control select2" name="permit_holder_id">
                                <option value="" selected disabled>Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('permit_holder_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="from_new_user_fields" class="col-md-12" style="display:none;">
                            <div class="row p-5">
                                <div class="mb-4 col-md-6">
                                    <label for="holder_name" class="form-label">{{ __('Name') }}</label><span
                                        class="text-danger">*</span>
                                    <input class="form-control @error('holder_name') is-invalid @enderror" type="text" id="holder_name"
                                        name="holder_name" placeholder="{{ __('Enter name') }}"
                                        value="{{ old('holder_name') }}" />
                                    @error('holder_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="holder_father_name" class="form-label">{{ __('Father Name') }}</label><span
                                        class="text-danger">*</span>
                                    <input class="form-control @error('holder_father_name') is-invalid @enderror" type="text" id="holder_father_name"
                                        name="holder_father_name" placeholder="{{ __('Enter father name') }}"
                                        value="{{ old('holder_father_name') }}" />
                                    @error('holder_father_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="holder_email" class="form-label">{{ __('Email (optional)') }}</label>
                                    <input class="form-control @error('holder_email') is-invalid @enderror" type="email" id="holder_email"
                                        name="holder_email" placeholder="{{ __('Enter email') }}" value="{{ old('holder_email') }}" />
                                    @error('holder_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="holder_phone" class="form-label">{{ __('Phone (optional)') }}</label>
                                    <input class="form-control @error('holder_phone') is-invalid @enderror" type="text" id="holder_phone"
                                        name="holder_phone" placeholder="{{ __('Enter phone') }}" value="{{ old('holder_phone') }}" />
                                    @error('holder_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="holder_cnic" class="form-label">{{ __('CNIC (optional)') }}</label>
                                    <input class="form-control @error('holder_cnic') is-invalid @enderror" type="text" id="holder_cnic"
                                        name="holder_cnic" placeholder="{{ __('00000-0000000-0') }}" value="{{ old('holder_cnic') }}" />
                                    @error('holder_cnic')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="holder_company" class="form-label">{{ __('Company (optional)') }}</label>
                                    <input class="form-control @error('holder_company') is-invalid @enderror" type="text" id="holder_company"
                                        name="holder_company" placeholder="{{ __('Enter company') }}" value="{{ old('holder_company') }}" />
                                    @error('holder_company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="holder_country" class="form-label">{{ __('Country (optional)') }}</label>
                                    <input class="form-control @error('holder_country') is-invalid @enderror" type="text" id="holder_country"
                                        name="holder_country" placeholder="{{ __('Enter country') }}" value="{{ old('holder_country') }}" />
                                    @error('holder_country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-12">
                                    <label for="holder_address" class="form-label">{{ __('Address (optional)') }}</label>
                                    <input class="form-control @error('holder_address') is-invalid @enderror" type="text" id="holder_address"
                                        name="holder_address" placeholder="{{ __('Enter address') }}" value="{{ old('holder_address') }}" />
                                    @error('holder_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Add Permit') }}</button>
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

            $('#from_new_user').change(function() {
                if ($(this).is(':checked')) {
                    $('#from_user_select').hide();
                    $('#from_new_user_fields').show();
                } else {
                    $('#from_user_select').show();
                    $('#from_new_user_fields').hide();
                }
            });

        });
    </script>
@endsection
