@extends('layouts.master')

@section('title', __('Customers'))

@section('css')
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Customers') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                @can('create customer')
                    <a href="{{ route('dashboard.customers.create') }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                        <span class="d-none d-sm-inline-block">{{ __('Add New Customer') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Customer Name') }}</th>
                            <th>{{ __('Mobile') }}</th>
                            <th>{{ __('Cases') }}</th>
                            <th>{{ __('Bill Status') }}</th>
                            @canany(['update customer', 'view customer', 'delete customer'])
                                <th>{{ __('Action') }}</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge bg-label-primary fw-semibold">{{ $customer->customer_code }}</span></td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->mobile ?? '—' }}</td>
                                <td><span class="badge bg-label-info">{{ $customer->vehicle_cases_count }}</span></td>
                                <td>
                                    @if ($customer->billing)
                                        @php
                                            $statusClass = ['paid' => 'success', 'partial' => 'warning', 'unpaid' => 'danger'];
                                        @endphp
                                        <span class="badge bg-label-{{ $statusClass[$customer->billing->status] ?? 'secondary' }}">
                                            {{ ucfirst($customer->billing->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-label-secondary">No Bill</span>
                                    @endif
                                </td>
                                @canany(['update customer', 'view customer', 'delete customer'])
                                    <td class="d-flex">
                                        @can('delete customer')
                                            <form class="customer-delete-form"
                                                action="{{ route('dashboard.customers.destroy', $customer->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill customer-delete-btn"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Customer') }}"
                                                    data-name="{{ $customer->name }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @can('update customer')
                                            <a href="{{ route('dashboard.customers.edit', $customer->id) }}"
                                                class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Edit Customer') }}">
                                                <i class="ti ti-edit ti-md"></i>
                                            </a>
                                        @endcan
                                        @can('view customer')
                                            <a href="{{ route('dashboard.customers.show', $customer->id) }}"
                                                class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('View Customer') }}">
                                                <i class="ti ti-eye ti-md"></i>
                                            </a>
                                        @endcan
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.customer-delete-btn', function (e) {
                e.preventDefault();
                const form = $(this).closest('.customer-delete-form');
                const name = $(this).data('name');
                Swal.fire({
                    title: 'Delete Customer?',
                    html: `<p>Are you sure you want to delete <strong>${name}</strong>?</p>
                           <p class="mt-2 mb-0 text-danger fw-bold">This cannot be undone.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endsection
