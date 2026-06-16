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
        .s-value.green { color: #059669; }
        .s-value.red   { color: #dc2626; }

        /* SECTION TITLE */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            border-left: 4px solid #2d3748;
            padding-left: 8px;
            margin: 20px 0 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 6px;
        }

        thead th {
            background: #2d3748;
            color: #fff;
            padding: 8px 7px;
            text-align: left;
            border: 1px solid #2d3748;
            white-space: nowrap;
        }

        thead th.text-right  { text-align: right; }
        thead th.text-center { text-align: center; }

        tbody tr:nth-child(even) { background: #f8f9fa; }
        tbody tr:hover           { background: #eef2ff; }

        tbody td {
            padding: 7px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tbody td.text-right  { text-align: right; }
        tbody td.text-center { text-align: center; }

        .services-list { line-height: 1.7; }
        .svc-item { display: flex; justify-content: space-between; gap: 6px; }
        .svc-name { color: #333; }
        .svc-amt  { font-weight: 600; white-space: nowrap; }

        .badge-open   { background: #d1fae5; color: #065f46; padding: 2px 7px; border-radius: 999px; font-size: 10px; }
        .badge-closed { background: #fee2e2; color: #991b1b; padding: 2px 7px; border-radius: 999px; font-size: 10px; }

        tfoot td {
            padding: 8px 7px;
            border: 1px solid #ccc;
            font-weight: bold;
            background: #f1f5f9;
        }
        tfoot td.text-right { text-align: right; }

        .empty-row td { text-align: center; color: #999; padding: 30px; font-style: italic; }

        /* ACTION BAR */
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
            text-decoration: none;
            display: inline-block;
        }

        .btn-print    { background: #000; color: #fff; border-color: #000; }
        .btn-download { background: #1a6e38; color: #fff; border-color: #1a6e38; }

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
            body  { background: none; padding: 0; }
            .page { border: none; padding: 10px; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>

@php
    $grandTotal     = $caseTotals->sum();
    $uniqueBillings = $billings->unique('id');
    $grandPaid      = $uniqueBillings->sum('paid_amount');
    $grandRemaining = $uniqueBillings->sum('remaining_amount');
@endphp

<div class="page">

    {{-- ACTION BAR (no-print) --}}
    <div class="action-bar no-print">
        <button onclick="window.print()" class="btn btn-print">&#128438; Print Report</button>
        <button onclick="downloadPDF()" class="btn btn-download">&#8659; Download PDF</button>
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

    {{-- ══════════════ CASES TABLE ══════════════ --}}
    <div class="section-title">Cases Detail</div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">Sr.</th>
                <th>Vehicle No</th>
                <th>Customer</th>
                <th>City</th>
                <th>Date</th>
                <th class="text-center">Status</th>
                <th>Services &amp; Amount</th>
                <th class="text-right">Case Total</th>
                <th class="text-right">Cust. Paid</th>
                <th class="text-right">Cust. Remaining</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cases as $index => $case)
                @php
                    $items     = $itemsByCase[$case->id] ?? collect();
                    $caseTotal = $caseTotals[$case->id] ?? 0;
                    $billing   = $billings[$case->customer_id] ?? null;
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
                    <td class="text-right"><strong>Rs. {{ number_format($caseTotal, 0) }}</strong></td>
                    <td class="text-right" style="color:#059669;">Rs. {{ number_format($billing->paid_amount ?? 0, 0) }}</td>
                    <td class="text-right" style="color:#dc2626;">Rs. {{ number_format($billing->remaining_amount ?? 0, 0) }}</td>
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

    {{-- ══════════════ CUSTOMER SUMMARY ══════════════ --}}
    @if($customers->isNotEmpty())
    <div class="section-title" style="margin-top:30px;">Customer Summary</div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">Sr.</th>
                <th>Customer</th>
                <th>Customer Code</th>
                <th class="text-center">Cases in Period</th>
                <th class="text-right">Period Case Amount</th>
                <th class="text-right">Total Bill Amount</th>
                <th class="text-right">Total Paid</th>
                <th class="text-right">Remaining Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
                @php
                    $customerCases      = $cases->where('customer_id', $customer->id);
                    $customerCaseIds    = $customerCases->pluck('id');
                    $periodAmount       = $caseTotals->only($customerCaseIds)->sum();
                    $customerBilling    = $billings[$customer->id] ?? null;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $customer->name }}</strong></td>
                    <td>{{ $customer->customer_code }}</td>
                    <td class="text-center">{{ $customerCases->count() }}</td>
                    <td class="text-right">Rs. {{ number_format($periodAmount, 0) }}</td>
                    <td class="text-right">Rs. {{ number_format($customerBilling->total_amount ?? 0, 0) }}</td>
                    <td class="text-right" style="color:#059669;">
                        Rs. {{ number_format($customerBilling->paid_amount ?? 0, 0) }}
                    </td>
                    <td class="text-right" style="color:#dc2626;">
                        Rs. {{ number_format($customerBilling->remaining_amount ?? 0, 0) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">TOTALS:</td>
                <td class="text-right">Rs. {{ number_format($grandTotal, 0) }}</td>
                <td class="text-right">Rs. {{ number_format($uniqueBillings->sum('total_amount'), 0) }}</td>
                <td class="text-right" style="color:#059669;">Rs. {{ number_format($grandPaid, 0) }}</td>
                <td class="text-right" style="color:#dc2626;">Rs. {{ number_format($grandRemaining, 0) }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- PAGE FOOTER --}}
    <div class="page-footer">
        <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
        <span>{{ \App\Helpers\Helper::getCompanyName() }} &mdash; Confidential</span>
    </div>

</div>

<script>
    function downloadPDF() {
        const btn = document.querySelectorAll('.no-print');
        btn.forEach(el => el.style.display = 'none');

        const element = document.querySelector('.page');
        const filename = 'cases-report-{{ $startDate }}-to-{{ $endDate }}.pdf';

        html2pdf()
            .set({
                margin: [8, 8, 8, 8],
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, logging: false },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
            })
            .from(element)
            .save()
            .then(() => {
                btn.forEach(el => el.style.display = '');
            });
    }
</script>

</body>
</html>
