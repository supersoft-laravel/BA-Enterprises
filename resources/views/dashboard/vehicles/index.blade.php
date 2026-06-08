@extends('layouts.master')

@section('title', __('Vehicles'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Vehicles') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Vehicles List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create vehicle'])
                    <a href="{{route('dashboard.vehicles.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Vehicle') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Reg No') }}</th>
                            <th>{{ __('Make') }}</th>
                            <th>{{ __('Model') }}</th>
                            <th>{{ __('Year') }}</th>
                            @canany(['delete vehicle', 'update vehicle'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicles as $index => $vehicle)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $vehicle->vehicle_name }}</td>
                                <td>{{ $vehicle->reg_no }}</td>
                                <td>{{ $vehicle->make }}</td>
                                <td>{{ $vehicle->model }}</td>
                                <td>{{ $vehicle->year }}</td>
                                @canany(['delete vehicle', 'update vehicle'])
                                    <td class="d-flex">
                                        @canany(['delete vehicle'])
                                            <form action="{{ route('dashboard.vehicles.destroy', $vehicle->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Vehicle') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update vehicle'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.vehicles.edit', $vehicle->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Vehicle') }}">
                                                    <i class="ti ti-edit ti-md"></i>
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
