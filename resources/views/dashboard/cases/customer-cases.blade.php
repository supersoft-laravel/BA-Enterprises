@extends('layouts.master')

@section('title', $customer->name . ' — Cases')

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.cases.index') }}">{{ __('Cases') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ $customer->name }}</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Compact Info Bar --}}
    <div class="card mb-4">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                {{-- LEFT: Customer + Bill ID --}}
                <div class="d-flex align-items-center flex-wrap gap-2" style="font-size:0.88rem;">
                    <span class="badge bg-label-primary fw-bold" style="font-size:0.8rem;letter-spacing:0.03em;">
                        {{ $customer->customer_code }}
                    </span>
                    <button class="btn btn-icon btn-text-secondary btn-sm p-0 ms-n1 copy-btn"
                            data-copy="{{ $customer->customer_code }}"
                            title="Copy customer code"
                            style="width:22px;height:22px;">
                        <i class="ti ti-copy" style="font-size:0.8rem;"></i>
                    </button>

                    <span class="text-muted" style="margin:0 2px;">|</span>
                    <span class="fw-semibold">{{ $customer->name }}</span>
                    @if($customer->mobile)
                        <span class="text-muted" style="margin:0 2px;">|</span>
                        <span class="text-secondary">{{ $customer->mobile }}</span>
                    @endif

                    @if($billing)
                        <span class="text-muted" style="margin:0 2px;">|</span>
                        <span class="text-secondary" style="font-size:0.82rem;">{{ $billing->bill_no }}</span>
                        <button class="btn btn-icon btn-text-secondary btn-sm p-0 ms-n1 copy-btn"
                                data-copy="{{ $billing->bill_no }}"
                                title="Copy bill number"
                                style="width:22px;height:22px;">
                            <i class="ti ti-copy" style="font-size:0.8rem;"></i>
                        </button>
                    @endif
                </div>

                {{-- RIGHT: Amounts + View Bill --}}
                <div class="d-flex align-items-center flex-wrap gap-3" style="font-size:0.85rem;">
                    @if($billing)
                        <div class="text-center">
                            <div class="text-muted" style="font-size:0.7rem;line-height:1.1;">Total</div>
                            <div class="fw-bold">₨ {{ number_format($billing->total_amount, 0) }}</div>
                        </div>
                        <div class="text-muted">|</div>
                        <div class="text-center">
                            <div class="text-muted" style="font-size:0.7rem;line-height:1.1;">Paid</div>
                            <div class="fw-bold" style="color:#059669;">₨ {{ number_format($billing->paid_amount, 0) }}</div>
                        </div>
                        <div class="text-muted">|</div>
                        <div class="text-center">
                            <div class="text-muted" style="font-size:0.7rem;line-height:1.1;">Remaining</div>
                            <div class="fw-bold" style="color:#dc2626;">₨ {{ number_format($billing->remaining_amount, 0) }}</div>
                        </div>
                        @can('view billing')
                        <div class="text-muted">|</div>
                        <a href="{{ route('dashboard.billings.show', $billing->id) }}"
                           class="btn btn-sm btn-outline-primary"
                           style="border-radius:9999px;padding:0.2rem 0.85rem;font-size:0.78rem;">
                            <i class="ti ti-receipt me-1"></i>View Bill
                        </a>
                        @endcan
                    @else
                        <span class="text-secondary" style="font-size:0.82rem;">
                            <i class="ti ti-info-circle me-1"></i>No bill generated yet.
                        </span>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Cases Table --}}
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="{{ $cases->isNotEmpty() ? 'datatables-users' : '' }} table border-top custom-datatables">
                <thead>
                    <tr>
                        <th>{{ __('Sr.') }}</th>
                        <th>{{ __('Vehicle No.') }}</th>
                        <th>{{ __('Vendor Name') }}</th>
                        <th>{{ __('City') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-end">{{ __('Amount') }}</th>
                        @canany(['delete case', 'update case', 'view case'])
                            <th>{{ __('Action') }}</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cases as $index => $case)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $case->vehicle_no ?? '—' }}</td>
                            <td>{{ $case->vendor_name ?? '—' }}</td>
                            <td>{{ ucfirst($case->city) }}</td>
                            <td>{{ $case->case_date ? \Carbon\Carbon::parse($case->case_date)->format('d M Y') : '—' }}</td>
                            <td>
                                <span class="badge me-4 bg-label-{{ $case->status === 'open' ? 'success' : 'danger' }}">
                                    {{ ucfirst($case->status) }}
                                </span>
                            </td>
                            <td class="text-end fw-semibold">
                                @php $amt = $caseAmounts[$case->id] ?? 0; @endphp
                                {{ $amt > 0 ? '₨ ' . number_format($amt, 0) : '—' }}
                            </td>
                            @canany(['delete case', 'update case', 'view case'])
                                <td class="d-flex">
                                    @canany(['delete case'])
                                        <form class="case-delete-form"
                                              action="{{ route('dashboard.cases.destroy', $case->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <a href="#"
                                               class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill case-delete-btn"
                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                               title="{{ __('Delete Case') }}"
                                               data-vehicle="{{ $case->vehicle_no ?? 'this case' }}">
                                                <i class="ti ti-trash ti-md"></i>
                                            </a>
                                        </form>
                                    @endcan
                                    @canany(['update case'])
                                        <a href="{{ route('dashboard.cases.edit', $case->id) }}"
                                           class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                           data-bs-toggle="tooltip" data-bs-placement="top"
                                           title="{{ __('Edit Case') }}">
                                            <i class="ti ti-edit ti-md"></i>
                                        </a>
                                    @endcan
                                    @canany(['view case'])
                                        <a href="{{ route('dashboard.cases.show', $case->id) }}"
                                           class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                           data-bs-toggle="tooltip" data-bs-placement="top"
                                           title="{{ __('View Case') }}">
                                            <i class="ti ti-eye ti-md"></i>
                                        </a>
                                    @endcan
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-4">
                                <i class="ti ti-folder-off ti-lg me-2"></i>No cases for this customer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Copy to clipboard
        $(document).on('click', '.copy-btn', function() {
            const text = $(this).data('copy');
            navigator.clipboard.writeText(text).then(() => {
                const icon = $(this).find('i');
                icon.removeClass('ti-copy').addClass('ti-check');
                setTimeout(() => icon.removeClass('ti-check').addClass('ti-copy'), 1500);
            });
        });

        // Delete confirm
        $(document).on('click', '.case-delete-btn', function(e) {
            e.preventDefault();
            const form    = $(this).closest('.case-delete-form');
            const vehicle = $(this).data('vehicle');
            Swal.fire({
                title: 'Delete Case?',
                html: `<p>You are about to delete <strong>${vehicle}</strong>.</p>
                       <div class="alert alert-danger text-start mt-2 mb-0 py-2" style="font-size:0.85rem;">
                           <i class="ti ti-alert-triangle me-1"></i>
                           <strong>Note:</strong> Deleting this case removes its service records and billing items.
                           The customer bill totals will <strong>not</strong> be automatically recalculated.
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
                    cancelButton:  'btn btn-label-secondary waves-effect waves-light'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endsection
