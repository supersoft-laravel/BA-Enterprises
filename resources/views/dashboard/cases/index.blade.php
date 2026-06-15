@extends('layouts.master')

@section('title', __('Cases'))

@section('css')
<style>
    .customer-row { cursor: pointer; transition: background 0.12s; }
    .customer-row:hover td { background: rgba(29,78,216,0.04); }
    .bill-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.65rem; border-radius: 9999px;
        font-size: 0.74rem; font-weight: 600;
    }
    #vehicle-search-wrap { position: relative; width: 260px; }
    #vehicle-search-wrap input { padding-right: 2rem; }
    #vehicle-search-clear {
        position: absolute; right: 0.55rem; top: 50%; transform: translateY(-50%);
        cursor: pointer; display: none; color: #6c757d; font-size: 1rem; line-height: 1;
    }
    #vehicle-search-clear:hover { color: #dc2626; }
</style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Cases') }}</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0 fw-bold">Customers &amp; Cases</h5>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div id="vehicle-search-wrap">
                    <input id="vehicle-search"
                           type="text"
                           class="form-control form-control-sm"
                           placeholder="Search vehicle no..."
                           autocomplete="off">
                    <span id="vehicle-search-clear" title="Clear search">
                        <i class="ti ti-x"></i>
                    </span>
                </div>
                <span class="badge bg-label-secondary">{{ $customerGroups->count() }} customers
                    @if($legacyCount > 0)
                        <span class="ms-1 text-warning">+ {{ $legacyCount }} uncategorized</span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Search results (hidden by default) --}}
        <div id="search-results-wrap" class="card-datatable table-responsive" style="display:none;">
            <table class="table border-top" id="search-results-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vehicle No.</th>
                        <th>Customer</th>
                        <th>Vendor Name</th>
                        <th>City</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="search-results-body"></tbody>
            </table>
        </div>

        {{-- Customer table (shown by default) --}}
        <div id="customer-table-wrap" class="card-datatable table-responsive">
            <table class="table border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Mobile</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Remaining</th>
                        <th class="text-center">Bill</th>
                        <th class="text-center">Cases</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customerGroups as $index => $customer)
                        @php $bill = $customer->billing; @endphp
                        <tr class="customer-row"
                            onclick="window.location='{{ route('dashboard.cases.customer-cases', $customer->id) }}'">
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-label-primary" style="font-size:0.72rem;">{{ $customer->customer_code }}</span>
                            </td>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td class="text-secondary">{{ $customer->mobile ?? '—' }}</td>
                            <td class="text-end fw-semibold">
                                {{ $bill ? '₨ ' . number_format($bill->total_amount, 0) : '—' }}
                            </td>
                            <td class="text-end fw-semibold" style="color:#059669;">
                                {{ $bill ? '₨ ' . number_format($bill->paid_amount, 0) : '—' }}
                            </td>
                            <td class="text-end fw-semibold" style="color:#dc2626;">
                                {{ $bill ? '₨ ' . number_format($bill->remaining_amount, 0) : '—' }}
                            </td>
                            <td class="text-center">
                                @if($bill)
                                    @php
                                        $billColors = [
                                            'paid'    => ['bg-label-success',  'Paid'],
                                            'partial' => ['bg-label-warning',  'Partial'],
                                            'unpaid'  => ['bg-label-danger',   'Unpaid'],
                                        ];
                                        [$cls, $lbl] = $billColors[$bill->status] ?? ['bg-label-secondary', ucfirst($bill->status)];
                                    @endphp
                                    <span class="bill-badge {{ $cls }}">{{ $lbl }}</span>
                                @else
                                    <span class="bill-badge bg-label-secondary">No Bill</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-label-info">{{ $customer->vehicle_cases_count }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-4">
                                <i class="ti ti-folder-off ti-lg me-2"></i>No cases found. Create the first case from the Dashboard.
                            </td>
                        </tr>
                    @endforelse

                    @if($legacyCount > 0)
                        <tr>
                            <td>—</td>
                            <td colspan="8" class="text-warning fw-semibold">
                                <i class="ti ti-alert-triangle me-1"></i>
                                {{ $legacyCount }} uncategorized case(s) with no customer linked (legacy data)
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>{{-- /customer-table-wrap --}}
    </div>{{-- /card --}}
</div>
@endsection

@section('script')
<script>
(function () {
    var searchInput   = document.getElementById('vehicle-search');
    var clearBtn      = document.getElementById('vehicle-search-clear');
    var customerWrap  = document.getElementById('customer-table-wrap');
    var resultsWrap   = document.getElementById('search-results-wrap');
    var resultsBody   = document.getElementById('search-results-body');
    var searchUrl     = '{{ route('dashboard.cases.search-vehicle') }}';
    var debounceTimer = null;

    function formatAmount(amt) {
        if (!amt || amt <= 0) return '—';
        return '₨ ' + Number(amt).toLocaleString('en-PK', { maximumFractionDigits: 0 });
    }

    function statusBadge(status) {
        var color = status === 'open' ? 'bg-label-success' : 'bg-label-danger';
        return '<span class="badge ' + color + '">' + status.charAt(0).toUpperCase() + status.slice(1) + '</span>';
    }

    function renderResults(cases) {
        resultsBody.innerHTML = '';

        if (cases.length === 0) {
            resultsBody.innerHTML = '<tr><td colspan="9" class="text-center text-secondary py-4">' +
                '<i class="ti ti-search-off ti-lg me-2"></i>No vehicle found.</td></tr>';
            return;
        }

        cases.forEach(function (c, i) {
            var actions = '';
            @can('update case')
            actions += '<a href="' + c.edit_url + '" class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1" title="Edit Case"><i class="ti ti-edit ti-md"></i></a>';
            @endcan
            @can('view case')
            actions += '<a href="' + c.show_url + '" class="btn btn-icon btn-text-info waves-effect waves-light rounded-pill me-1" title="View Case"><i class="ti ti-eye ti-md"></i></a>';
            @endcan
            if (c.customer_url) {
                actions += '<a href="' + c.customer_url + '" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill" title="View Customer Cases"><i class="ti ti-user ti-md"></i></a>';
            }

            resultsBody.innerHTML +=
                '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td class="fw-semibold">' + c.vehicle_no + '</td>' +
                '<td>' + c.customer_name + '</td>' +
                '<td>' + c.vendor_name + '</td>' +
                '<td>' + c.city + '</td>' +
                '<td>' + c.case_date + '</td>' +
                '<td>' + statusBadge(c.status) + '</td>' +
                '<td class="text-end fw-semibold">' + formatAmount(c.amount) + '</td>' +
                '<td class="d-flex">' + actions + '</td>' +
                '</tr>';
        });
    }

    function doSearch(q) {
        fetch(searchUrl + '?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            renderResults(data);
            customerWrap.style.display = 'none';
            resultsWrap.style.display  = '';
        })
        .catch(function () {
            resultsBody.innerHTML = '<tr><td colspan="9" class="text-center text-danger py-3">Search failed. Please try again.</td></tr>';
            customerWrap.style.display = 'none';
            resultsWrap.style.display  = '';
        });
    }

    function clearSearch() {
        searchInput.value        = '';
        clearBtn.style.display   = 'none';
        resultsWrap.style.display = 'none';
        customerWrap.style.display = '';
        resultsBody.innerHTML    = '';
    }

    searchInput.addEventListener('input', function () {
        var q = this.value.trim();
        clearBtn.style.display = q.length > 0 ? 'inline' : 'none';
        clearTimeout(debounceTimer);
        if (q.length === 0) { clearSearch(); return; }
        debounceTimer = setTimeout(function () { doSearch(q); }, 350);
    });

    clearBtn.addEventListener('click', clearSearch);
})();
</script>
@endsection
