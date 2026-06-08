@extends('layouts.master')

@section('title', __('Payments'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Payments') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Payments List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create payment'])
                    <a href="{{ route('dashboard.payments.create') }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Payment') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Bill No') }}</th>
                            <th>{{ __('Vehicle No') }}</th>
                            <th>{{ __('Party Name') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Method') }}</th>
                            @canany(['delete payment', 'update payment'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><a href="{{ route('dashboard.billings.show', $payment->billing->id) }}">{{ $payment->billing->bill_no }}</a></td>
                                <td>{{ $payment->billing->vehicleCase->vehicle_no ?? 'N/A' }}</td>
                                <td>{{ $payment->billing->vehicleCase->party_name ?? 'N/A' }}</td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($payment->amount) }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                @canany(['delete payment', 'update payment'])
                                    <td class="d-flex">
                                        @canany(['delete payment'])
                                            <form action="{{ route('dashboard.payments.destroy', $payment->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Payment') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
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
