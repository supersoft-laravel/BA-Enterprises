@extends('layouts.master')

@section('title', __('Edit Transfer'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.transfers.index') }}">{{ __('Transfers') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.transfers.update', $transfer->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>Edit Transfer</h3>

                        {{-- FROM DETAILS --}}
                        <div class="col-md-4 mb-3">
                            <label>From Name *</label>
                            <input type="text" name="from_name" class="form-control"
                                value="{{ old('from_name', $transfer->from_name) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>From S/O *</label>
                            <input type="text" name="from_s_o" class="form-control"
                                value="{{ old('from_s_o', $transfer->from_s_o) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>From NIC *</label>
                            <input type="text" name="from_nic" class="form-control"
                                value="{{ old('from_nic', $transfer->from_nic) }}" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>From Biometric *</label>
                            <select name="from_biometric" class="form-control" required>
                                <option value="yes"
                                    {{ old('from_biometric', $transfer->from_biometric) == 'yes' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="no"
                                    {{ old('from_biometric', $transfer->from_biometric) == 'no' ? 'selected' : '' }}>No
                                </option>
                            </select>
                        </div>

                        {{-- TO DETAILS --}}
                        <div class="col-md-4 mb-3">
                            <label>To Name *</label>
                            <input type="text" name="to_name" class="form-control"
                                value="{{ old('to_name', $transfer->to_name) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>To S/O *</label>
                            <input type="text" name="to_s_o" class="form-control"
                                value="{{ old('to_s_o', $transfer->to_s_o) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>To NIC *</label>
                            <input type="text" name="to_nic" class="form-control"
                                value="{{ old('to_nic', $transfer->to_nic) }}" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>To Biometric *</label>
                            <select name="to_biometric" class="form-control" required>
                                <option value="yes"
                                    {{ old('to_biometric', $transfer->to_biometric) == 'yes' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="no"
                                    {{ old('to_biometric', $transfer->to_biometric) == 'no' ? 'selected' : '' }}>No
                                </option>
                            </select>
                        </div>

                        {{-- VEHICLE DETAILS --}}
                        <div class="col-md-4 mb-3">
                            <label>Engine No *</label>
                            <input type="text" name="engine_no" class="form-control"
                                value="{{ old('engine_no', $transfer->engine_no) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Chassis No *</label>
                            <input type="text" name="chassis_no" class="form-control"
                                value="{{ old('chassis_no', $transfer->chassis_no) }}" required>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Wheels</label>
                            <input type="text" name="wheels" class="form-control"
                                value="{{ old('wheels', $transfer->wheels) }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Weight</label>
                            <input type="text" name="weight" class="form-control"
                                value="{{ old('weight', $transfer->weight) }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Last Tax</label>
                            <input type="text" name="last_tax" class="form-control"
                                value="{{ old('last_tax', $transfer->last_tax) }}">
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Edit Transfer') }}</button>
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
