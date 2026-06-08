@extends('layouts.master')

@section('title', __('Transfers'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Transfers') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Transfers List Table -->
        <div class="card">
            {{-- <div class="card-header">
                @canany(['create transfer'])
                    <a href="{{route('dashboard.transfers.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Transfer') }}</span>
                    </a>
                @endcan
            </div> --}}
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Case No') }}</th>
                            <th>{{ __('From') }}</th>
                            <th>{{ __('To') }}</th>
                            <th>{{ __('Engine No') }}</th>
                            <th>{{ __('Chasis No') }}</th>
                            @canany(['delete transfer', 'update transfer', 'view transfer'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfers as $index => $transfer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>#{{ $transfer->vehicleCase->case_no }}</td>
                                <td>{{ $transfer->from_name }}</td>
                                <td>{{ $transfer->to_name }}</td>
                                <td>{{ $transfer->engine_no }}</td>
                                <td>{{ $transfer->chassis_no }}</td>

                                @canany(['delete transfer', 'update transfer', 'view transfer'])
                                    <td class="d-flex">
                                        @canany(['delete transfer'])
                                            <form action="{{ route('dashboard.transfers.destroy', $transfer->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Transfer') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update transfer'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.transfers.edit', $transfer->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Transfer') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view transfer'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.transfers.show', $transfer->id) }}"
                                                    class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Transfer') }}">
                                                    <i class="ti ti-receipt ti-md"></i>
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
