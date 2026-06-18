<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Helpers\Helper::getCompanyName() }} - Case Invoice #{{ $case->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 10px;
        }

        .receipt {
            width: 320px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 8px;
        }

        .center  { text-align: center; }
        .right   { text-align: right; }
        .left    { text-align: left; }

        /* HEADER */
        .header { border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 5px; }
        .logo   { width: 120px; height: auto; margin: 0 auto 5px; display: block; border: 1px solid #000; padding: 3px; }
        .shop-name    { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 2px 0; }
        .shop-address { font-size: 10px; margin: 1px 0; }
        .shop-phone   { font-size: 10px; margin: 1px 0; }

        .receipt-title { font-size: 13px; font-weight: bold; text-decoration: underline; margin: 5px 0 3px; }
        .receipt-meta  { font-size: 10px; margin: 1px 0; }

        /* SEPARATORS */
        .separator        { border-bottom: 1px dotted #000; margin: 4px 0; }
        .double-separator { border-bottom: 2px solid #000; margin: 4px 0; }

        /* INFO */
        .info-row   { display: flex; justify-content: space-between; margin: 2px 0; font-size: 10px; }
        .info-label { font-weight: bold; }

        /* ITEMS */
        .items        { margin: 4px 0; font-size: 10px; }
        .items-header {
            display: flex; justify-content: space-between;
            font-weight: bold; border-bottom: 1px solid #000;
            padding-bottom: 2px; margin-bottom: 2px;
        }
        .item-row   { display: flex; justify-content: space-between; margin: 2px 0; }
        .item-name  { flex: 1; padding-right: 5px; }
        .item-amount { font-weight: bold; }

        /* TOTALS */
        .total-row  { display: flex; justify-content: space-between; margin: 2px 0; font-size: 11px; }
        .total-label { font-weight: bold; }
        .grand-total {
            font-size: 12px; font-weight: bold;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            padding: 3px 0; margin: 3px 0;
        }

        /* STATUS */
        .status { font-weight: bold; margin: 2px 0; }
        .status-case::before { content: "[CASE INVOICE] "; }

        /* QR */
        .qr-section { margin: 8px 0 5px; display: flex; justify-content: space-around; align-items: flex-start; gap: 4px; }
        .qr-block   { flex: 1; text-align: center; }
        .qr-code    { width: 80px; height: 80px; margin: 0 auto 3px; border: 1px solid #000; padding: 2px; display: block; }
        .qr-label   { font-size: 9px; font-weight: bold; margin: 2px 0; }
        .qr-url     { font-size: 8px; color: #000; word-break: break-all; margin: 1px 0; }

        /* FOOTER */
        .footer      { margin-top: 8px; font-size: 9px; text-align: center; border-top: 1px solid #000; padding-top: 5px; }
        .footer-note { margin: 2px 0; }
        .signature-line { margin-top: 15px; display: flex; justify-content: space-between; }
        .signature-box  { width: 40%; border-top: 1px solid #000; padding-top: 2px; text-align: center; font-size: 9px; }

        /* BUTTONS */
        .action-buttons { width: 320px; margin: 0 auto 10px; display: flex; justify-content: space-between; gap: 8px; }
        .btn { flex: 1; padding: 8px 10px; font-family: 'Courier New', monospace; font-size: 11px; border: 1px solid #000; background: #fff; cursor: pointer; }
        .btn-back:hover  { background: #000; color: #fff; }
        .btn-print       { background: #000; color: #fff; }
        .btn-print:hover { background: #fff; color: #000; }
        .btn:active      { transform: scale(0.97); }

        .bold      { font-weight: bold; }
        .underline { text-decoration: underline; }
        .mt-5  { margin-top: 5px; }
        .mb-5  { margin-bottom: 5px; }

        @media print {
            body * { visibility: hidden; }
            .receipt, .receipt * { visibility: visible; }
            .receipt { position: absolute; top: 0; left: 0; width: 100%; max-width: 320px; margin: 0; padding: 0; border: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

@php
    $customer     = $case->customer;
    $partyName    = $customer ? $customer->name    : ($case->party_name   ?? 'N/A');
    $partyMobile  = $customer ? $customer->mobile  : ($case->party_mobile ?? '—');
    $caseTotal    = $caseItems->sum('item_amount');
@endphp

<div class="no-print action-buttons">
    <button onclick="window.history.back()" class="btn btn-back">← Back</button>
    <button onclick="window.print()" class="btn btn-print">🖨 Print</button>
</div>

<div class="receipt">

    {{-- HEADER --}}
    <div class="header center">
        <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="Company Logo" class="logo">
        <div class="shop-name">{{ \App\Helpers\Helper::getCompanyName() }}</div>
        <div class="shop-address">{{ \App\Helpers\Helper::getCompanyAddress() }}</div>
        @foreach(array_filter(explode(',', \App\Helpers\Helper::getCompanyPhone())) as $phone)
            <div class="shop-phone">Ph: {{ trim($phone) }}</div>
        @endforeach

        <div class="separator"></div>

        <div class="receipt-title">CASE INVOICE</div>

        <div class="receipt-meta">
            Case No: {{ $case->id }}<br>
            Date: {{ $case->case_date ?? date('Y-m-d') }}<br>
            City: {{ strtoupper($case->city ?? 'N/A') }}<br>
            <span class="status status-case">{{ strtoupper($case->status ?? 'open') }}</span>
        </div>
    </div>

    {{-- VEHICLE / CUSTOMER INFO --}}
    <div class="info-block">
        <div class="info-row">
            <span class="info-label">Vehicle:</span>
            <span>{{ $case->vehicle_no ?? 'N/A' }}</span>
        </div>
        <div class="separator"></div>
        <div class="info-row">
            <span class="info-label">Billed To:</span>
            <span>{{ $partyName }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mobile:</span>
            <span>{{ $partyMobile }}</span>
        </div>
        @if($customer)
        <div class="info-row">
            <span class="info-label">Customer ID:</span>
            <span>{{ $customer->customer_code }}</span>
        </div>
        @endif
    </div>

    <div class="double-separator"></div>

    {{-- SERVICES --}}
    <div class="items">
        <div class="items-header">
            <span>DESCRIPTION</span>
            <span>AMT (PKR)</span>
        </div>

        @forelse($caseItems as $item)
            <div class="item-row">
                <span class="item-name">{{ Str::limit($item->item_name, 22) }}</span>
                <span class="item-amount">{{ \App\Helpers\Helper::formatCurrency($item->item_amount) }}</span>
            </div>
        @empty
            <div class="item-row">
                <span class="item-name">No services found</span>
                <span class="item-amount">0.00</span>
            </div>
        @endforelse
    </div>

    {{-- TOTAL --}}
    <div class="total-row grand-total">
        <span>TOTAL:</span>
        <span>{{ \App\Helpers\Helper::formatCurrency($caseTotal) }}</span>
    </div>

    {{-- AMOUNT IN WORDS --}}
    <div class="info-row mt-5">
        <span class="info-label">In Words:</span>
        <span>{{ \App\Helpers\Helper::numberToWords($caseTotal) }}</span>
    </div>

    {{-- REFERENCE TO FULL CUSTOMER BILL --}}
    @if($customerBilling)
    <div class="separator"></div>
    <div style="font-size:9px;text-align:center;margin:3px 0;color:#333;">
        Full Bill No: {{ $customerBilling->bill_no }} &mdash;
        Total: {{ \App\Helpers\Helper::formatCurrency($customerBilling->total_amount) }}
    </div>
    @endif

    {{-- QR CODE --}}
    <div class="double-separator"></div>
    <div class="qr-section">
        <div class="qr-block">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('dashboard.cases.invoice', $case->id)) }}&margin=0&ecc=H"
                 alt="Case Invoice QR" class="qr-code">
            <div class="qr-label">Scan to verify</div>
        </div>
        <div class="qr-block">
            <img src="{{ asset('assets/img/meezan-qr.jpeg') }}" alt="Pay via Meezan Bank" class="qr-code">
            <div class="qr-label">Scan to pay</div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-note">* Goods once sold will not be taken back</div>
        <div class="footer-note">* Subject to Karachi jurisdiction only</div>
        <div class="footer-note">* Please retain this receipt for warranty</div>

        <div class="signature-line">
            <div class="signature-box">Customer Signature</div>
            <div class="signature-box">Authorized Signatory</div>
        </div>

        <div class="separator"></div>
        <div class="footer-note">Thank you for your business!</div>
        <div class="footer-note no-print">[ Ctrl+P to print ]</div>
    </div>

</div>

</body>
</html>
