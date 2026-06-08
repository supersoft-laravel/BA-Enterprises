@extends('layouts.master')

@section('title', __('Create Payment'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.payments.index') }}">{{ __('Payments') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.payments.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row p-5">
                        <h3>{{ __('Add New Payment') }}</h3>
                        @php
                            $selectedBilling = request('billing_id');
                        @endphp
                        <div class="mb-4 col-md-12">
                            <label for="billing_id" class="form-label">{{ __('Billing') }}</label><span
                                class="text-danger">*</span>
                            <select name="billing_id" class="form-select select2 @error('billing_id') is-invalid @enderror"
                                id="billing_id" required>
                                <option value="" selected disabled>Select Billing</option>
                                @foreach ($billings as $bill)
                                    <option value="{{ $bill->id }}" data-remaining="{{ $bill->remaining_amount }}" {{ $selectedBilling == $bill->id ? 'selected' : '' }}>
                                        {{ $bill->vehicleCase->vehicle_no }} - {{ $bill->vehicleCase->party_name }} - ({{ $bill->bill_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('billing_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label class="form-label">Remaining Amount</label>
                            <input type="text" id="remaining_amount" class="form-control" readonly>
                        </div>

                        <div class="mb-4 col-md-6">
                            <label class="form-label">Amount</label><span class="text-danger">*</span>
                            <input type="number" step="0.01" name="amount" id="amount"
                                class="form-control @error('amount') is-invalid @enderror" required>

                            @error('amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4 col-md-6">
                            <label class="form-label">Payment Date</label><span class="text-danger">*</span>
                            <input type="date" name="payment_date"
                                class="form-control @error('payment_date') is-invalid @enderror"
                                value="{{ old('payment_date', date('Y-m-d')) }}" required>

                            @error('payment_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4 col-md-6">
                            <label class="form-label">Payment Method</label><span class="text-danger">*</span>

                            <select name="payment_method"
                                class="form-select select2 @error('payment_method') is-invalid @enderror" required>

                                <option value="">Select Method</option>

                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>

                                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>
                                    Bank Transfer
                                </option>

                                <option value="Credit/Debit Card" {{ old('payment_method') == 'Credit/Debit Card' ? 'selected' : '' }}>
                                    Credit/Debit Card
                                </option>

                                <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>

                                <option value="JazzCash" {{ old('payment_method') == 'JazzCash' ? 'selected' : '' }}>JazzCash</option>

                                <option value="EasyPaisa" {{ old('payment_method') == 'EasyPaisa' ? 'selected' : '' }}>EasyPaisa</option>

                                <option value="Allied Bank Limited (ABL)" {{ old('payment_method') == 'Allied Bank Limited (ABL)' ? 'selected' : '' }}>
                                    Allied Bank Limited (ABL)
                                </option>

                                <option value="Askari Bank" {{ old('payment_method') == 'Askari Bank' ? 'selected' : '' }}>
                                    Askari Bank
                                </option>

                                <option value="Bank Al-Habib Limited (BAHL)" {{ old('payment_method') == 'Bank Al-Habib Limited (BAHL)' ? 'selected' : '' }}>
                                    Bank Al-Habib Limited (BAHL)
                                </option>

                                <option value="Bank of Punjab (BOP)" {{ old('payment_method') == 'Bank of Punjab (BOP)' ? 'selected' : '' }}>
                                    Bank of Punjab (BOP)
                                </option>

                                <option value="Bank of Khyber (BOK)" {{ old('payment_method') == 'Bank of Khyber (BOK)' ? 'selected' : '' }}>
                                    Bank of Khyber (BOK)
                                </option>

                                <option value="Al Baraka Bank" {{ old('payment_method') == 'Al Baraka Bank' ? 'selected' : '' }}>
                                    Al Baraka Bank
                                </option>

                                <option value="Burj Bank" {{ old('payment_method') == 'Burj Bank' ? 'selected' : '' }}>
                                    Burj Bank
                                </option>

                                <option value="Dubai Islamic Bank" {{ old('payment_method') == 'Dubai Islamic Bank' ? 'selected' : '' }}>
                                    Dubai Islamic Bank
                                </option>

                                <option value="Faysal Bank" {{ old('payment_method') == 'Faysal Bank' ? 'selected' : '' }}>
                                    Faysal Bank
                                </option>

                                <option value="First Women Bank Limited (FWBL)" {{ old('payment_method') == 'First Women Bank Limited (FWBL)' ? 'selected' : '' }}>
                                    First Women Bank Limited (FWBL)
                                </option>

                                <option value="Habib Metro Bank" {{ old('payment_method') == 'Habib Metro Bank' ? 'selected' : '' }}>
                                    Habib Metro Bank
                                </option>

                                <option value="Habib Bank Limited (HBL)" {{ old('payment_method') == 'Habib Bank Limited (HBL)' ? 'selected' : '' }}>
                                    Habib Bank Limited (HBL)
                                </option>

                                <option value="Industrial and Commercial Bank of China (ICBC)" {{ old('payment_method') == 'Industrial and Commercial Bank of China (ICBC)' ? 'selected' : '' }}>
                                    Industrial and Commercial Bank of China (ICBC)
                                </option>

                                <option value="JS Bank" {{ old('payment_method') == 'JS Bank' ? 'selected' : '' }}>
                                    JS Bank
                                </option>

                                <option value="MCB Bank Limited" {{ old('payment_method') == 'MCB Bank Limited' ? 'selected' : '' }}>
                                    MCB Bank Limited
                                </option>

                                <option value="Meezan Bank" {{ old('payment_method') == 'Meezan Bank' ? 'selected' : '' }}>
                                    Meezan Bank
                                </option>

                                <option value="National Bank of Pakistan (NBP)" {{ old('payment_method') == 'National Bank of Pakistan (NBP)' ? 'selected' : '' }}>
                                    National Bank of Pakistan (NBP)
                                </option>

                                <option value="Samba Bank" {{ old('payment_method') == 'Samba Bank' ? 'selected' : '' }}>
                                    Samba Bank
                                </option>

                                <option value="State Bank of Pakistan (SBP)" {{ old('payment_method') == 'State Bank of Pakistan (SBP)' ? 'selected' : '' }}>
                                    State Bank of Pakistan (SBP)
                                </option>

                                <option value="Silk Bank" {{ old('payment_method') == 'Silk Bank' ? 'selected' : '' }}>
                                    Silk Bank
                                </option>

                                <option value="Sindh Bank" {{ old('payment_method') == 'Sindh Bank' ? 'selected' : '' }}>
                                    Sindh Bank
                                </option>

                                <option value="Soneri Bank" {{ old('payment_method') == 'Soneri Bank' ? 'selected' : '' }}>
                                    Soneri Bank
                                </option>

                                <option value="Standard Chartered Bank" {{ old('payment_method') == 'Standard Chartered Bank' ? 'selected' : '' }}>
                                    Standard Chartered Bank
                                </option>

                                <option value="Summit Bank" {{ old('payment_method') == 'Summit Bank' ? 'selected' : '' }}>
                                    Summit Bank
                                </option>

                                <option value="United Bank Limited (UBL)" {{ old('payment_method') == 'United Bank Limited (UBL)' ? 'selected' : '' }}>
                                    United Bank Limited (UBL)
                                </option>

                                <option value="Zarai Taraqiati Bank Limited (ZTBL)" {{ old('payment_method') == 'Zarai Taraqiati Bank Limited (ZTBL)' ? 'selected' : '' }}>
                                    Zarai Taraqiati Bank Limited (ZTBL)
                                </option>

                                <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                            @error('payment_method')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Add Payment') }}</button>
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

            function updateAmounts() {
                let remaining = $('#billing_id').find(':selected').data('remaining');

                $('#remaining_amount').val(remaining);
                $('#amount').val(remaining);
            }

            $('#billing_id').on('change', function() {
                updateAmounts();
            });

            // page load par selected billing ka amount show karne ke liye
            if ($('#billing_id').val()) {
                updateAmounts();
            }

        });
    </script>
@endsection
