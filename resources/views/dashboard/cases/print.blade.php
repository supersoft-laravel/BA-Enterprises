<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Case #{{ $case->id }} — {{ $case->vehicle_no }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            background: #e6e6e6;
            padding: 20px;
            font-size: 13px;
            color: #222;
        }

        .page {
            width: 800px;
            margin: auto;
            background: #fff;
            padding: 28px;
            border: 1px solid #ccc;
        }

        /* PRINT BUTTON */
        .print-btn {
            text-align: center;
            margin-bottom: 16px;
        }
        .print-btn button {
            padding: 10px 24px;
            font-size: 14px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-btn button:hover { background: #0056b3; }

        /* HEADER */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 12px; }
        .header img { height: 45px; margin-bottom: 6px; }
        .header h1 { font-size: 20px; letter-spacing: 1px; margin-bottom: 2px; }
        .header p  { font-size: 12px; color: #555; margin: 1px 0; }

        /* CASE TITLE BAR */
        .case-title {
            background: #2c3e50;
            color: #fff;
            padding: 8px 14px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .case-title h2 { font-size: 15px; }
        .case-title .status-badge {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: bold;
            background: {{ $case->status === 'open' ? '#27ae60' : '#c0392b' }};
            color: #fff;
        }

        /* SECTION TITLES */
        .section-title {
            background: #d6dee8;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #aaa;
            margin-top: 14px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* INFO GRID */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px 12px;
            margin-bottom: 8px;
        }
        .info-grid.two-col { grid-template-columns: repeat(2, 1fr); }
        .info-item label { font-size: 10px; color: #666; display: block; margin-bottom: 1px; }
        .info-item p     { font-size: 13px; font-weight: bold; }

        /* TABLES */
        table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 12px; }
        th { background: #d6dee8; padding: 6px 8px; border: 1px solid #999; text-align: left; }
        td { padding: 6px 8px; border: 1px solid #999; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; background: #f5f5f5; }

        /* SERVICE CARDS */
        .service-card { border: 1px solid #bbb; margin-top: 10px; }
        .service-card-header {
            background: #eaf0f7;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 12px;
            border-bottom: 1px solid #bbb;
        }
        .service-card-body { padding: 8px 10px; }
        .service-card-body p { margin: 3px 0; font-size: 12px; }
        .service-card-body .two-col-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 12px;
        }

        /* PROVINCE BADGES */
        .province-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
        .province-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #bbb;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
        }
        .badge-complete   { color: #27ae60; font-weight: bold; }
        .badge-incomplete { color: #c0392b; font-weight: bold; }

        /* BILLING SUMMARY */
        .billing-summary { margin-top: 4px; }

        /* FOOTER */
        .footer { margin-top: 20px; display: flex; justify-content: space-between; font-size: 11px; color: #555; border-top: 1px solid #ccc; padding-top: 10px; }
        .signature { text-align: right; }
        .signature .line { margin-top: 30px; border-top: 1px solid #333; width: 160px; margin-left: auto; padding-top: 4px; }

        @media print {
            body { background: none; padding: 0; }
            .page { border: none; width: 100%; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

@php
    $settings = \App\Models\SystemSetting::first();
@endphp

<div class="print-btn no-print">
    <button onclick="window.print()">&#128438; Print Case Details</button>
</div>

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="Logo">
        <h1>{{ \App\Helpers\Helper::getCompanyName() }}</h1>
        <p>{{ \App\Helpers\Helper::getCompanyAddress() ?? 'Karachi, Pakistan' }}</p>
        <p>{{ \App\Helpers\Helper::getCompanyPhone() ?? '' }}
           @if(\App\Helpers\Helper::getCompanyEmail()) &nbsp;|&nbsp; {{ \App\Helpers\Helper::getCompanyEmail() }} @endif
        </p>
    </div>

    {{-- CASE TITLE BAR --}}
    <div class="case-title">
        <h2>Case Details &mdash; #{{ $case->id }}</h2>
        <span class="status-badge">{{ strtoupper($case->status) }}</span>
    </div>

    {{-- BASIC INFORMATION --}}
    <div class="section-title">&#9432; Basic Information</div>
    <div class="info-grid">
        <div class="info-item">
            <label>City / Refer To</label>
            <p>{{ $case->city ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Vehicle No.</label>
            <p>{{ $case->vehicle_no ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>New Vehicle No.</label>
            <p>{{ $case->new_vehicle_no ?? '—' }}</p>
        </div>
        <div class="info-item">
            <label>Vehicle Make</label>
            <p>{{ $case->vehicle_make ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Vehicle Model</label>
            <p>{{ $case->vehicle_model ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Engine No.</label>
            <p>{{ $case->engine_no ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Chassis No.</label>
            <p>{{ $case->chassis_no ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Customer Name</label>
            <p>{{ $case->party_name ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Customer Mobile</label>
            <p>{{ $case->party_mobile ?? 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Vendor Name</label>
            <p>{{ $case->vendor_name ?? '—' }}</p>
        </div>
        <div class="info-item">
            <label>Vendor Mobile</label>
            <p>{{ $case->vendor_mobile ?? '—' }}</p>
        </div>
        <div class="info-item">
            <label>Case Date</label>
            <p>{{ $case->case_date ? \Carbon\Carbon::parse($case->case_date)->format('d/m/Y') : 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Submitted At</label>
            <p>{{ $case->submitted_at ? \Carbon\Carbon::parse($case->submitted_at)->format('d/m/Y h:i A') : 'N/A' }}</p>
        </div>
        <div class="info-item">
            <label>Case Status</label>
            <p>{{ ucfirst($case->status) }}</p>
        </div>
    </div>
    @if($case->comment)
    <div class="info-item" style="margin-top:6px;">
        <label>Comment</label>
        <p>{{ $case->comment }}</p>
    </div>
    @endif

    {{-- SERVICES --}}
    <div class="section-title">&#9881; Service Details</div>

    @if($case->transfer)
    <div class="service-card">
        <div class="service-card-header">&#8644; Transfer Details</div>
        <div class="service-card-body">
            <div class="two-col-grid">
                <div>
                    <p><strong>From Name:</strong> {{ $case->transfer->from_name ?? 'N/A' }}</p>
                    <p><strong>From S/O:</strong> {{ $case->transfer->from_s_o ?? 'N/A' }}</p>
                    <p><strong>From NIC:</strong> {{ $case->transfer->from_nic ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>To Name:</strong> {{ $case->transfer->to_name ?? 'N/A' }}</p>
                    <p><strong>To S/O:</strong> {{ $case->transfer->to_s_o ?? 'N/A' }}</p>
                    <p><strong>To NIC:</strong> {{ $case->transfer->to_nic ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($case->alteration)
    <div class="service-card">
        <div class="service-card-header">&#9997; Alteration Details</div>
        <div class="service-card-body">
            <p><strong>Alteration Type:</strong> {{ $case->alteration->alteration_type ?? 'N/A' }}</p>
        </div>
    </div>
    @endif

    @if($case->permit)
    @php
        $permitProvinces = $case->permit->province
            ? array_values(array_filter(array_map('trim', explode(',', $case->permit->province))))
            : [];
        $provinceStatus  = $case->permit->province_status ?? [];
    @endphp
    <div class="service-card">
        <div class="service-card-header">&#128205; Route Permit Details</div>
        <div class="service-card-body">
            <p><strong>Type:</strong> {{ $case->permit->type ?? 'N/A' }}</p>
            <p><strong>Details:</strong> {{ $case->permit->details ?? 'N/A' }}</p>
            @if(count($permitProvinces) > 0)
            <p style="margin-top:6px;"><strong>Province Status:</strong></p>
            <div class="province-row">
                @foreach($permitProvinces as $prov)
                @php $pStatus = $provinceStatus[$prov] ?? 'incomplete'; @endphp
                <div class="province-badge">
                    <span>{{ $prov }}</span>
                    <span class="{{ $pStatus === 'complete' ? 'badge-complete' : 'badge-incomplete' }}">
                        {{ ucfirst($pStatus) }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($case->fitness)
    <div class="service-card">
        <div class="service-card-header">&#128663; FC (Fitness Certificate) Details</div>
        <div class="service-card-body">
            <p><strong>Truck Type:</strong> {{ $case->fitness->type ?? 'N/A' }}</p>
            <p><strong>Details:</strong> {{ $case->fitness->details ?? 'N/A' }}</p>
        </div>
    </div>
    @endif

    @if($case->insurance)
    <div class="service-card">
        <div class="service-card-header">&#128737; Insurance Details</div>
        <div class="service-card-body">
            <p><strong>Details:</strong> {{ $case->insurance->details ?? 'N/A' }}</p>
        </div>
    </div>
    @endif

    @if($case->tax)
    <div class="service-card">
        <div class="service-card-header">&#128181; Tax Details</div>
        <div class="service-card-body">
            <p><strong>From Period:</strong> {{ $case->tax->tax_from ?? 'N/A' }}</p>
            <p><strong>Upto:</strong> {{ $case->tax->tax_to ?? 'N/A' }}</p>
        </div>
    </div>
    @endif

    @if($case->fileReturn)
    <div class="service-card">
        <div class="service-card-header">&#128194; File Return Details</div>
        <div class="service-card-body">
            <div class="two-col-grid">
                <div>
                    <p><strong>From Name:</strong> {{ $case->fileReturn->from_name ?? 'N/A' }}</p>
                    <p><strong>From S/O:</strong> {{ $case->fileReturn->from_s_o ?? 'N/A' }}</p>
                    <p><strong>From NIC:</strong> {{ $case->fileReturn->from_nic ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>To Name:</strong> {{ $case->fileReturn->to_name ?? 'N/A' }}</p>
                    <p><strong>To S/O:</strong> {{ $case->fileReturn->to_s_o ?? 'N/A' }}</p>
                    <p><strong>To NIC:</strong> {{ $case->fileReturn->to_nic ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($case->other)
    <div class="service-card">
        <div class="service-card-header">&#8943; Other Details</div>
        <div class="service-card-body">
            <p>{{ $case->other->details ?? 'N/A' }}</p>
        </div>
    </div>
    @endif

    {{-- BILLING SUMMARY --}}
    @if($case->billing)
    <div class="section-title">&#128179; Billing Summary</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Service</th>
                <th>Service Date</th>
                <th class="text-right">Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($case->billing->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->service_date ? \Carbon\Carbon::parse($item->service_date)->format('d/m/Y') : '—' }}</td>
                <td class="text-right">{{ \App\Helpers\Helper::formatCurrency($item->item_amount) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Amount</td>
                <td class="text-right">{{ \App\Helpers\Helper::formatCurrency($case->billing->total_amount) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">Paid Amount</td>
                <td class="text-right">{{ \App\Helpers\Helper::formatCurrency($case->billing->paid_amount) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">Remaining Balance</td>
                <td class="text-right">{{ \App\Helpers\Helper::formatCurrency($case->billing->remaining_amount) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- PAYMENT HISTORY --}}
    @if($case->billing->payments && $case->billing->payments->count() > 0)
    <div class="section-title" style="margin-top:14px;">&#128203; Payment History</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Method</th>
                <th>Reference</th>
                <th class="text-right">Amount (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($case->billing->payments as $payment)
            <tr>
                <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>
                <td>{{ $payment->reference_no ?? ($payment->transaction_id ?? '—') }}</td>
                <td class="text-right">{{ \App\Helpers\Helper::formatCurrency($payment->amount) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <div>
            Printed: {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}<br>
            Case #{{ $case->id }} &mdash; {{ $case->vehicle_no }}
        </div>
        <div class="signature">
            @if(file_exists(public_path('assets/img/stamp/stamp.png')))
                <img src="{{ asset('assets/img/stamp/stamp.png') }}" style="height:50px; display:block; margin-left:auto; margin-bottom:4px;">
            @endif
            <div class="line">Authorized Signature</div>
        </div>
    </div>

</div>{{-- /page --}}

<script>
    window.onbeforeprint = function() {
        document.querySelectorAll('.no-print').forEach(function(el) { el.style.display = 'none'; });
    };
    window.onafterprint = function() {
        document.querySelectorAll('.no-print').forEach(function(el) { el.style.display = 'block'; });
    };
</script>
</body>
</html>
