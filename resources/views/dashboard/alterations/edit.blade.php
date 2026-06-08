@extends('layouts.master')

@section('title', __('Edit Alteration'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.alterations.index') }}">{{ __('Alterations') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.alterations.update', $alteration->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>{{ __('Edit Alteration') }}</h3>
                        <div class="mb-4 col-md-12">
                            <label for="type" class="form-label">{{ __('Alteration Type') }}</label><span
                                class="text-danger">*</span>
                            <select class="form-control select2 @error('type') is-invalid @enderror" id="type"
                                name="type" required>
                                <option value="" selected disabled>{{ __('Select Alteration Type') }}</option>
                                <option value="karachi" {{ old('type', $alteration->type) == 'karachi' ? 'selected' : '' }}>
                                    {{ __('Karachi') }}
                                </option>
                                <option value="lasbella" {{ old('type', $alteration->type) == 'lasbella' ? 'selected' : '' }}>
                                    {{ __('Lasbella') }}
                                </option>
                                <option value="peshawar" {{ old('type', $alteration->type) == 'peshawar' ? 'selected' : '' }}>
                                    {{ __('Peshawar') }}
                                </option>
                                <option value="gilgit" {{ old('type', $alteration->type) == 'gilgit' ? 'selected' : '' }}>
                                    {{ __('Gilgit') }}
                                </option>
                                <option value="other" {{ old('type', $alteration->type) == 'other' ? 'selected' : '' }}>
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
                                        {{ old('vehicle_id', $alteration->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
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
                            <label for="alteration_date" class="form-label">{{ __('Alteration Date') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('alteration_date') is-invalid @enderror" type="date"
                                id="alteration_date" name="alteration_date" required
                                placeholder="{{ __('Enter alteration date') }}" value="{{ old('alteration_date', $alteration->alteration_date) }}" />
                            @error('alteration_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="alteration_details" class="form-label">{{ __('Alteration Details (optional)') }}</label>
                            <textarea class="form-control @error('alteration_details') is-invalid @enderror" id="alteration_details" name="alteration_details"
                                placeholder="{{ __('Enter alteration details...') }}">{{ old('alteration_details', $alteration->alteration_details) }}</textarea>
                            @error('alteration_details')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="notes" class="form-label">{{ __('Notes (optional)') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                placeholder="{{ __('Enter notes...') }}">{{ old('notes', $alteration->notes) }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <h6>{{ __('From User Details') }}</h6>
                        <hr>

                        <div class="mb-3 col-md-12">
                            <input type="checkbox" id="from_new_user">
                            <label for="from_new_user">Add New User</label>
                        </div>

                        <div id="from_user_select" class="mb-4 col-md-12">
                            <select class="form-control select2" name="from_user_id">
                                <option value="" selected disabled>Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('from_user_id', $alteration->from_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="from_new_user_fields" class="col-md-12" style="display:none;">
                            <div class="row p-5">
                                <div class="mb-4 col-md-6">
                                    <label for="from_name" class="form-label">{{ __('Name') }}</label><span
                                        class="text-danger">*</span>
                                    <input class="form-control @error('from_name') is-invalid @enderror" type="text" id="from_name"
                                        name="from_name" placeholder="{{ __('Enter name') }}"
                                        value="{{ old('from_name') }}" />
                                    @error('from_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="from_father_name" class="form-label">{{ __('Father Name') }}</label><span
                                        class="text-danger">*</span>
                                    <input class="form-control @error('from_father_name') is-invalid @enderror" type="text" id="from_father_name"
                                        name="from_father_name" placeholder="{{ __('Enter father name') }}"
                                        value="{{ old('from_father_name') }}" />
                                    @error('from_father_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="from_email" class="form-label">{{ __('Email (optional)') }}</label>
                                    <input class="form-control @error('from_email') is-invalid @enderror" type="email" id="from_email"
                                        name="from_email" placeholder="{{ __('Enter email') }}" value="{{ old('from_email') }}" />
                                    @error('from_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="from_phone" class="form-label">{{ __('Phone (optional)') }}</label>
                                    <input class="form-control @error('from_phone') is-invalid @enderror" type="text" id="from_phone"
                                        name="from_phone" placeholder="{{ __('Enter phone') }}" value="{{ old('from_phone') }}" />
                                    @error('from_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="from_cnic" class="form-label">{{ __('CNIC (optional)') }}</label>
                                    <input class="form-control @error('from_cnic') is-invalid @enderror" type="text" id="from_cnic"
                                        name="from_cnic" placeholder="{{ __('00000-0000000-0') }}" value="{{ old('from_cnic') }}" />
                                    @error('from_cnic')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="from_company" class="form-label">{{ __('Company (optional)') }}</label>
                                    <input class="form-control @error('from_company') is-invalid @enderror" type="text" id="from_company"
                                        name="from_company" placeholder="{{ __('Enter company') }}" value="{{ old('from_company') }}" />
                                    @error('from_company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="from_country" class="form-label">{{ __('Country (optional)') }}</label>
                                    <input class="form-control @error('from_country') is-invalid @enderror" type="text" id="from_country"
                                        name="from_country" placeholder="{{ __('Enter country') }}" value="{{ old('from_country') }}" />
                                    @error('from_country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-12">
                                    <label for="from_address" class="form-label">{{ __('Address (optional)') }}</label>
                                    <input class="form-control @error('from_address') is-invalid @enderror" type="text" id="from_address"
                                        name="from_address" placeholder="{{ __('Enter address') }}" value="{{ old('from_address') }}" />
                                    @error('from_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h6>{{ __('To User Details') }}</h6>
                        <hr>

                        <div class="mb-3 col-md-12">
                            <input type="checkbox" id="to_new_user">
                            <label for="to_new_user">Add New User</label>
                        </div>

                        <div id="to_user_select" class="mb-4 col-md-12">
                            <select class="form-control select2" name="to_user_id">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('to_user_id', $alteration->to_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="to_new_user_fields" style="display:none;" class="col-md-12">
                            <div class="row p-5">
                                <div class="mb-4 col-md-6">
                                    <label for="to_name" class="form-label">{{ __('Name') }}</label><span
                                        class="text-danger">*</span>
                                    <input class="form-control @error('to_name') is-invalid @enderror" type="text" id="to_name"
                                        name="to_name" placeholder="{{ __('Enter name') }}"
                                        value="{{ old('to_name') }}" />
                                    @error('to_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="to_father_name" class="form-label">{{ __('Father Name') }}</label><span
                                        class="text-danger">*</span>
                                    <input class="form-control @error('to_father_name') is-invalid @enderror" type="text" id="to_father_name"
                                        name="to_father_name" placeholder="{{ __('Enter father name') }}"
                                        value="{{ old('to_father_name') }}" />
                                    @error('to_father_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="to_email" class="form-label">{{ __('Email (optional)') }}</label>
                                    <input class="form-control @error('to_email') is-invalid @enderror" type="email" id="to_email"
                                        name="to_email" placeholder="{{ __('Enter email') }}" value="{{ old('to_email') }}" />
                                    @error('to_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="to_phone" class="form-label">{{ __('Phone (optional)') }}</label>
                                    <input class="form-control @error('to_phone') is-invalid @enderror" type="text" id="to_phone"
                                        name="to_phone" placeholder="{{ __('Enter phone') }}" value="{{ old('to_phone') }}" />
                                    @error('to_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="to_cnic" class="form-label">{{ __('CNIC (optional)') }}</label>
                                    <input class="form-control @error('to_cnic') is-invalid @enderror" type="text" id="to_cnic"
                                        name="to_cnic" placeholder="{{ __('00000-0000000-0') }}" value="{{ old('to_cnic') }}" />
                                    @error('to_cnic')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="to_company" class="form-label">{{ __('Company (optional)') }}</label>
                                    <input class="form-control @error('to_company') is-invalid @enderror" type="text" id="to_company"
                                        name="to_company" placeholder="{{ __('Enter company') }}" value="{{ old('to_company') }}" />
                                    @error('to_company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-6">
                                    <label for="to_country" class="form-label">{{ __('Country (optional)') }}</label>
                                    <input class="form-control @error('to_country') is-invalid @enderror" type="text" id="to_country"
                                        name="to_country" placeholder="{{ __('Enter country') }}" value="{{ old('to_country') }}" />
                                    @error('to_country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-4 col-md-12">
                                    <label for="to_address" class="form-label">{{ __('Address (optional)') }}</label>
                                    <input class="form-control @error('to_address') is-invalid @enderror" type="text" id="to_address"
                                        name="to_address" placeholder="{{ __('Enter address') }}" value="{{ old('to_address') }}" />
                                    @error('to_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Edit Alateration') }}</button>
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

            $('#to_new_user').change(function() {
                if ($(this).is(':checked')) {
                    $('#to_user_select').hide();
                    $('#to_new_user_fields').show();
                } else {
                    $('#to_user_select').show();
                    $('#to_new_user_fields').hide();
                }
            });

        });
    </script>
@endsection
