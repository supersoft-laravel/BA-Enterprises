@extends('layouts.master')

@section('title', __('Edit Billing'))

@section('css')
<style>
    tr.row-deleted td { background:#fff5f5 !important; text-decoration:line-through; color:#999; }
    tr.row-deleted .badge { opacity:0.5; }
    tr.row-deleted .item-amount { background:#fff5f5; color:#bbb; }
    .btn-delete-row { padding:2px 8px; font-size:0.75rem; }
    #deletionNotice { display:none; }
</style>
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
                                        {{ $case->vehicle_no }} - {{ $case->party_name }}
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

                        {{-- ADJUSTMENT --}}
                        <div class="mb-4 col-md-6">
                            <label class="form-label">{{ __('Adjustment Amount') }} <small class="text-muted">(deduction / counter bill)</small></label>
                            <input type="number" id="adjustment_amount" name="adjustment_amount" class="form-control"
                                value="{{ $billing->adjustment_amount ?? 0 }}" step="0.01" min="0">
                        </div>
                        <div class="mb-4 col-md-6">
                            <label class="form-label">{{ __('Adjustment Note') }} <small class="text-muted">(optional)</small></label>
                            <input type="text" id="adjustment_note" name="adjustment_note" class="form-control"
                                value="{{ $billing->adjustment_note }}" placeholder="e.g. Counter Bill / Discount">
                        </div>

                        {{-- ITEMS --}}
                        <div class="mb-4 col-md-12 billingItems">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ti ti-search text-muted"></i>
                                <input type="text" id="vehicleSearch" class="form-control form-control-sm"
                                    placeholder="Search by vehicle number..." style="max-width:280px;" autocomplete="off">
                                <span id="vehicleSearchCount" class="text-muted" style="font-size:0.82rem;white-space:nowrap;"></span>
                            </div>

                            <div id="deletionNotice" class="alert alert-warning py-2 mb-2" style="font-size:0.83rem;">
                                <i class="ti ti-alert-triangle me-1"></i>
                                <span id="deletionCount">0</span> service(s) marked for deletion — will be removed on save.
                            </div>

                            <table class="table table-bordered" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Vehicle No</th>
                                        <th>Item</th>
                                        <th width="200">Amount</th>
                                        <th width="80" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($billing->items as $index => $item)
                                        <tr>
                                            <td style="white-space:nowrap;">
                                                <span class="badge bg-label-secondary">
                                                    {{ $item->vehicleCase->vehicle_no ?? '—' }}
                                                </span>
                                            </td>
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
                                            <td class="text-center" style="vertical-align:middle;">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-row"
                                                    title="Mark for deletion">
                                                    <i class="ti ti-trash ti-xs"></i>
                                                </button>
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

            // SOFT DELETE ROWS
            $(document).on('click', '.btn-delete-row', function() {
                const $row = $(this).closest('tr');
                const totalRows = $('#itemsTable tbody tr').length;
                const deletedRows = $('#itemsTable tbody tr.row-deleted').length;

                if ($row.hasClass('row-deleted')) {
                    // UNDO
                    $row.removeClass('row-deleted');
                    $row.find('input').prop('disabled', false);
                    $(this).html('<i class="ti ti-trash ti-xs"></i>').removeClass('btn-warning').addClass('btn-outline-danger');
                    $(this).attr('title', 'Mark for deletion');
                } else {
                    // Cannot delete the last active row
                    const activeRows = totalRows - deletedRows;
                    if (activeRows <= 1) {
                        alert('At least one service must remain on the bill.');
                        return;
                    }
                    // SOFT DELETE
                    $row.addClass('row-deleted');
                    $row.find('input').prop('disabled', true);
                    $(this).html('<i class="ti ti-arrow-back-up ti-xs"></i> Undo').removeClass('btn-outline-danger').addClass('btn-warning');
                    $(this).attr('title', 'Undo deletion');
                }

                updateDeletionNotice();
                calculateTotal();
            });

            function updateDeletionNotice() {
                const count = $('#itemsTable tbody tr.row-deleted').length;
                if (count > 0) {
                    $('#deletionCount').text(count);
                    $('#deletionNotice').show();
                } else {
                    $('#deletionNotice').hide();
                }
            }

            // VEHICLE SEARCH FILTER
            $('#vehicleSearch').on('input', function() {
                const query = $(this).val().trim().toLowerCase();
                let visible = 0;

                $('#itemsTable tbody tr').each(function() {
                    const vehicleNo = $(this).find('td:first-child .badge').text().trim().toLowerCase();
                    if (!query || vehicleNo.includes(query)) {
                        $(this).show();
                        visible++;
                    } else {
                        $(this).hide();
                    }
                });

                const total = $('#itemsTable tbody tr:not(.row-deleted)').length;
                $('#vehicleSearchCount').text(query ? visible + ' of ' + total + ' active rows' : '');
            });

            // TOTAL CALC
            $(document).on('input', '.item-amount', function() {
                calculateTotal();
            });

            $('#paid_amount, #adjustment_amount').on('input', function() {
                calculateRemaining();
            });

            function calculateTotal() {
                let total = 0;

                $('.item-amount:not(:disabled)').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });

                $('#total_amount').val(total.toFixed(2));
                calculateRemaining();
            }

            function calculateRemaining() {
                let total      = parseFloat($('#total_amount').val()) || 0;
                let paid       = parseFloat($('#paid_amount').val()) || 0;
                let adjustment = parseFloat($('#adjustment_amount').val()) || 0;

                let remaining = total - adjustment - paid;

                $('#remaining_amount').val(remaining.toFixed(2));
            }

        });
    </script>
@endsection
