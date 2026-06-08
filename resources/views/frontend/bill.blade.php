<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ \App\Helpers\Helper::getCompanyName() }} - Billing #{{ $billing->id }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6e6e6;
            padding: 20px;
        }

        .invoice {
            width: 800px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border: 1px solid #ccc;
            position: relative;
            z-index: 1;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 60px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 26px;
            letter-spacing: 1px;
        }

        .header p {
            margin: 2px 0;
            font-size: 13px;
        }

        /* INFO */
        .info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 13px;
        }

        .info div {
            width: 48%;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }

        th {
            background: #d6dee8;
            padding: 8px;
            border: 1px solid #999;
        }

        td {
            padding: 8px;
            border: 1px solid #999;
        }

        .text-right {
            text-align: right;
        }

        /* TOTAL BOX */
        .total-row td {
            font-weight: bold;
        }

        .final-total {
            color: red;
            font-size: 16px;
        }

        /* PAYMENT HISTORY SECTION */
        .payment-history {
            margin-top: 20px;
        }

        .payment-history h3 {
            background: #d6dee8;
            padding: 8px;
            margin: 0 0 10px 0;
            font-size: 14px;
            border: 1px solid #999;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        .payment-table th {
            background: #e8e8e8;
            padding: 6px;
            font-size: 12px;
        }

        .payment-table td {
            padding: 6px;
            font-size: 12px;
        }

        .payment-method-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #f0f0f0;
            border-radius: 3px;
            font-size: 10px;
            font-weight: normal;
        }

        .reference-no {
            font-size: 10px;
            color: #666;
            font-family: monospace;
        }

        .payment-note {
            font-size: 10px;
            color: #888;
            font-style: italic;
        }

        .total-paid-summary {
            margin-top: 10px;
            padding: 8px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            text-align: right;
            font-weight: bold;
        }

        /* TERMS */
        .terms {
            margin-top: 20px;
            background: #d6dee8;
            padding: 10px;
            font-size: 13px;
            text-align: center;
        }

        /* FOOTER */
        .footer {
            margin-top: 20px;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }

        @media print {
            body {
                background: none;
            }

            .invoice {
                border: none;
            }
        }

        /* WATERMARK */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 90px;
            font-weight: bold;
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }

        .watermark.paid {
            color: green;
        }

        .watermark.unpaid {
            color: red;
        }

        .watermark.partial {
            color: orange;
        }

        /* Ensure content stays above watermark */
        .invoice {
            position: relative;
            z-index: 1;
        }

        /* Print button */
        .print-btn {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-btn button {
            padding: 10px 20px;
            font-size: 14px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .print-btn button:hover {
            background: #0056b3;
        }

        @media print {
            .print-btn {
                display: none;
            }

            .watermark {
                position: absolute;
            }
        }
    </style>
</head>

<body>

    @php
        $settings = \App\Models\SystemSetting::first();
    @endphp

    {{-- <div class="print-btn no-print">
        <button onclick="window.print()">🖨 Print Invoice</button>
    </div> --}}

    <div class="invoice">
        <!-- WATERMARK -->
        <div class="watermark {{ $billing->status }}">
            {{ strtoupper($billing->status) }}
        </div>

        <!-- HEADER -->
        <div class="header">
            <img style="height: 40px;" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="Logo">
            <p>{{ \App\Helpers\Helper::getCompanyAddress() ?? 'Karachi, Pakistan' }}</p>
            <p>{{ \App\Helpers\Helper::getCompanyPhone() ?? '' }}</p>
            <p>{{ \App\Helpers\Helper::getCompanyEmail() ?? '' }}</p>
        </div>

        <!-- INFO -->
        <div class="info">
            <div>
                <strong>Office / Service Address:</strong><br>
                <strong>Case Refer To:</strong> {{ $billing->vehicleCase->city ?? 'N/A' }}<br>
                <strong>Mobile:</strong> {{ $billing->vehicleCase->party_mobile ?? 'N/A' }}
            </div>

            <div class="text-right">
                <strong>Bill No:</strong> #{{ $billing->bill_no ?? $billing->id }}<br>
                {{-- <strong>Case No:</strong> #{{ $billing->vehicleCase->case_no ?? 'N/A' }}<br> --}}
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($billing->billing_date)->format('d M Y') }}<br>
                <strong>Customer:</strong> {{ $billing->vehicleCase->party_name ?? 'N/A' }}<br>
                <strong>Vehicle Regd:</strong> {{ $billing->vehicleCase->vehicle_no ?? 'N/A' }}
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <table>
            <thead>
                <tr>
                    <th>Services</th>
                    <th>Date</th>
                    <th>Amount (PKR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($billing->items as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($billing->billing_date)->format('d M Y') }}</td>
                        <td class="text-right">{{ \App\Helpers\Helper::formatCurrency($item->item_amount) }}</td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="2" class="text-right"><strong>Sub Total</strong></td>
                    <td class="text-right"><strong>{{ \App\Helpers\Helper::formatCurrency($billing->total_amount) }}</strong></td>
                </tr>

                <tr class="total-row">
                    <td colspan="2" class="text-right"><strong>Paid Amount</strong></td>
                    <td class="text-right"><strong>{{ \App\Helpers\Helper::formatCurrency($billing->paid_amount) }}</strong></td>
                </tr>

                <tr class="total-row">
                    <td colspan="2" class="text-right"><strong>Remaining Balance</strong></td>
                    <td class="text-right"><strong>{{ \App\Helpers\Helper::formatCurrency($billing->remaining_amount) }}</strong></td>
                </tr>

                <tr class="total-row">
                    <td colspan="2" class="text-right final-total"><strong>Total Invoice Amount</strong></td>
                    <td class="text-right final-total"><strong>{{ \App\Helpers\Helper::formatCurrency($billing->total_amount) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- PAYMENT HISTORY SECTION -->
        @if(isset($payments) && $payments->count() > 0)
            <div class="payment-history">
                <h3>📋 Payment History</h3>
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Payment Method</th>
                            <th>Amount (PKR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : 'N/A' }}</td>
                                <td>
                                    <span class="payment-method-badge">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td class="text-right"><strong>{{ \App\Helpers\Helper::formatCurrency($payment->amount) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-paid-summary">
                    <strong>Total Payments Received:</strong> {{ \App\Helpers\Helper::formatCurrency($payments->sum('amount')) }}
                </div>
            </div>
        @else
            <div class="payment-history">
                <h3>📋 Payment History</h3>
                <p style="padding: 15px; text-align: center; background: #f9f9f9; border: 1px solid #ddd;">
                    No payment records found for this bill.
                </p>
            </div>
        @endif

        <!-- TERMS -->
        {{-- <div class="terms">
            <strong>Terms & Conditions:</strong> Payment is due within 30 days. Late payments may incur additional charges.
        </div> --}}

        <!-- FOOTER -->
        <div class="footer">
            <div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('frontend.billing.verify', $billing->bill_no ?? $billing->id)) }}&margin=0&ecc=H"
                    alt="Verify Bill" class="qr-code" style="margin-top:8px; width:80px; height:80px;">
                <br><br>
                <strong>Scan this QR code to verify the bill</strong><br>
                <small style="font-size: 10px;">{{ route('frontend.billing.verify', $billing->bill_no ?? $billing->id) }}</small>
                <br><br>
                Thank you for your business!
            </div>

            <div class="signature">
                @if(file_exists(public_path('assets/img/stamp/stamp.png')))
                    <img src="{{ asset('assets/img/stamp/stamp.png') }}"
                        style="height: 60px; display:block; margin-left:auto; margin-bottom:5px;">
                @endif
                _______________________<br>
                Authorized Signature
            </div>
        </div>

        <!-- Payment Status Note -->
        @if($billing->status == 'paid')
            <div style="margin-top: 15px; padding: 8px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; text-align: center; font-size: 12px;">
                ✅ This bill has been fully paid. Thank you!
            </div>
        @elseif($billing->status == 'partial')
            <div style="margin-top: 15px; padding: 8px; background: #fff3cd; color: #856404; border: 1px solid #ffeeba; text-align: center; font-size: 12px;">
                ⚠️ Partial payment received. Remaining balance: {{ \App\Helpers\Helper::formatCurrency($billing->remaining_amount) }}
            </div>
        @elseif($billing->status == 'unpaid')
            <div style="margin-top: 15px; padding: 8px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; text-align: center; font-size: 12px;">
                ⚠️ This bill is unpaid. Please settle the amount of {{ \App\Helpers\Helper::formatCurrency($billing->remaining_amount) }}.
            </div>
        @endif
    </div>

    <script>
        // Auto-hide print button when printing
        window.onbeforeprint = function() {
            document.querySelectorAll('.no-print').forEach(el => {
                el.style.display = 'none';
            });
        };

        window.onafterprint = function() {
            document.querySelectorAll('.no-print').forEach(el => {
                el.style.display = 'block';
            });
        };
    </script>
</body>

</html>
