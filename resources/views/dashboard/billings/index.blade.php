@extends('layouts.master')

@section('title', __('Billings'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Billings') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Billings List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create billing'])
                    <a href="{{ route('dashboard.billings.create') }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Billing') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Vehicle No') }}</th>
                            <th>{{ __('Party Name') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete billing', 'update billing'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($billings as $index => $billing)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $billing->vehicleCase->vehicle_no ?? 'N/A' }}</td>
                                <td>{{ $billing->vehicleCase->party_name ?? 'N/A' }}</td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($billing->total_amount) }}</td>

                                <td>{{ \Carbon\Carbon::parse($billing->billing_date)->format('d M Y') }}</td>
                                @php
                                    $statusClass = [
                                        'paid' => 'success',
                                        'partial' => 'warning',
                                        'unpaid' => 'danger',
                                    ];
                                @endphp

                                <td>
                                    <span class="badge me-4 bg-label-{{ $statusClass[$billing->status] ?? 'secondary' }}">
                                        {{ ucfirst($billing->status) }}
                                    </span>
                                </td>
                                @canany(['delete billing', 'update billing', 'view billing'])
                                    <td class="d-flex">
                                        @canany(['delete billing'])
                                            <form action="{{ route('dashboard.billings.destroy', $billing->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Billing') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update billing'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.billings.edit', $billing->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Billing') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view billing'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.billings.show', $billing->id) }}"
                                                    class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Billing') }}">
                                                    <i class="ti ti-eye ti-md"></i>
                                                </a>
                                            </span>
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
    {{-- <script src="{{asset('assets/js/app-user-list.js')}}"></script> --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
