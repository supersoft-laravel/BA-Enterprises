@extends('layouts.master')

@section('title', __('Alterations'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Alterations') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Alterations List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create alteration'])
                    <a href="{{route('dashboard.alterations.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Alteration') }}</span>
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
                            <th>{{ __('From') }}</th>
                            <th>{{ __('To') }}</th>
                            @canany(['delete alteration', 'update alteration', 'view alteration'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alterations as $index => $alteration)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $alteration->vehicle->vehicle_name }}</td>
                                <td>{{ ucfirst($alteration->type) }}</td>
                                <td>{{ $alteration->fromUser->name }}</td>
                                <td>{{ $alteration->toUser->name }}</td>
                                @canany(['delete alteration', 'update alteration', 'view alteration'])
                                    <td class="d-flex">
                                        @canany(['delete alteration'])
                                            <form action="{{ route('dashboard.alterations.destroy', $alteration->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Alteration') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update alteration'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.alterations.edit', $alteration->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Alteration') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view alteration'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.alterations.show', $alteration->id) }}"
                                                    class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Alteration') }}">
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
