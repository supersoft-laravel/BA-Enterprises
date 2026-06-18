<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Helpers\Helper::getCompanyName() }} - Partial Invoice #{{ $billing->bill_no ?? $billing->id }}</title>
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

        .center { text-align: center; }
        .right  { text-align: right; }
        .left   { text-align: left; }

        .header { border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 5px; }
        .logo   { width: 120px; height: auto; margin: 0 auto 5px; display: block; border: 1px solid #000; padding: 3px; }
        .shop-name    { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 2px 0; }
        .shop-address { font-size: 10px; margin: 1px 0; }
        .shop-phone   { font-size: 10px; margin: 1px 0; }

        .receipt-title { font-size: 13px; font-weight: bold; text-decoration: underline; margin: 5px 0 3px; }
        .receipt-meta  { font-size: 10px; margin: 1px 0; }

        .separator        { border-bottom: 1px dotted #000; margin: 4px 0; }
        .double-separator { border-bottom: 2px solid #000; margin: 4px 0; }

        .info-row   { display: flex; justify-content: space-between; margin: 2px 0; font-size: 10px; }
        .info-label { font-weight: bold; }

        /* 3-column items */
        .items        { margin: 4px 0; font-size: 10px; }
        .items-header {
            display: flex;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }
        .item-row     { display: flex; margin: 2px 0; }
        .col-desc     { flex: 2; padding-right: 3px; }
        .col-vehicle  { flex: 1; text-align: center; padding: 0 2px; }
        .col-amount   { flex: 1; text-align: right; font-weight: bold; }

        /* Old balance row */
        .old-balance-row .col-desc { font-style: italic; }

        .total-row  { display: flex; justify-content: space-between; margin: 2px 0; font-size: 11px; }
        .grand-total {
            font-size: 12px; font-weight: bold;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            padding: 3px 0; margin: 3px 0;
        }

        .status { font-weight: bold; margin: 2px 0; }

        .qr-section { margin: 8px 0 5px; text-align: center; }
        .qr-code    { width: 80px; height: 80px; margin: 0 auto 3px; border: 1px solid #000; padding: 2px; display: block; }
        .qr-label   { font-size: 9px; font-weight: bold; margin: 2px 0; }
        .qr-url     { font-size: 8px; color: #000; word-break: break-all; margin: 1px 0; }

        .footer      { margin-top: 8px; font-size: 9px; text-align: center; border-top: 1px solid #000; padding-top: 5px; }
        .footer-note { margin: 2px 0; }
        .signature-line { margin-top: 15px; display: flex; justify-content: space-between; }
        .signature-box  { width: 40%; border-top: 1px solid #000; padding-top: 2px; text-align: center; font-size: 9px; }

        .action-buttons { width: 320px; margin: 0 auto 10px; display: flex; justify-content: space-between; gap: 8px; }
        .btn { flex: 1; padding: 8px 10px; font-family: 'Courier New', monospace; font-size: 11px; border: 1px solid #000; background: #fff; cursor: pointer; }
        .btn-back:hover  { background: #000; color: #fff; }
        .btn-print       { background: #000; color: #fff; }
        .btn-print:hover { background: #fff; color: #000; }
        .btn:active      { transform: scale(0.97); }

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
    $customer      = $billing->customer;
    $partyName     = $customer ? $customer->name   : ($billing->vehicleCase->party_name   ?? 'N/A');
    $partyMobile   = $customer ? $customer->mobile : ($billing->vehicleCase->party_mobile ?? '—');
    $selectedTotal = $selectedItems->sum('item_amount');
@endphp

<div class="no-print action-buttons">
    <button onclick="window.history.back()" class="btn btn-back">← Back</button>
    <button onclick="window.print()" class="btn btn-print">🖨 Print</button>
</div>

<div class="receipt">

    {{-- HEADER --}}
    <div class="header center">
        <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="Logo" class="logo">
        <div class="shop-name">{{ \App\Helpers\Helper::getCompanyName() }}</div>
        <div class="shop-address">{{ \App\Helpers\Helper::getCompanyAddress() }}</div>
        @foreach(array_filter(explode(',', \App\Helpers\Helper::getCompanyPhone())) as $phone)
            <div class="shop-phone">Ph: {{ trim($phone) }}</div>
        @endforeach

        <div class="separator"></div>

        <div class="receipt-title">CASH MEMO</div>

        <div class="receipt-meta">
            Bill No: {{ $billing->bill_no ?? $billing->id }}<br>
            Date: {{ \Carbon\Carbon::parse($billing->billing_date)->format('Y-m-d') }}<br>
            Type: {{ strtoupper($billing->billing_type ?? 'LOCAL') }}<br>
            <span class="status">[PARTIAL] {{ strtoupper($billing->status) }}</span>
        </div>
    </div>

    {{-- CUSTOMER INFO --}}
    <div class="info-block">
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

    {{-- ITEMS --}}
    <div class="items">
        <div class="items-header">
            <span class="col-desc">DESCRIPTION</span>
            <span class="col-vehicle">VEH NO</span>
            <span class="col-amount">AMT (PKR)</span>
        </div>

        {{-- Previous balance row --}}
        @if($oldBalance > 0)
        <div class="item-row old-balance-row">
            <span class="col-desc">Prev. Balance</span>
            <span class="col-vehicle">—</span>
            <span class="col-amount">{{ \App\Helpers\Helper::formatCurrency($oldBalance) }}</span>
        </div>
        <div class="separator"></div>
        @endif

        {{-- New case items --}}
        @forelse($selectedItems as $item)
        <div class="item-row">
            <span class="col-desc">{{ Str::limit($item->item_name, 14) }}</span>
            <span class="col-vehicle">{{ $item->vehicleCase->vehicle_no ?? '—' }}</span>
            <span class="col-amount">{{ \App\Helpers\Helper::formatCurrency($item->item_amount) }}</span>
        </div>
        @empty
        <div class="item-row">
            <span class="col-desc">No items</span>
            <span class="col-vehicle">—</span>
            <span class="col-amount">0.00</span>
        </div>
        @endforelse
    </div>

    {{-- TOTAL --}}
    <div class="total-row grand-total">
        <span>TOTAL:</span>
        <span>{{ \App\Helpers\Helper::formatCurrency($billing->total_amount) }}</span>
    </div>

    {{-- PAID (only if something paid) --}}
    @if($billing->paid_amount > 0)
    <div class="total-row">
        <span class="info-label">PAID:</span>
        <span>{{ \App\Helpers\Helper::formatCurrency($billing->paid_amount) }}</span>
    </div>
    @endif

    {{-- BALANCE --}}
    <div class="total-row grand-total">
        <span>BALANCE:</span>
        <span>{{ \App\Helpers\Helper::formatCurrency($billing->remaining_amount) }}</span>
    </div>

    {{-- IN WORDS --}}
    <div class="info-row mt-5" style="margin-top:5px;">
        <span class="info-label">In Words:</span>
        <span>{{ \App\Helpers\Helper::numberToWords($billing->total_amount) }}</span>
    </div>

    {{-- QR CODE --}}
    <div class="double-separator"></div>
    <div class="qr-section">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('frontend.billing.verify', $billing->bill_no ?? $billing->id)) }}&margin=0&ecc=H"
             alt="Verify Bill" class="qr-code">
        <div class="qr-label">SCAN TO VERIFY</div>
        <div class="qr-url">{{ route('frontend.billing.verify', $billing->bill_no ?? $billing->id) }}</div>
        <div class="qr-label" style="font-weight:normal;">Authenticity Check</div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-note">* Goods once sold will not be taken back</div>
        <div class="footer-note">* Subject to Karachi jurisdiction only</div>
        <div class="footer-note">* Please retain this receipt for warranty</div>

        <div class="signature-line">
            <div class="signature-box">Customer Signature</div>
            <div class="signature-box">Authorised Signatory</div>
        </div>

        <div class="separator"></div>
        <div class="footer-note">Thank you for your business!</div>
        <div class="footer-note no-print">[ Ctrl+P to print ]</div>
    </div>

</div>

</body>
</html>
