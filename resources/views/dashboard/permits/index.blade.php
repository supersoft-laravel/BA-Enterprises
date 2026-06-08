@extends('layouts.master')

@section('title', __('Permits'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Permits') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Permits List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create permit'])
                    <a href="{{route('dashboard.permits.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Permit') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Vehicle') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Holder') }}</th>
                            @canany(['delete permit', 'update permit', 'view permit'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permits as $index => $permit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $permit->vehicle->vehicle_name }}</td>
                                <td>{{ ucfirst($permit->type) }}</td>
                                <td>{{ $permit->permitHolder->name }}</td>
                                @canany(['delete permit', 'update permit', 'view permit'])
                                    <td class="d-flex">
                                        @canany(['delete permit'])
                                            <form action="{{ route('dashboard.permits.destroy', $permit->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Permit') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update permit'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.permits.edit', $permit->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Permit') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view permit'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.permits.show', $permit->id) }}"
                                                    class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Permit') }}">
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
