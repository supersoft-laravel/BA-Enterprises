<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cases Report — {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: #f0f0f0;
            padding: 20px;
        }

        .page {
            width: 960px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border: 1px solid #ccc;
        }

        /* HEADER */
        .report-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .report-header img { height: 45px; margin-bottom: 5px; }
        .report-header h2  { font-size: 18px; letter-spacing: 1px; margin: 3px 0; }
        .report-header p   { font-size: 11px; margin: 1px 0; color: #444; }
        .report-title      { font-size: 15px; font-weight: bold; text-decoration: underline; margin-top: 8px; }
        .report-period     { font-size: 11px; margin-top: 4px; }

        /* SUMMARY STRIP */
        .summary-strip {
            display: flex;
            gap: 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 16px;
            font-size: 12px;
        }

        .summary-strip .s-box {
            flex: 1;
            padding: 10px 14px;
            border-right: 1px solid #ccc;
            text-align: center;
        }

        .summary-strip .s-box:last-child { border-right: none; }
        .summary-strip .s-label { font-size: 10px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-strip .s-value { font-size: 14px; font-weight: bold; margin-top: 2px; }
        .s-value.green  { color: #059669; }
        .s-value.red    { color: #dc2626; }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        thead th {
            background: #2d3748;
            color: #fff;
            padding: 8px 7px;
            text-align: left;
            border: 1px solid #2d3748;
            white-space: nowrap;
        }

        thead th.text-right { text-align: right; }

        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr:hover           { background: #eef2ff; }

        tbody td {
            padding: 7px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tbody td.text-right { text-align: right; }
        tbody td.text-center { text-align: center; }

        .services-list { line-height: 1.6; }
        .svc-item { display: flex; justify-content: space-between; gap: 6px; }
        .svc-name { color: #333; }
        .svc-amt  { font-weight: 600; white-space: nowrap; }

        .case-total { font-weight: bold; font-size: 12px; border-top: 1px solid #bbb; margin-top: 3px; padding-top: 3px; }

        .badge-open   { background:#d1fae5; color:#065f46; padding:2px 7px; border-radius:999px; font-size:10px; }
        .badge-closed { background:#fee2e2; color:#991b1b; padding:2px 7px; border-radius:999px; font-size:10px; }

        /* FOOTER TOTALS */
        tfoot td {
            padding: 8px 7px;
            border: 1px solid #ccc;
            font-weight: bold;
            background: #f1f5f9;
        }

        tfoot td.text-right { text-align: right; }

        /* PRINT BUTTON */
        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }

        .btn {
            padding: 8px 18px;
            font-size: 13px;
            border: 1px solid #000;
            background: #fff;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-print { background: #000; color: #fff; }
        .btn-back  { background: #fff; color: #000; }

        .empty-row td { text-align: center; color: #999; padding: 30px; font-style: italic; }

        /* PAGE FOOTER */
        .page-footer {
            margin-top: 20px;
            font-size: 10px;
            color: #666;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        @media print {
            body    { background: none; padding: 0; }
            .page   { border: none; padding: 10px; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

@php
    $grandTotal     = $caseTotals->sum();
    $uniqueBillings = $billings->unique('id');
    $grandPaid      = $uniqueBillings->sum('paid_amount');
    $grandRemaining = $uniqueBillings->sum('remaining_amount');
@endphp

<div class="page">

    {{-- ACTION BAR --}}
    <div class="action-bar no-print">
        <button onclick="window.history.back()" class="btn btn-back">← Back</button>
        <button onclick="window.print()" class="btn btn-print">🖨 Print Report</button>
    </div>

    {{-- HEADER --}}
    <div class="report-header">
        <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="Logo">
        <h2>{{ \App\Helpers\Helper::getCompanyName() }}</h2>
        <p>{{ \App\Helpers\Helper::getCompanyAddress() }}</p>
        @if(\App\Helpers\Helper::getCompanyPhone())
            <p>Ph: {{ \App\Helpers\Helper::getCompanyPhone() }}</p>
        @endif
        <div class="report-title">CASES REPORT</div>
        <div class="report-period">
            Period: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong>
            &mdash;
            <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            Total Cases: <strong>{{ $cases->count() }}</strong>
        </div>
    </div>

    {{-- SUMMARY STRIP --}}
    <div class="summary-strip">
        <div class="s-box">
            <div class="s-label">Total Cases</div>
            <div class="s-value">{{ $cases->count() }}</div>
        </div>
        <div class="s-box">
            <div class="s-label">Case Amount</div>
            <div class="s-value">Rs. {{ number_format($grandTotal, 0) }}</div>
        </div>
        <div class="s-box">
            <div class="s-label">Total Paid</div>
            <div class="s-value green">Rs. {{ number_format($grandPaid, 0) }}</div>
        </div>
        <div class="s-box">
            <div class="s-label">Total Remaining</div>
            <div class="s-value red">Rs. {{ number_format($grandRemaining, 0) }}</div>
        </div>
    </div>

    {{-- CASES TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width:30px;">Sr.</th>
                <th>Vehicle No</th>
                <th>Customer</th>
                <th>City</th>
                <th>Date</th>
                <th>Status</th>
                <th>Services & Amount</th>
                <th class="text-right">Case Total</th>
                <th class="text-right">Cust. Paid</th>
                <th class="text-right">Cust. Remaining</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cases as $index => $case)
                @php
                    $items      = $itemsByCase[$case->id] ?? collect();
                    $caseTotal  = $caseTotals[$case->id] ?? 0;
                    $billing    = $billings[$case->customer_id] ?? null;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $case->vehicle_no ?? '—' }}</strong></td>
                    <td>
                        {{ $case->customer->name ?? '—' }}<br>
                        @if($case->customer)
                            <span style="font-size:10px;color:#888;">{{ $case->customer->customer_code }}</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($case->city ?? '—') }}</td>
                    <td>{{ $case->case_date ? \Carbon\Carbon::parse($case->case_date)->format('d M Y') : '—' }}</td>
                    <td class="text-center">
                        @if($case->status === 'open')
                            <span class="badge-open">Open</span>
                        @else
                            <span class="badge-closed">Closed</span>
                        @endif
                    </td>
                    <td>
                        <div class="services-list">
                            @forelse($items as $item)
                                <div class="svc-item">
                                    <span class="svc-name">{{ $item->item_name }}</span>
                                    <span class="svc-amt">Rs. {{ number_format($item->item_amount, 0) }}</span>
                                </div>
                            @empty
                                <span style="color:#aaa;">—</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="text-right">
                        <strong>Rs. {{ number_format($caseTotal, 0) }}</strong>
                    </td>
                    <td class="text-right" style="color:#059669;">
                        Rs. {{ number_format($billing->paid_amount ?? 0, 0) }}
                    </td>
                    <td class="text-right" style="color:#dc2626;">
                        Rs. {{ number_format($billing->remaining_amount ?? 0, 0) }}
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="10">No cases found between the selected dates.</td>
                </tr>
            @endforelse
        </tbody>
        @if($cases->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="7" class="text-right">TOTALS:</td>
                <td class="text-right">Rs. {{ number_format($grandTotal, 0) }}</td>
                <td class="text-right" style="color:#059669;">Rs. {{ number_format($grandPaid, 0) }}</td>
                <td class="text-right" style="color:#dc2626;">Rs. {{ number_format($grandRemaining, 0) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- PAGE FOOTER --}}
    <div class="page-footer">
        <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
        <span>{{ \App\Helpers\Helper::getCompanyName() }} &mdash; Confidential</span>
    </div>

</div>

</body>
</html>
