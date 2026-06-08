@extends('layouts.master')

@section('title', __('Create Billing'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.billings.index') }}">{{ __('Billings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.billings.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row p-5">
                        <h3>{{ __('Add New Billing') }}</h3>
                        <div class="mb-4 col-md-6">
                            <label for="billing_type" class="form-label">{{ __('Type') }}</label><span
                                class="text-danger">*</span>
                            <select name="billing_type"
                                class="form-select select2 @error('billing_type') is-invalid @enderror" required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="local">Local Party</option>
                                <option value="out_of_city">Out of City Party</option>
                            </select>
                            @error('billing_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="vehicle_case_id" class="form-label">{{ __('Case') }}</label><span
                                class="text-danger">*</span>
                            <select name="vehicle_case_id"
                                class="form-select select2 @error('vehicle_case_id') is-invalid @enderror" id="vehicle_case_id" required>
                                <option value="" selected disabled>Select Case</option>
                                @foreach ($cases as $case)
                                    <option value="{{ $case->id }}">{{ $case->vehicle_no }} - {{ $case->party_name }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_case_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="billing_name" class="form-label">{{ __('Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('billing_name') is-invalid @enderror" type="text"
                                id="billing_name" name="billing_name" required placeholder="{{ __('Enter billing name') }}"
                                value="{{ old('billing_name') }}" />
                            @error('billing_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="billing_date" class="form-label">{{ __('Date') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('billing_date') is-invalid @enderror" type="date"
                                id="billing_date" name="billing_date" required placeholder="{{ __('Enter billing date') }}"
                                value="{{ old('billing_date') }}" />
                            @error('billing_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12 billingItems">

                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="total_amount" class="form-label">{{ __('Total Amount') }}</label>
                            <input class="form-control @error('total_amount') is-invalid @enderror" type="number"
                                id="total_amount" name="total_amount" placeholder="{{ __('Enter total amount') }}"
                                value="{{ old('total_amount') }}" step="0.01" />
                            @error('total_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="paid_amount" class="form-label">{{ __('Paid Amount') }}</label>
                            <input class="form-control @error('paid_amount') is-invalid @enderror" type="number"
                                id="paid_amount" name="paid_amount" placeholder="{{ __('Enter paid amount') }}"
                                value="{{ old('paid_amount') }}" step="0.01" />
                            @error('paid_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="remaining_amount" class="form-label">{{ __('Remaining Amount') }}</label>
                            <input class="form-control @error('remaining_amount') is-invalid @enderror" type="number"
                                id="remaining_amount" name="remaining_amount"
                                placeholder="{{ __('Enter remaining amount') }}" value="{{ old('remaining_amount') }}"
                                step="0.01" />
                            @error('remaining_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                placeholder="{{ __('Enter description') }}">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Add Billing') }}</button>
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

            $('#vehicle_case_id').on('change', function() {
                let caseId = $(this).val();

                if (!caseId) return;

                $.ajax({
                    url: `/dashboard/cases/${caseId}/items`,
                    type: 'GET',
                    success: function(items) {

                        let html = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th width="200">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                        items.forEach((item, index) => {
                            html += `
                        <tr>
                            <td>
                                ${item.name}
                                <input type="hidden" name="items[${index}][name]" value="${item.name}">
                            </td>
                            <td>
                                <input type="number" class="form-control item-amount"
                                    name="items[${index}][amount]" value="0" step="0.01">
                            </td>
                        </tr>
                    `;
                        });

                        html += `</tbody></table>`;

                        $('.billingItems').html(html);

                        calculateTotal();
                    }
                });
            });

            // AUTO TOTAL
            $(document).on('input', '.item-amount', function() {
                calculateTotal();
            });

            // PAID CHANGE
            $('#paid_amount').on('input', function() {
                calculateRemaining();
            });

            function calculateTotal() {
                let total = 0;

                $('.item-amount').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });

                $('#total_amount').val(total.toFixed(2));

                calculateRemaining();
            }

            function calculateRemaining() {
                let total = parseFloat($('#total_amount').val()) || 0;
                let paid = parseFloat($('#paid_amount').val()) || 0;

                let remaining = total - paid;

                $('#remaining_amount').val(remaining.toFixed(2));
            }

        });
    </script>
@endsection
