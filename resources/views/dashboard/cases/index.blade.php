@extends('layouts.master')

@section('title', __('Cases'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Cases') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Cases List Table -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                {{-- @canany(['create case'])
                    <a href="{{route('dashboard.cases.create')}}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Case') }}</span>
                    </a>
                @endcan --}}
                <form method="GET" action="{{ route('dashboard.cases.index') }}">
                    <div class="d-flex gap-2 align-items-center mt-3">
                        <select name="refer_to" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ __('All Cities') }}</option>
                            <option value="Karachi" {{ request('refer_to') == 'Karachi' ? 'selected' : '' }}>Karachi</option>
                            <option value="Lasbella" {{ request('refer_to') == 'Lasbella' ? 'selected' : '' }}>Lasbella</option>
                            <option value="Quetta" {{ request('refer_to') == 'Quetta' ? 'selected' : '' }}>Quetta</option>
                            <option value="Peshawar" {{ request('refer_to') == 'Peshawar' ? 'selected' : '' }}>Peshawar</option>
                            <option value="Gilgit" {{ request('refer_to') == 'Gilgit' ? 'selected' : '' }}>Gilgit</option>
                            <option value="Punjab" {{ request('refer_to') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            <option value="Other" {{ request('refer_to') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Reg. No.') }}</th>
                            <th>{{ __('Party Name') }}</th>
                            <th>{{ __('City') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete case', 'update case', 'view case'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cases as $index => $case)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $case->vehicle_no }}</td>
                                <td>{{ $case->party_name }}</td>
                                <td>{{ ucfirst($case->city) }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $case->status == 'closed' ? 'success' : 'warning' }}">{{ ucfirst($case->status) }}</span>
                                </td>
                                @canany(['delete case', 'update case', 'view case'])
                                    <td class="d-flex">
                                        {{-- @canany(['delete case'])
                                            <form action="{{ route('dashboard.cases.destroy', $case->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Case') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan --}}
                                        {{-- @canany(['update case'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.cases.edit', $case->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Case') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan --}}
                                        @canany(['view case'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.cases.show', $case->id) }}"
                                                    class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Case') }}">
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
