<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill #{{ $billing->id ?? '00000' }}</title>
    <style>
        /* ===== CLASSIC RECEIPT RESET ===== */
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
        .right { text-align: right; }
        .left { text-align: left; }

        /* ===== HEADER & LOGO ===== */
        .header {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        .logo {
            width: 120px;
            height: auto;
            margin: 0 auto 5px;
            display: block;
            border: 1px solid #000;
            padding: 3px;
        }
        .shop-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }
        .shop-address { font-size: 10px; margin: 1px 0; }
        .shop-phone { font-size: 10px; margin: 1px 0; }
        .receipt-title {
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
            margin: 5px 0 3px;
        }
        .receipt-meta { font-size: 10px; margin: 1px 0; }

        /* ===== SEPARATORS ===== */
        .separator { border-bottom: 1px dotted #000; margin: 4px 0; }
        .double-separator { border-bottom: 2px solid #000; margin: 4px 0; }

        /* ===== INFO BLOCKS ===== */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 10px;
        }
        .info-label { font-weight: bold; }

        /* ===== ITEMS ===== */
        .items { margin: 4px 0; font-size: 10px; }
        .items-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }
        .item-name { flex: 1; padding-right: 5px; }
        .item-amount { font-weight: bold; }

        /* ===== TOTALS ===== */
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 11px;
        }
        .total-label { font-weight: bold; }
        .grand-total {
            font-size: 12px;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 0;
            margin: 3px 0;
        }

        /* ===== QR CODE SECTION ===== */
        .qr-section {
            margin: 8px 0 5px;
            text-align: center;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin: 0 auto 3px;
            border: 1px solid #000;
            padding: 2px;
            display: block;
        }
        .qr-label {
            font-size: 9px;
            font-weight: bold;
            margin: 2px 0;
        }
        .qr-url {
            font-size: 8px;
            color: #000;
            word-break: break-all;
            margin: 1px 0;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 8px;
            font-size: 9px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer-note { margin: 2px 0; }
        .signature-line {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 40%;
            border-top: 1px solid #000;
            padding-top: 2px;
            text-align: center;
            font-size: 9px;
        }

        /* ===== STATUS BADGE (CLASSIC) ===== */
        .status { font-weight: bold; margin: 2px 0; }
        .status-unpaid::before { content: "[UNPAID] "; }
        .status-partial::before { content: "[PARTIAL] "; }
        .status-paid::before { content: "[PAID] "; }

        /* ===== PRINT OPTIMIZATION ===== */
        @media print {
            body { padding: 0; background: #fff; }
            .receipt { border: none; width: 100%; max-width: 320px; margin: 0; padding: 0; }
            .no-print { display: none; }
            .qr-code { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        /* ===== UTILITY ===== */
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .mt-5 { margin-top: 5px; }
        .mb-5 { margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- ===== HEADER WITH LOGO ===== -->
        <div class="header center">
            <!-- COMPANY LOGO: Replace src with your logo path -->
            <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}"
                 alt="Company Logo"
                 class="logo">

            <div class="shop-name">{{ \App\Helpers\Helper::getCompanyName() }}</div>
            <div class="shop-address">{{ \App\Helpers\Helper::getCompanyAddress() }}</div>
            <div class="shop-phone">Ph: {{ \App\Helpers\Helper::getCompanyPhone() ?? '-' }}</div>
            <div class="shop-phone">GST: {{ \App\Helpers\Helper::getCompanyGST() ?? '-' }}</div>

            <div class="separator"></div>

            <div class="receipt-title">CASH MEMO</div>

            <div class="receipt-meta">
                Bill No: <span class="blade-placeholder">{{ $billing->id ?? '00000' }}</span><br>
                Date: <span class="blade-placeholder">{{ $billing->billing_date ?? date('Y-m-d') }}</span><br>
                Type: <span class="blade-placeholder">{{ strtoupper($billing->billing_type ?? 'LOCAL') }}</span><br>
                <span class="status status-{{ $billing->status ?? 'unpaid' }}">{{ strtoupper($billing->status ?? 'unpaid') }}</span>
            </div>
        </div>

        <!-- ===== CUSTOMER / CASE INFO ===== -->
        <div class="info-block">
            <div class="info-row">
                <span class="info-label">Case No:</span>
                <span class="blade-placeholder">{{ $billing->vehicleCase->case_no ?? 'CASE-00123' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Vehicle:</span>
                <span class="blade-placeholder">{{ $billing->vehicleCase->vehicle_reg_no ?? 'ABC-1234' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Make/Year:</span>
                <span class="blade-placeholder">{{ $billing->vehicleCase->make ?? 'Toyota' }} {{ $billing->vehicleCase->year ?? '2020' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Work Type:</span>
                <span class="blade-placeholder">{{ $billing->vehicleCase->work_type ?? 'Repair' }}</span>
            </div>
            <div class="separator"></div>
            <div class="info-row">
                <span class="info-label">Billed To:</span>
                <span class="blade-placeholder">{{ $billing->billing_name ?? 'Walk-in Customer' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mobile:</span>
                <span class="blade-placeholder">{{ $billing->vehicleCase->mobile_no ?? '+92-300-1234567' }}</span>
            </div>
        </div>

        <div class="double-separator"></div>

        <!-- ===== ITEMS ===== -->
        <div class="items">
            <div class="items-header">
                <span>DESCRIPTION</span>
                <span>AMT (PKR)</span>
            </div>

            @forelse($billing->items ?? [] as $item)
            <div class="item-row">
                <span class="item-name blade-placeholder">{{ Str::limit($item->item_name, 22) }}</span>
                <span class="item-amount blade-placeholder">{{ number_format($item->item_amount, 2) }}</span>
            </div>
            @empty
            <!-- Sample data for preview -->
            <div class="item-row">
                <span class="item-name">Engine Diagnostic</span>
                <span class="item-amount">2500.00</span>
            </div>
            <div class="item-row">
                <span class="item-name">Oil Change Synthetic</span>
                <span class="item-amount">4800.00</span>
            </div>
            <div class="item-row">
                <span class="item-name">Brake Pads Front</span>
                <span class="item-amount">8750.00</span>
            </div>
            <div class="item-row">
                <span class="item-name">Labor Charges</span>
                <span class="item-amount">3200.00</span>
            </div>
            <div class="item-row">
                <span class="item-name">Env. Fee</span>
                <span class="item-amount">250.00</span>
            </div>
            @endforelse
        </div>

        <div class="separator"></div>

        <!-- ===== TOTALS ===== -->
        <div class="total-row">
            <span class="total-label">TOTAL:</span>
            <span class="blade-placeholder">{{ number_format($billing->total_amount ?? 19500.00, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">PAID:</span>
            <span class="blade-placeholder">{{ number_format($billing->paid_amount ?? 10000.00, 2) }}</span>
        </div>
        <div class="total-row grand-total">
            <span>BALANCE:</span>
            <span class="blade-placeholder">{{ number_format($billing->remaining_amount ?? 9500.00, 2) }}</span>
        </div>

        <!-- ===== AMOUNT IN WORDS ===== -->
        <div class="info-row mt-5">
            <span class="info-label">In Words:</span>
            <span class="blade-placeholder">Nineteen Thousand Five Hundred Only</span>
        </div>

        <!-- ===== QR CODE FOR VERIFICATION ===== -->
        <div class="double-separator"></div>
        <div class="qr-section">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('billing.verify', $billing->id ?? '00000')) }}&margin=0&ecc=H"
                 alt="Verify Bill"
                 class="qr-code">
            <div class="qr-label">SCAN TO VERIFY</div>
            <div class="qr-url blade-placeholder">{{ route('billing.verify', $billing->id ?? '00000') }}</div>
            <div class="qr-label" style="font-weight:normal">Authenticity Check</div>
        </div>

        <!-- ===== FOOTER ===== -->
        <div class="footer">
            <div class="footer-note">* Goods once sold will not be taken back</div>
            <div class="footer-note">* Subject to Karachi jurisdiction only</div>
            <div class="footer-note">* Please retain this receipt for warranty</div>

            <div class="signature-line">
                <div class="signature-box">Customer</div>
                <div class="signature-box">Authorized Signatory</div>
            </div>

            <div class="separator"></div>
            <div class="footer-note">Thank you for your business!</div>
            <div class="footer-note no-print">[ Ctrl+P to print ]</div>
        </div>
    </div>

    <!-- ===== HELPER SCRIPT (Remove in production) ===== -->
    <script>
        // Fallback for QR code if route helper fails in preview
        document.addEventListener('DOMContentLoaded', function() {
            const qrImg = document.querySelector('.qr-code');
            if (qrImg && qrImg.src.includes('billing.verify')) {
                // QR will auto-generate via API when printed/viewed live
            }
        });
    </script>
</body>
</html>
