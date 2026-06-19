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
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">

                @canany(['create payment'])
                    <a href="{{ route('dashboard.payments.create') }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-1 ti-xs"></i>{{ __('Add New Payment') }}
                    </a>
                @endcan

                <form method="GET" action="{{ route('dashboard.payments.index') }}" class="d-flex align-items-center gap-2 flex-wrap">

                    <select name="payment_method" class="form-select form-select-sm" style="width:190px;">
                        <option value="">All Methods</option>
                        @foreach(['Cash','Bank Transfer','Credit/Debit Card','Cheque','JazzCash','EasyPaisa','Meezan Bank','Allied Bank Limited (ABL)','Askari Bank','Bank Al-Habib Limited (BAHL)','Bank of Punjab (BOP)','Faysal Bank','Habib Bank Limited (HBL)','MCB Bank Limited','National Bank of Pakistan (NBP)','United Bank Limited (UBL)','Other'] as $method)
                            <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>

                    <select name="customer_id" class="form-select form-select-sm" style="width:210px;">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->customer_code }})
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light">
                        <i class="ti ti-filter ti-xs me-1"></i>Filter
                    </button>

                    @if(request('payment_method') || request('customer_id'))
                        <a href="{{ route('dashboard.payments.index') }}" class="btn btn-sm btn-outline-secondary waves-effect">
                            <i class="ti ti-x ti-xs me-1"></i>Clear
                        </a>
                    @endif

                </form>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Bill No') }}</th>
                            <th>{{ __('Vehicle No') }}</th>
                            <th>{{ __('Customer Name') }}</th>
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
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
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
