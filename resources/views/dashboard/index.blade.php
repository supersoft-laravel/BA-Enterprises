@extends('layouts.master')

@section('title', 'Dashboard')

@section('css')
<style>
    /* ===== DESIGN TOKENS ===== */
    :root {
        --primary: #1d4ed8;
        --primary-light: #3b82f6;
        --primary-bg: #eff6ff;
        --success: #059669;
        --success-bg: #ecfdf5;
        --warning: #d97706;
        --warning-bg: #fffbeb;
        --danger: #dc2626;
        --danger-bg: #fff1f2;
        --surface: #ffffff;
        --surface-2: #f8fafc;
        --border: #e2e8f0;
        --border-strong: #cbd5e1;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 30px rgba(0,0,0,0.10), 0 4px 8px rgba(0,0,0,0.05);
        --radius-sm: 0.5rem;
        --radius-md: 0.75rem;
        --radius-lg: 1rem;
        --radius-xl: 1.25rem;
        --radius-pill: 9999px;
    }

    /* ===== BASE ===== */
    body { color: var(--text-primary); background: var(--surface-2); font-size: 0.9rem; }

    /* ===== UTILITIES ===== */
    .hidden { display: none !important; }
    .text-primary-clr { color: var(--primary); }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.3rem 0.85rem; border-radius: var(--radius-pill);
        font-size: 0.75rem; font-weight: 600; letter-spacing: 0.02em;
    }
    .card-surface {
        background: var(--surface); border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md); padding: 1.5rem; margin-bottom: 1.25rem;
        border: 1px solid var(--border);
    }
    .section-title {
        font-size: 1rem; font-weight: 700; color: var(--text-primary);
        display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;
    }
    .section-title .icon-wrap {
        width: 28px; height: 28px; border-radius: var(--radius-sm);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
    }
    .divider { border: none; border-top: 1px solid var(--border); margin: 1rem 0; }

    /* ===== CITY CARDS ===== */
    .city-card {
        background: var(--surface); border: 1.5px solid var(--border);
        border-radius: var(--radius-lg); padding: 1.25rem 0.75rem;
        text-align: center; cursor: pointer;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        box-shadow: var(--shadow-sm);
    }
    .city-card:hover {
        transform: translateY(-4px); box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
    }
    .city-card .city-icon {
        width: 48px; height: 48px; border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; margin: 0 auto 0.6rem;
    }
    .city-card h5 { font-size: 0.85rem; font-weight: 700; margin: 0; color: var(--text-primary); }

    /* city color variants */
    .city-karachi   .city-icon { background: #dcfce7; color: #15803d; }
    .city-lasbella  .city-icon { background: #dbeafe; color: #1d4ed8; }
    .city-quetta    .city-icon { background: #fef3c7; color: #b45309; }
    .city-peshawar  .city-icon { background: #e0e7ff; color: #4338ca; }
    .city-gilgit    .city-icon { background: #cffafe; color: #0e7490; }
    .city-punjab    .city-icon { background: #d9f99d; color: #3f6212; }
    .city-other     .city-icon { background: #f1f5f9; color: #475569; }

    /* ===== SERVICE CARDS ===== */
    .service-card {
        background: var(--surface); border: 1.5px solid var(--border);
        border-radius: var(--radius-lg); padding: 1.25rem 1rem;
        text-align: center; cursor: pointer; height: 100%;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease, background 0.15s ease;
        box-shadow: var(--shadow-sm);
    }
    .service-card:hover {
        transform: scale(1.03); box-shadow: var(--shadow-md);
        background: var(--primary-bg); border-color: var(--primary-light);
    }
    .service-card .svc-icon {
        width: 44px; height: 44px; border-radius: var(--radius-md);
        background: var(--primary-bg); color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; margin: 0 auto 0.65rem;
    }
    .service-card:hover .svc-icon { background: var(--primary); color: #fff; }
    .service-card h5 { font-size: 0.82rem; font-weight: 700; margin: 0; color: var(--text-primary); }

    /* ===== PENDING CARDS ===== */
    .pending-payment-item {
        background: var(--surface); border: 1px solid #fee2e2;
        border-left: 3px solid var(--danger); border-radius: var(--radius-md);
        padding: 0.65rem 0.9rem; margin-bottom: 0.5rem;
        display: flex; justify-content: space-between; align-items: center;
        transition: box-shadow 0.15s;
    }
    .pending-payment-item:hover { box-shadow: var(--shadow-sm); }
    .pending-case-item {
        background: var(--surface); border: 1px solid #fde68a;
        border-left: 3px solid var(--warning); border-radius: var(--radius-md);
        padding: 0.65rem 0.9rem; margin-bottom: 0.5rem;
        display: flex; gap: 0.6rem; align-items: flex-start;
        transition: box-shadow 0.15s;
    }
    .pending-case-item:hover { box-shadow: var(--shadow-sm); }
    .scroll-list { max-height: 15rem; overflow-y: auto; padding-right: 2px; }
    .scroll-list::-webkit-scrollbar { width: 4px; }
    .scroll-list::-webkit-scrollbar-track { background: transparent; }
    .scroll-list::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }

    /* ===== STEP INDICATORS ===== */
    .step-wrap { display: flex; gap: 0.4rem; align-items: center; }
    .step-chip {
        font-size: 0.7rem; font-weight: 600; padding: 0.25rem 0.7rem;
        border-radius: var(--radius-pill); background: var(--border); color: var(--text-secondary);
        display: inline-flex; align-items: center; gap: 0.25rem;
    }
    .step-chip.done  { background: #d1fae5; color: #065f46; }
    .step-chip.active { background: var(--primary); color: #fff; }

    /* ===== FORM NAV HEADER ===== */
    .nav-header {
        background: var(--surface); border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm); padding: 0.9rem 1.25rem;
        margin-bottom: 1.25rem; display: flex; flex-wrap: wrap;
        justify-content: space-between; align-items: center; gap: 0.75rem;
        border: 1px solid var(--border);
    }

    /* ===== FORM LABELS & INPUTS ===== */
    .form-label-sm { font-size: 0.78rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; display: block; }
    .form-control, .form-select {
        border-color: var(--border); border-radius: var(--radius-sm);
        font-size: 0.85rem; padding: 0.45rem 0.7rem;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
    }
    .form-control[readonly] { background: var(--surface-2); color: var(--text-secondary); }
    .required-dot::after { content: " *"; color: var(--danger); font-weight: 700; }

    /* ===== SERVICE DETAIL SECTIONS ===== */
    .service-detail-block {
        background: #fffdf0; border: 1px solid #fde68a;
        border-left: 4px solid var(--warning); border-radius: var(--radius-lg);
        padding: 1.1rem 1.25rem; margin-bottom: 0.9rem;
    }
    .service-detail-block .block-title {
        font-size: 0.85rem; font-weight: 700; color: #92400e;
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem;
    }

    /* ===== SERVICES TABLE ===== */
    #finalServicesTable thead th {
        background: var(--surface-2); font-size: 0.75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary);
        padding: 0.6rem 0.75rem; border-bottom: 1px solid var(--border);
    }
    #finalServicesTable tbody td { padding: 0.6rem 0.75rem; vertical-align: middle; }
    #finalServicesTable tbody tr:hover { background: var(--surface-2); }
    .amount-input { width: 120px !important; }

    /* ===== BUTTONS ===== */
    .btn-remove-svc {
        background: #fef2f2; color: var(--danger); border: 1px solid #fecaca;
        border-radius: var(--radius-pill); padding: 0.25rem 0.7rem;
        font-size: 0.75rem; font-weight: 600; cursor: pointer;
        transition: background 0.12s; line-height: 1.4;
    }
    .btn-remove-svc:hover { background: #fee2e2; }

    /* ===== TOTALS ===== */
    .totals-card {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd; border-radius: var(--radius-xl);
        padding: 1.25rem; margin-bottom: 1.25rem;
    }
    .total-field label { font-size: 0.78rem; font-weight: 700; color: var(--text-secondary); }
    .total-amount { font-size: 1.05rem; font-weight: 800; }

    /* ===== MISC ===== */
    .empty-state { padding: 1.5rem; text-align: center; color: var(--text-muted); font-size: 0.82rem; }
    .empty-state i { display: block; font-size: 1.5rem; margin-bottom: 0.4rem; opacity: 0.4; }
</style>
@endsection

@section('content')

<!-- =================== SCREEN 1: DASHBOARD =================== -->
<div id="screen1Dashboard" class="container py-3">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="fw-bold mb-0" style="font-size:1.6rem; color:var(--text-primary);">{{\App\Helpers\Helper::getCompanyName()}}</h1>
            <p class="text-secondary small mb-0 mt-1">Permits · Taxes · Insurance · Vehicle Services — Pakistan</p>
        </div>
        <span class="badge-pill" style="background:var(--primary-bg);color:var(--primary);border:1px solid #bfdbfe;">
            <i class="fas fa-truck"></i> Transport Management
        </span>
    </div>

    <!-- City Grid -->
    <div class="card-surface">
        <div class="section-title">
            <span class="icon-wrap" style="background:#fef3c7;color:#b45309;"><i class="fas fa-map-marker-alt"></i></span>
            Select a City to Begin
        </div>
        <div class="row g-3">
            @php
            $cities = [
                ['name'=>'Karachi',  'cls'=>'city-karachi',  'icon'=>'fa-city'],
                ['name'=>'Lasbella', 'cls'=>'city-lasbella', 'icon'=>'fa-map-pin'],
                ['name'=>'Quetta',   'cls'=>'city-quetta',   'icon'=>'fa-mountain'],
                ['name'=>'Peshawar', 'cls'=>'city-peshawar', 'icon'=>'fa-landmark'],
                ['name'=>'Gilgit',   'cls'=>'city-gilgit',   'icon'=>'fa-mountain'],
                ['name'=>'Punjab',   'cls'=>'city-punjab',   'icon'=>'fa-seedling'],
                ['name'=>'Other',    'cls'=>'city-other',    'icon'=>'fa-globe'],
            ];
            @endphp
            @foreach($cities as $c)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="city-card {{ $c['cls'] }}" data-city="{{ $c['name'] }}">
                    <div class="city-icon"><i class="fas {{ $c['icon'] }}"></i></div>
                    <h5>{{ $c['name'] }}</h5>
                </div>
            </div>
            @endforeach
        </div>
        <p class="text-center small mt-3 mb-0" style="color:var(--text-muted);">👉 Click any city to proceed to service selection</p>
    </div>

    <!-- Pending Filter -->
    <div class="card-surface">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div class="section-title mb-0">
                <span class="icon-wrap" style="background:var(--primary-bg);color:var(--primary);"><i class="fas fa-filter"></i></span>
                Pending Items
            </div>
            <div class="d-flex flex-wrap gap-2">
                <select id="filterCity" class="form-select form-select-sm" style="width:auto;border-radius:var(--radius-pill);">
                    <option value="all">All Cities</option>
                    <option value="Karachi">Karachi</option><option value="Lasbella">Lasbella</option>
                    <option value="Quetta">Quetta</option><option value="Peshawar">Peshawar</option>
                    <option value="Gilgit">Gilgit</option><option value="Punjab">Punjab</option>
                    <option value="Other">Other</option>
                </select>
                <select id="filterService" class="form-select form-select-sm" style="width:auto;border-radius:var(--radius-pill);">
                    <option value="all">All Services</option>
                    <option>Transfer</option><option>Alteration</option><option>Route Permit</option>
                    <option>FC</option><option>Insurance</option><option>Tax</option>
                    <option>File Return</option><option>Others</option>
                </select>
                <button id="applyFilterBtn" class="btn btn-primary btn-sm" style="border-radius:var(--radius-pill);padding:0.3rem 1rem;">
                    <i class="fas fa-search me-1"></i> Apply
                </button>
            </div>
        </div>
        <hr class="divider mt-0">
        <div class="row g-3">
            <div class="col-md-6">
                <p class="form-label-sm text-danger mb-2"><i class="fas fa-rupee-sign me-1"></i> Pending Payments</p>
                <div id="filteredPaymentsList" class="scroll-list"></div>
            </div>
            <div class="col-md-6">
                <p class="form-label-sm mb-2" style="color:var(--warning);"><i class="fas fa-folder-open me-1"></i> Pending Cases</p>
                <div id="filteredCasesList" class="scroll-list"></div>
            </div>
        </div>
    </div>
</div>

<!-- =================== SCREEN 2: SERVICE SELECTION =================== -->
<div id="screen2Service" class="container py-3 hidden">

    <div class="nav-header">
        <button id="backToCitiesBtn" class="btn btn-light btn-sm" style="border-radius:var(--radius-pill);border:1px solid var(--border);">
            <i class="fas fa-arrow-left me-1"></i> Back
        </button>
        <span class="badge-pill" style="background:var(--success-bg);color:var(--success);border:1px solid #a7f3d0;">
            <i class="fas fa-city"></i> <span id="selectedCityName">—</span>
        </span>
        <div class="step-wrap">
            <span class="step-chip done"><i class="fas fa-check"></i> City</span>
            <span class="step-chip active">Service</span>
        </div>
    </div>

    <div class="card-surface">
        <div class="section-title">
            <span class="icon-wrap" style="background:var(--primary-bg);color:var(--primary);"><i class="fas fa-concierge-bell"></i></span>
            Select a Service
        </div>
        <div class="row g-3" id="servicesGrid"></div>
    </div>

    <!-- Pending for city -->
    <div class="card-surface" style="border-left:4px solid var(--warning);">
        <div class="section-title mb-2">
            <span class="icon-wrap" style="background:var(--warning-bg);color:var(--warning);"><i class="fas fa-clock"></i></span>
            Pending Cases — <span id="pendingCityName" class="ms-1" style="color:var(--warning);">—</span>
        </div>
        <div id="cityPendingCasesList" class="scroll-list small"></div>
    </div>
</div>

<!-- =================== SCREEN 3: ENTRY FORM =================== -->
<div id="screen3Form" class="container py-3 hidden">

    <div class="nav-header">
        <button id="backToServiceScreenBtn" class="btn btn-light btn-sm" style="border-radius:var(--radius-pill);border:1px solid var(--border);">
            <i class="fas fa-chevron-left me-1"></i> Back
        </button>
        <span class="badge-pill" style="background:var(--success-bg);color:var(--success);border:1px solid #a7f3d0;">
            <i class="fas fa-map-marker-alt"></i> <span id="formCityLabel">—</span>
        </span>
        <div class="step-wrap">
            <span class="step-chip done"><i class="fas fa-check"></i> City</span>
            <span class="step-chip done"><i class="fas fa-check"></i> Service</span>
            <span class="step-chip active">Entry Form</span>
        </div>
    </div>

    <!-- Vehicle & Party -->
    <div class="card-surface">
        <div class="section-title border-bottom pb-2 mb-3">
            <span class="icon-wrap" style="background:var(--primary-bg);color:var(--primary);"><i class="fas fa-truck"></i></span>
            Vehicle &amp; Party Details
        </div>
        <div id="dynamicCommonFieldsContainer" class="row g-3"></div>
    </div>

    <!-- Service Detail Sections -->
    <div id="dynamicDetailsContainer"></div>

    <!-- Services Table -->
    <div class="card-surface">
        <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-2 mb-3 gap-2">
            <div class="section-title mb-0">
                <span class="icon-wrap" style="background:var(--primary-bg);color:var(--primary);"><i class="fas fa-list-ul"></i></span>
                Services &amp; Charges
            </div>
            <button id="addMoreServiceBtn" class="btn btn-primary btn-sm" style="border-radius:var(--radius-pill);padding:0.3rem 1rem;">
                <i class="fas fa-plus me-1"></i> Add Service
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-2" id="finalServicesTable">
                <thead>
                    <tr>
                        <th style="width:45%">Service Type</th>
                        <th style="width:25%">Amount (₨)</th>
                        <th style="width:30%">Action</th>
                    </tr>
                </thead>
                <tbody id="finalServicesTableBody"></tbody>
            </table>
        </div>
        <p class="small mb-0" style="color:var(--text-muted);">
            <i class="fas fa-info-circle me-1"></i> Transfer, Alteration &amp; File Return share the same party details across all rows.
        </p>
    </div>

    <!-- Totals -->
    <div class="totals-card">
        <div class="row g-3 mb-3">
            <div class="col-md-4 total-field">
                <label class="required-dot mb-1">Total Amount (₨)</label>
                <input type="number" id="finalTotalAmount" readonly class="form-control total-amount text-primary" style="border-color:#bae6fd;">
            </div>
            <div class="col-md-4 total-field">
                <label class="mb-1">Received Amount (₨)</label>
                <input type="number" id="finalReceivedAmount" value="0" class="form-control total-amount" style="color:var(--success);">
            </div>
            <div class="col-md-4 total-field">
                <label class="mb-1">Remaining Amount (₨)</label>
                <input type="number" id="finalRemainingAmount" readonly class="form-control total-amount" style="color:var(--danger);border-color:#fecaca;">
            </div>
        </div>
        <div class="text-end">
            <button id="finalSaveRecordBtn" class="btn btn-success px-4 py-2 fw-bold" style="border-radius:var(--radius-pill);">
                <i class="fas fa-save me-2"></i> Save Record
            </button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
(function () {
    'use strict';

    // =========================================================
    // CONSTANTS & CONFIG
    // =========================================================
    const ALL_SERVICES = ['Transfer', 'Alteration', 'Route Permit', 'FC', 'Insurance', 'Tax', 'File Return', 'Others'];
    const TRANSFER_LIKE = new Set(['Transfer', 'Alteration', 'File Return']);
    const SVC_ICONS = {
        Transfer: 'fa-exchange-alt', Alteration: 'fa-edit', 'Route Permit': 'fa-road',
        FC: 'fa-truck', Insurance: 'fa-shield-alt', Tax: 'fa-money-bill-wave',
        'File Return': 'fa-folder-open', Others: 'fa-ellipsis-h'
    };

    // =========================================================
    // MOCK DATA
    // =========================================================
    // Global variables that will be updated dynamically
    let pendingPaymentsDB = [];
    let pendingCasesDB = [];

    // Function to fetch latest pending items from server
    async function fetchPendingItems() {
        try {
            const response = await fetch('/dashboard/pending-items');
            const data = await response.json();

            pendingPaymentsDB = data.payments;
            pendingCasesDB = data.cases;

            // Re-render the filtered items with new data
            renderFilteredItems();

            return { payments: pendingPaymentsDB, cases: pendingCasesDB };
        } catch (error) {
            console.error('Failed to fetch pending items:', error);
            return { payments: [], cases: [] };
        }
    }

    // =========================================================
    // STATE
    // =========================================================
    let currentCity = '';
    let currentServicesRows = [];
    let nextId = 1;
    let syncTimeout = null;

    // Persisted vehicle fields — never wiped on re-render
    const vehicleState = {
        vehicleNo:'', vehicleMake:'', vehicleModel:'',
        engineNo:'', chassisNo:'', partyName:'',
        partyMobile:'', date:'', comment:''
    };

    // =========================================================
    // HELPERS
    // =========================================================
    const $ = id => document.getElementById(id);
    const esc = s => s ? String(s).replace(/[&<>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[m]||m)) : '';
    const normalizeCity = c => (c === 'Lahore') ? 'Punjab' : c;

    function readVehicleFields() {
        Object.keys(vehicleState).forEach(k => {
            const el = $('comm_' + k);
            if (el) vehicleState[k] = el.value;
        });
    }

    function writeVehicleFields() {
        Object.keys(vehicleState).forEach(k => {
            const el = $('comm_' + k);
            if (!el) return;
            if (k === 'date' && !vehicleState[k]) {
                el.value = new Date().toISOString().split('T')[0];
            } else {
                el.value = vehicleState[k];
            }
        });
    }

    // =========================================================
    // CHECK IF ANY TRANSFER-LIKE SERVICE EXISTS
    // =========================================================
    function hasTransferLikeService() {
        return currentServicesRows.some(row => TRANSFER_LIKE.has(row.serviceType));
    }
    const caseRoute = "{{ route('dashboard.cases.show', ':id') }}";
    const billingRoute = "{{ route('dashboard.payments.create') }}";

    // =========================================================
    // PENDING ITEMS RENDERING
    // =========================================================
    function renderFilteredItems() {
        const city = $('filterCity').value;
        const serv = $('filterService').value;
        const matchCity = p => city === 'all' || normalizeCity(p.city) === city;
        const matchServ = p => serv === 'all' || p.service === serv;

        const payments = pendingPaymentsDB.filter(p => matchCity(p) && matchServ(p));
        const cases    = pendingCasesDB.filter(c    => matchCity(c) && matchServ(c));

        $('filteredPaymentsList').innerHTML = payments.length
            ? payments.map(p => `
                <a href="${billingRoute}?billing_id=${p.billing_id}" class="text-decoration-none">
                    <div class="pending-payment-item">
                        <div>
                            <span class="fw-semibold" style="font-size:0.82rem;">${esc(p.city)} — ${esc(p.service)}</span><br>
                            <span class="text-secondary" style="font-size:0.75rem;">${esc(p.party)} &bull; ${esc(p.vehicle)}</span>
                        </div>
                        <span class="fw-bold text-danger" style="font-size:0.85rem;">₨ ${p.amount.toLocaleString()}</span>
                    </div>
                </a>`).join('')
            : '<div class="empty-state"><i class="fas fa-check-circle"></i>No pending payments</div>';

        $('filteredCasesList').innerHTML = cases.length
            ? cases.map(c => `
            <a href="${caseRoute.replace(':id', c.case_id)}" class="text-decoration-none">
                <div class="pending-case-item">
                    <i class="fas fa-folder-open mt-1" style="color:var(--warning);font-size:0.85rem;"></i>
                    <div>
                        <span class="fw-semibold" style="font-size:0.82rem;">${esc(c.city)} — ${esc(c.service)}</span><br>
                        <span class="fw-semibold" style="font-size:0.82rem;">${esc(c.vehicle_no)} — ${esc(c.party_name)}</span><br>
                        <span class="text-secondary" style="font-size:0.75rem;">${esc(c.title)} · ${esc(c.desc)}</span>
                    </div>
                </div>
            </a>
            `).join('')
            : '<div class="empty-state"><i class="fas fa-check-circle"></i>No pending cases</div>';
    }

    // =========================================================
    // CITY PENDING CASES (screen 2)
    // =========================================================
    function loadCityPendingCases(city) {
        const filtered = pendingCasesDB.filter(c => normalizeCity(c.city) === city);
        $('cityPendingCasesList').innerHTML = filtered.length
            ? filtered.map(c => `
                <a href="${caseRoute.replace(':id', c.case_id)}" class="text-decoration-none">
                    <div class="pending-case-item">
                        <i class="fas fa-exclamation-circle mt-1" style="color:var(--warning);"></i>
                        <div>
                            <span class="fw-semibold">${esc(c.service)} — ${esc(c.title)}</span><br>
                            <span class="fw-semibold">${esc(c.vehicle_no)} — ${esc(c.party_name)}</span><br>
                            <span class="text-secondary">${esc(c.desc)}</span>
                        </div>
                    </div>
                </a>`).join('')
            : '<div class="empty-state"><i class="fas fa-check-circle"></i>No pending cases for this city</div>';
        $('pendingCityName').textContent = city;
    }

    // =========================================================
    // DETAIL FIELDS HTML PER SERVICE
    // =========================================================
    function detailsHTML(serviceType, data = {}) {
        const v = (key, placeholder) => `value="${esc(data[key] || '')}"${placeholder ? ` placeholder="${placeholder}"` : ''}`;
        const t = (key, rows, placeholder) => `${esc(data[key] || '')}`;

        if (TRANSFER_LIKE.has(serviceType)) return `
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label-sm">From Name</label><input class="form-control detail-fromName" ${v('fromName')}></div>
                <div class="col-md-6"><label class="form-label-sm">From S/O</label><input class="form-control detail-fromSo" ${v('fromSo')}></div>
                <div class="col-md-6"><label class="form-label-sm">From NIC No</label><input class="form-control detail-fromNic" ${v('fromNic')} placeholder="XXXXX-XXXXXXX-X"></div>
                <div class="col-md-6"><label class="form-label-sm">To Name</label><input class="form-control detail-toName" ${v('toName')}></div>
                <div class="col-md-6"><label class="form-label-sm">To S/O</label><input class="form-control detail-toSo" ${v('toSo')}></div>
                <div class="col-md-6"><label class="form-label-sm">To NIC No</label><input class="form-control detail-toNic" ${v('toNic')} placeholder="XXXXX-XXXXXXX-X"></div>
            </div>`;

        if (serviceType === 'Route Permit') return `
            <div class="row g-2">
                <div class="col-12"><label class="form-label-sm">Route Details</label><textarea class="form-control detail-details" rows="2">${t('details')}</textarea></div>
                <div class="col-md-6">
                    <label class="form-label-sm">RTA / PTA</label>
                    <select class="form-select detail-rtaPta">
                        <option value="RTA" ${data.rtaPta === 'RTA' ? 'selected' : ''}>RTA</option>
                        <option value="PTA" ${data.rtaPta === 'PTA' ? 'selected' : ''}>PTA</option>
                        <option value="Other" ${data.rtaPta === 'Other' ? 'selected' : ''}>Other</option>
                    </select>
                </div>
            </div>`;

        if (serviceType === 'FC') return `
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label-sm">Truck / Trailer</label>
                    <select class="form-select detail-truckType">
                        <option ${data.truckType==='Truck'?'selected':''}>Truck</option>
                        <option ${data.truckType==='Trailer'?'selected':''}>Trailer</option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label-sm">FC Details</label><textarea class="form-control detail-fcDetails" rows="2">${t('fcDetails')}</textarea></div>
            </div>`;

        if (serviceType === 'Insurance') return `
            <div><label class="form-label-sm">Remarks</label><textarea class="form-control detail-remarks" rows="3">${t('remarks')}</textarea></div>`;

        if (serviceType === 'Tax') return `
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label-sm">From (Period)</label><input class="form-control detail-fromPeriod" ${v('fromPeriod','e.g. 01-Jan-2025')}></div>
                <div class="col-md-6"><label class="form-label-sm">Upto (Period)</label><input class="form-control detail-upto" ${v('upto','e.g. 31-Dec-2025')}></div>
            </div>`;

        if (serviceType === 'Others') return `
            <div><label class="form-label-sm">Other Details</label><textarea class="form-control detail-otherDetails" rows="3" placeholder="Enter extra details">${t('otherDetails')}</textarea></div>`;

        return '<p class="small text-secondary mb-0">No additional fields required.</p>';
    }

    // =========================================================
    // READ DETAIL DATA FROM DOM INTO ROW
    // =========================================================
    function syncRowDetailsFromDOM(rowId) {
        const row = currentServicesRows.find(r => r.id === rowId);
        if (!row) return;
        const section = $(`details-section-${rowId}`);
        if (!section) return;
        const w = section.querySelector('.details-fields-wrapper');
        if (!w) return;

        const st = row.serviceType;
        const g = cls => w.querySelector(`.${cls}`)?.value || '';

        if (TRANSFER_LIKE.has(st)) {
            row.detailsData = {
                fromName:g('detail-fromName'),
                fromSo:g('detail-fromSo'),
                fromNic:g('detail-fromNic'),
                toName:g('detail-toName'),
                toSo:g('detail-toSo'),
                toNic:g('detail-toNic')
            };
        } else if (st==='Route Permit') {
            row.detailsData = {
                details:g('detail-details'),
                rtaPta:g('detail-rtaPta')
            };
        } else if (st==='FC') {
            row.detailsData = {
                truckType:g('detail-truckType')||'Truck',
                fcDetails:g('detail-fcDetails')
            };
        } else if (st==='Insurance') {
            row.detailsData = {
                remarks:g('detail-remarks')
            };
        } else if (st==='Tax') {
            row.detailsData = {
                fromPeriod:g('detail-fromPeriod'),
                upto:g('detail-upto')
            };
        } else if (st==='Others') {
            row.detailsData = {
                otherDetails:g('detail-otherDetails')
            };
        }
    }

    // =========================================================
    // TRANSFER DATA SYNC (master → all transfer-like rows)
    // =========================================================
    function getMasterTransferData() {
        const rows = currentServicesRows.filter(r => TRANSFER_LIKE.has(r.serviceType));
        for (const row of rows) {
            if (row.detailsData && (row.detailsData.fromName || row.detailsData.fromNic || row.detailsData.toName)) {
                return { ...row.detailsData };
            }
        }
        return { fromName:'', fromSo:'', fromNic:'', toName:'', toSo:'', toNic:'' };
    }

    function syncAllTransferRows() {
        const rows = currentServicesRows.filter(r => TRANSFER_LIKE.has(r.serviceType));
        if (rows.length < 2) return;

        // Read freshest data from DOM first
        rows.forEach(r => syncRowDetailsFromDOM(r.id));
        const master = getMasterTransferData();

        rows.forEach(row => {
            // Only update if this row doesn't have data or if we're syncing from another row
            const hasData = row.detailsData && (row.detailsData.fromName || row.detailsData.fromNic || row.detailsData.toName);
            if (!hasData || row !== rows[0]) {
                row.detailsData = { ...master };
                const section = $(`details-section-${row.id}`);
                if (!section) return;
                const w = section.querySelector('.details-fields-wrapper');
                if (!w) return;
                const fields = { fromName:'detail-fromName', fromSo:'detail-fromSo', fromNic:'detail-fromNic', toName:'detail-toName', toSo:'detail-toSo', toNic:'detail-toNic' };
                Object.entries(fields).forEach(([key, cls]) => {
                    const el = w.querySelector('.' + cls);
                    if (el && el !== document.activeElement) el.value = master[key] || '';
                });
            }
        });
    }

    // =========================================================
    // RENDER: DETAIL SECTIONS
    // =========================================================
    function renderDetailSections() {
        const container = $('dynamicDetailsContainer');
        if (!container) return;
        container.innerHTML = '';

        currentServicesRows.forEach(row => {
            const div = document.createElement('div');
            div.className = 'service-detail-block';
            div.id = `details-section-${row.id}`;
            div.innerHTML = `
                <div class="block-title">
                    <span><i class="fas fa-file-alt me-2"></i>${esc(row.serviceType)} — Details</span>
                    <button class="btn-remove-svc remove-detail-btn" data-id="${row.id}">
                        <i class="fas fa-times me-1"></i>Remove
                    </button>
                </div>
                <div class="details-fields-wrapper">${detailsHTML(row.serviceType, row.detailsData || {})}</div>`;
            container.appendChild(div);

            const w = div.querySelector('.details-fields-wrapper');
            const isTransfer = TRANSFER_LIKE.has(row.serviceType);
            w.querySelectorAll('input, textarea, select').forEach(el => {
                el.addEventListener('input', () => {
                    if (isTransfer) {
                        clearTimeout(syncTimeout);
                        syncTimeout = setTimeout(() => {
                            syncRowDetailsFromDOM(row.id);
                            syncAllTransferRows();
                        }, 30);
                    } else {
                        syncRowDetailsFromDOM(row.id);
                    }
                });
            });
        });

        // Remove buttons
        container.querySelectorAll('.remove-detail-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (currentServicesRows.length === 1) { alert('At least one service is required.'); return; }
                const id = parseInt(this.dataset.id);
                readVehicleFields();
                currentServicesRows = currentServicesRows.filter(r => r.id !== id);
                renderServicesTable();
                renderDetailSections();
                updateCommonFields();
                updateTotals();
                writeVehicleFields();
            });
        });
    }

    // =========================================================
    // RENDER: SERVICES TABLE
    // =========================================================
    function renderServicesTable() {
        const tbody = $('finalServicesTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        currentServicesRows.forEach(row => {
            const tr = document.createElement('tr');

            // Service select
            const tdType = document.createElement('td');
            const sel = document.createElement('select');
            sel.className = 'form-select form-select-sm';
            ALL_SERVICES.forEach(opt => {
                const o = document.createElement('option');
                o.value = o.textContent = opt;
                if (row.serviceType === opt) o.selected = true;
                sel.appendChild(o);
            });
            sel.addEventListener('change', e => {
                const newType = e.target.value;
                const wasTransfer = TRANSFER_LIKE.has(row.serviceType);
                const isTransfer  = TRANSFER_LIKE.has(newType);
                row.serviceType = newType;

                // Preserve data if converting between similar types
                if (isTransfer && wasTransfer) {
                    // Keep existing transfer data
                    if (!row.detailsData) {
                        const master = getMasterTransferData();
                        row.detailsData = master;
                    }
                } else if (isTransfer && !wasTransfer) {
                    // Converting to transfer - get master data if exists
                    const master = getMasterTransferData();
                    row.detailsData = master;
                } else if (!isTransfer && wasTransfer) {
                    // Converting from transfer - keep some data if applicable
                    const oldData = row.detailsData || {};
                    row.detailsData = {};
                    // Try to map relevant fields
                    if (newType === 'Route Permit') {
                        row.detailsData = { details: `From: ${oldData.fromName || ''} To: ${oldData.toName || ''}`, rtaPta: 'RTA' };
                    } else if (newType === 'FC') {
                        row.detailsData = { truckType: 'Truck', fcDetails: `Transfer from ${oldData.fromName || ''}` };
                    }
                } else {
                    // Converting between non-transfer services
                    if (!row.detailsData) row.detailsData = {};
                }

                readVehicleFields();
                renderServicesTable();
                renderDetailSections();
                updateCommonFields();
                updateTotals();
                writeVehicleFields();
            });
            tdType.appendChild(sel);

            // Amount
            const tdAmt = document.createElement('td');
            const amtInput = document.createElement('input');
            amtInput.type = 'number'; amtInput.value = row.amount || 0;
            amtInput.className = 'form-control form-control-sm amount-input';
            amtInput.addEventListener('input', e => { row.amount = parseFloat(e.target.value) || 0; updateTotals(); });
            tdAmt.appendChild(amtInput);

            // Remove
            const tdAct = document.createElement('td');
            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn-remove-svc';
            removeBtn.innerHTML = '<i class="fas fa-trash-alt me-1"></i>Remove';
            removeBtn.addEventListener('click', () => {
                if (currentServicesRows.length === 1) { alert('At least one service is required.'); return; }
                readVehicleFields();
                currentServicesRows = currentServicesRows.filter(r => r.id !== row.id);
                renderServicesTable();
                renderDetailSections();
                updateCommonFields();
                updateTotals();
                writeVehicleFields();
            });
            tdAct.appendChild(removeBtn);

            tr.append(tdType, tdAmt, tdAct);
            tbody.appendChild(tr);
        });

        updateTotals();
    }

    // =========================================================
    // TOTALS
    // =========================================================
    function updateTotals() {
        const total    = currentServicesRows.reduce((s, r) => s + (parseFloat(r.amount) || 0), 0);
        const received = parseFloat($('finalReceivedAmount')?.value) || 0;
        if ($('finalTotalAmount'))     $('finalTotalAmount').value     = total.toFixed(2);
        if ($('finalRemainingAmount')) $('finalRemainingAmount').value = (total - received).toFixed(2);
    }

    // =========================================================
    // COMMON FIELDS (Vehicle & Party) — show/hide based on service type
    // =========================================================
    function updateCommonFields() {
        const hasTransfer = hasTransferLikeService();
        const container = $('dynamicCommonFieldsContainer');
        if (!container) return;

        // Store current values before re-rendering
        readVehicleFields();

        if (hasTransfer) {
            container.innerHTML = `
                <div class="col-md-6"><label class="form-label-sm required-dot">Vehicle No</label><input id="comm_vehicleNo" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Vehicle Make</label><input id="comm_vehicleMake" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Vehicle Model</label><input id="comm_vehicleModel" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Engine No</label><input id="comm_engineNo" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Chassis No</label><input id="comm_chassisNo" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Party Name</label><input id="comm_partyName" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Party Mobile No</label><input id="comm_partyMobile" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Date</label><input type="date" id="comm_date" class="form-control"></div>
                <div class="col-12"><label class="form-label-sm">Comment / Remarks</label><textarea id="comm_comment" rows="2" class="form-control" placeholder="Any additional comment…"></textarea></div>`;
        } else {
            container.innerHTML = `
                <div class="col-md-6"><label class="form-label-sm required-dot">Vehicle No</label><input id="comm_vehicleNo" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Party Name</label><input id="comm_partyName" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Party Mobile No</label><input id="comm_partyMobile" class="form-control"></div>
                <div class="col-md-6"><label class="form-label-sm">Date</label><input type="date" id="comm_date" class="form-control"></div>
                <div class="col-12"><label class="form-label-sm">Comment / Remarks</label><textarea id="comm_comment" rows="2" class="form-control" placeholder="Any additional comment…"></textarea></div>`;
        }

        // Restore values after re-rendering
        writeVehicleFields();

        // Add event listeners to save values on input
        Object.keys(vehicleState).forEach(k => {
            const el = $('comm_' + k);
            if (el) {
                el.addEventListener('input', () => readVehicleFields());
            }
        });
    }

    // =========================================================
    // ADD SERVICE ROW
    // =========================================================
    function addServiceRow(serviceType = 'Transfer') {
        readVehicleFields(); // persist before DOM changes
        const id  = nextId++;
        const row = { id, serviceType, amount: 0, detailsData: {} };

        if (TRANSFER_LIKE.has(serviceType)) {
            const master = getMasterTransferData();
            // Always use master data if available, otherwise empty object
            row.detailsData = (master && (master.fromName || master.fromNic || master.toName)) ? { ...master } : { fromName:'', fromSo:'', fromNic:'', toName:'', toSo:'', toNic:'' };
        } else if (serviceType === 'Route Permit') {
            row.detailsData = { details: '', rtaPta: 'RTA' };
        } else if (serviceType === 'FC') {
            row.detailsData = { truckType: 'Truck', fcDetails: '' };
        } else if (serviceType === 'Insurance') {
            row.detailsData = { remarks: '' };
        } else if (serviceType === 'Tax') {
            row.detailsData = { fromPeriod: '', upto: '' };
        } else if (serviceType === 'Others') {
            row.detailsData = { otherDetails: '' };
        }

        currentServicesRows.push(row);
        renderServicesTable();
        renderDetailSections();
        updateCommonFields();
        updateTotals();
        writeVehicleFields(); // restore after DOM changes
    }

    // =========================================================
    // COLLECT ALL FORM DATA FOR SAVE
    // =========================================================
    function collectFormData() {
        currentServicesRows.forEach(r => syncRowDetailsFromDOM(r.id));
        readVehicleFields();
        const common = {
            city:        currentCity,
            vehicleNo:   vehicleState.vehicleNo,
            vehicleMake: vehicleState.vehicleMake,
            vehicleModel:vehicleState.vehicleModel,
            engineNo:    vehicleState.engineNo,
            chassisNo:   vehicleState.chassisNo,
            partyName:   vehicleState.partyName,
            partyMobile: vehicleState.partyMobile,
            date:        vehicleState.date,
            comment:     vehicleState.comment
        };
        const services = currentServicesRows.map(r => ({ id:r.id, serviceType:r.serviceType, amount:r.amount, details:r.detailsData || {} }));
        const totals   = { totalAmount:parseFloat($('finalTotalAmount').value)||0, receivedAmount:parseFloat($('finalReceivedAmount').value)||0, remainingAmount:parseFloat($('finalRemainingAmount').value)||0 };
        return { common, services, totals, submittedAt:new Date().toISOString() };
    }

    // =========================================================
    // SCREEN NAVIGATION
    // =========================================================
    function showScreen(id) {
        ['screen1Dashboard','screen2Service','screen3Form'].forEach(s => $s => $s?.classList.add('hidden'));
        document.querySelectorAll('#screen1Dashboard,#screen2Service,#screen3Form').forEach(el => el.classList.add('hidden'));
        $(id)?.classList.remove('hidden');
    }

    function showScreen2(city) {
        currentCity = city;
        $('selectedCityName').textContent = city;
        loadCityPendingCases(city);

        const grid = $('servicesGrid');
        grid.innerHTML = '';
        ALL_SERVICES.forEach(serv => {
            const col  = document.createElement('div');
            col.className = 'col-sm-6 col-md-4 col-lg-3';
            const card = document.createElement('div');
            card.className = 'service-card';
            card.innerHTML = `<div class="svc-icon"><i class="fas ${SVC_ICONS[serv] || 'fa-cog'}"></i></div><h5>${esc(serv)}</h5>`;
            card.addEventListener('click', () => initFormWithService(serv));
            col.appendChild(card);
            grid.appendChild(col);
        });

        showScreen('screen2Service');
    }

    function initFormWithService(serviceType) {
        currentServicesRows = [];
        // Reset vehicle state but preserve any existing values if needed
        Object.keys(vehicleState).forEach(key => {
            vehicleState[key] = '';
        });
        vehicleState.date = '';

        const row = { id: nextId++, serviceType, amount: 0, detailsData: {} };

        // Initialize details data based on service type
        if (TRANSFER_LIKE.has(serviceType)) {
            row.detailsData = { fromName:'', fromSo:'', fromNic:'', toName:'', toSo:'', toNic:'' };
        } else if (serviceType === 'Route Permit') {
            row.detailsData = { details: '', rtaPta: 'RTA' };
        } else if (serviceType === 'FC') {
            row.detailsData = { truckType: 'Truck', fcDetails: '' };
        } else if (serviceType === 'Insurance') {
            row.detailsData = { remarks: '' };
        } else if (serviceType === 'Tax') {
            row.detailsData = { fromPeriod: '', upto: '' };
        } else if (serviceType === 'Others') {
            row.detailsData = { otherDetails: '' };
        }

        currentServicesRows.push(row);

        renderServicesTable();
        renderDetailSections();
        updateCommonFields();
        $('formCityLabel').textContent = currentCity;
        $('finalReceivedAmount').value = '0';
        updateTotals();
        showScreen('screen3Form');
    }

    // =========================================================
    // EVENT LISTENERS
    // =========================================================
    document.querySelectorAll('.city-card').forEach(card => {
        card.addEventListener('click', () => showScreen2(card.dataset.city));
    });

    $('backToCitiesBtn').addEventListener('click', () => { showScreen('screen1Dashboard'); renderFilteredItems(); });
    $('backToServiceScreenBtn').addEventListener('click', () => showScreen2(currentCity));

    $('addMoreServiceBtn').addEventListener('click', () => {
        readVehicleFields();
        // Default to the first service type or Transfer if none exists
        const defaultService = currentServicesRows[0]?.serviceType || 'Transfer';
        addServiceRow(defaultService);
    });

        $('finalReceivedAmount').addEventListener('input', updateTotals);
        $('applyFilterBtn').addEventListener('click', async () => {
            // Fetch fresh data before filtering
            await fetchPendingItems();
            renderFilteredItems();
        });



        $('finalSaveRecordBtn').addEventListener('click', async () => {
            // Validate
            if (!$('comm_vehicleNo')?.value.trim()) {
                alert('Please fill in the Vehicle Number.');
                return;
            }

            // Collect data
            const data = collectFormData();

            // Log to console for inspection
            console.log('=== FORM SUBMISSION ===');
            console.log('Form Data:', data);
            console.log('Common:', data.common);
            console.log('Services:', data.services);
            console.log('Totals:', data.totals);

            // Show loading state
            const saveBtn = $('finalSaveRecordBtn');
            const originalHtml = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            try {
                const response = await fetch('/api/cases/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                // Log response
                console.log('Server Response:', result);
                console.log('Response Status:', response.status);

                if (response.ok && result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Record saved successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reset and go to dashboard
                        resetAllFormData();
                        showScreen('screen1Dashboard');

                        // Refresh pending items from server
                        fetchPendingItems().then(() => {
                            // Also refresh the city pending cases if we're on a specific city
                            if (currentCity) {
                                loadCityPendingCases(currentCity);
                            }
                        });
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Failed to save record'
                    });
                }
            } catch (error) {
                console.error('Submission Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message
                });
            } finally {
                saveBtn.innerHTML = originalHtml;
                saveBtn.disabled = false;
            }
        });


    function resetAllFormData() {
        // Reset all global state
        currentServicesRows = [];
        nextId = 1;

        // Reset vehicle state
        Object.keys(vehicleState).forEach(key => {
            vehicleState[key] = '';
        });

        // Clear UI elements
        const elements = ['finalServicesTableBody', 'dynamicDetailsContainer', 'dynamicCommonFieldsContainer'];
        elements.forEach(id => {
            if ($(id)) $(id).innerHTML = '';
        });

        // Reset total fields
        if ($('finalTotalAmount')) $('finalTotalAmount').value = '';
        if ($('finalReceivedAmount')) $('finalReceivedAmount').value = '0';
        if ($('finalRemainingAmount')) $('finalRemainingAmount').value = '';
    }

    // =========================================================
    // INIT
    // =========================================================
    // Load initial data from server
    fetchPendingItems().then(() => {
        renderFilteredItems();
    });

})();
</script>
@endsection
