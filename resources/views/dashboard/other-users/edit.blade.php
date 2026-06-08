@extends('layouts.master')

@section('title', __('Edit User'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.other-users.index') }}">{{ __('Users') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.other-users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>{{ __('Edit User') }}</h3>
                        <div class="mb-4 col-md-6">
                            <label for="name" class="form-label">{{ __('User Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                name="name" required placeholder="{{ __('Enter user name') }}" autofocus
                                value="{{ old('name', $user->name) }}" />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="father_name" class="form-label">{{ __('Father Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('father_name') is-invalid @enderror" type="text" id="father_name"
                                name="father_name" required placeholder="{{ __('Enter father name') }}"
                                value="{{ old('father_name', $user->father_name) }}" />
                            @error('father_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="email" class="form-label">{{ __('Email (optional)') }}</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" id="email"
                                name="email" placeholder="{{ __('Enter email') }}" value="{{ old('email', $user->email) }}" />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="phone" class="form-label">{{ __('Phone (optional)') }}</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text" id="phone"
                                name="phone" placeholder="{{ __('Enter phone') }}" value="{{ old('phone', $user->phone) }}" />
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="cnic" class="form-label">{{ __('CNIC (optional)') }}</label>
                            <input class="form-control @error('cnic') is-invalid @enderror" type="text" id="cnic"
                                name="cnic" placeholder="{{ __('00000-0000000-0') }}" value="{{ old('cnic', $user->cnic) }}" />
                            @error('cnic')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="company" class="form-label">{{ __('Company (optional)') }}</label>
                            <input class="form-control @error('company') is-invalid @enderror" type="text" id="company"
                                name="company" placeholder="{{ __('Enter company') }}" value="{{ old('company', $user->company) }}" />
                            @error('company')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="country" class="form-label">{{ __('Country (optional)') }}</label>
                            <input class="form-control @error('country') is-invalid @enderror" type="text" id="country"
                                name="country" placeholder="{{ __('Enter country') }}" value="{{ old('country', $user->country) }}" />
                            @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="address" class="form-label">{{ __('Address (optional)') }}</label>
                            <input class="form-control @error('address') is-invalid @enderror" type="text" id="address"
                                name="address" placeholder="{{ __('Enter address') }}" value="{{ old('address', $user->address) }}" />
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Update User') }}</button>
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
