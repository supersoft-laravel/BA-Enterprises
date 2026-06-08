@extends('layouts.master')

@section('title', __('Edit Billing'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.billings.index') }}">{{ __('Billings') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">

            <div class="card-body pt-4">

                <form method="POST" action="{{ route('dashboard.billings.update', $billing->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row p-5">
                        <h3>{{ __('Edit Billing') }}</h3>

                        {{-- TYPE --}}
                        <div class="mb-4 col-md-6">
                            <label class="form-label">{{ __('Type') }}</label><span class="text-danger">*</span>
                            <select name="billing_type" class="form-select select2" required>
                                <option value="local" {{ $billing->billing_type == 'local' ? 'selected' : '' }}>Local Party
                                </option>
                                <option value="out_of_city" {{ $billing->billing_type == 'out_of_city' ? 'selected' : '' }}>
                                    Out of City Party</option>
                            </select>
                        </div>

                        {{-- CASE --}}
                        <div class="mb-4 col-md-6">
                            <label class="form-label">{{ __('Case') }}</label><span class="text-danger">*</span>

                            <select name="vehicle_case_id" id="vehicle_case_id" class="form-select" disabled>
                                @foreach ($cases as $case)
                                    <option value="{{ $case->id }}"
                                        {{ $billing->vehicle_case_id == $case->id ? 'selected' : '' }}>
                                        {{ $case->case_no }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- hidden input to submit disabled value --}}
                            <input type="hidden" name="vehicle_case_id" value="{{ $billing->vehicle_case_id }}">
                        </div>

                        {{-- NAME --}}
                        <div class="mb-4 col-md-6">
                            <label class="form-label">{{ __('Name') }}</label><span class="text-danger">*</span>
                            <input type="text" name="billing_name" class="form-control"
                                value="{{ $billing->billing_name }}">
                        </div>

                        {{-- DATE --}}
                        <div class="mb-4 col-md-6">
                            <label class="form-label">{{ __('Date') }}</label><span class="text-danger">*</span>
                            <input type="date" name="billing_date" class="form-control"
                                value="{{ $billing->billing_date }}">
                        </div>

                        {{-- ITEMS --}}
                        <div class="mb-4 col-md-12 billingItems">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th width="200">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->items as $index => $item)
                                        <tr>
                                            <td>
                                                {{ $item->item_name }}
                                                <input type="hidden" name="items[{{ $index }}][name]"
                                                    value="{{ $item->item_name }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control item-amount"
                                                    name="items[{{ $index }}][amount]"
                                                    value="{{ $item->item_amount }}" step="0.01">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- TOTAL --}}
                        <div class="mb-4 col-md-12">
                            <label class="form-label">{{ __('Total Amount') }}</label>
                            <input type="number" id="total_amount" name="total_amount" class="form-control"
                                value="{{ $billing->total_amount }}" step="0.01">
                        </div>

                        {{-- PAID --}}
                        <div class="mb-4 col-md-12">
                            <label class="form-label">{{ __('Paid Amount') }}</label>
                            <input type="number" id="paid_amount" name="paid_amount" class="form-control"
                                value="{{ $billing->paid_amount }}" step="0.01">
                        </div>

                        {{-- REMAINING --}}
                        <div class="mb-4 col-md-12">
                            <label class="form-label">{{ __('Remaining Amount') }}</label>
                            <input type="number" id="remaining_amount" name="remaining_amount" class="form-control"
                                value="{{ $billing->remaining_amount }}" step="0.01">
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="mb-4 col-md-12">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control">{{ $billing->description }}</textarea>
                        </div>

                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">{{ __('Update Billing') }}</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // TOTAL CALC
            $(document).on('input', '.item-amount', function() {
                calculateTotal();
            });

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
