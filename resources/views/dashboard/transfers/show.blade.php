@extends('layouts.master')
@section('title', 'Transfer Receipt - Case #' . $transfer->vehicleCase->case_no)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- A4 Receipt Container --}}
        <div class="mx-auto" style="max-width: 800px;">
            <div id="receipt" class="bg-white shadow border"
                style="width: 210mm; min-height: 297mm; margin: 0 auto; padding: 5mm 10mm; border: 2px solid #222; font-family: Arial, sans-serif; position: relative;">

                {{-- Header --}}
                <div class="text-center mb-5">
                    <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="Logo" height="85"
                        style="max-width: 320px;"
                        onerror="this.src='https://via.placeholder.com/220x75/003087/ffffff?text=BA EnterPrise';">
                    {{-- <h2 class="mt-3 mb-1 fw-bold text-dark" style="letter-spacing: 1px;">
                        {{ \App\Helpers\Helper::getCompanyName() }}</h2> --}}
                    <p class="text-muted mb-0">Vehicle Transfer & Documentation Services</p>
                    <p class="small text-muted">123 Exporter Street, Karachi, Pakistan | +92-300-1234567</p>
                </div>

                <div class="border-bottom pb-3 mb-4 text-center">
                    <h3 class="fw-bold text-uppercase mb-1">Vehicle Transfer Receipt</h3>
                    {{-- <p class="mb-0 text-muted">یہ رسید برائے منتقلی گاڑی ہے</p> --}}
                </div>

                {{-- Receipt Info --}}
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <strong>Receipt No:</strong> TR-{{ str_pad($transfer->id, 6, '0', STR_PAD_LEFT) }}<br>
                        <strong>Date:</strong> {{ now()->format('d F, Y') }}
                    </div>
                    <div class="text-end">
                        <strong>Case No:</strong> {{ $transfer->vehicleCase->case_no }}<br>
                        <strong>Reg. No:</strong> {{ $transfer->vehicleCase->vehicle_reg_no }}
                    </div>
                </div>

                {{-- From & To --}}
                <div class="row mb-5">
                    <div class="col-6">
                        <div class="border p-3 h-100">
                            <h6 class="fw-bold text-primary mb-3">Transferred From (Seller)</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $transfer->from_name }}</p>
                            <p class="mb-1"><strong>S/O:</strong> {{ $transfer->from_s_o ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>NIC:</strong> {{ $transfer->from_nic }}</p>
                            <p><strong>Biometric:</strong>
                                <span class="badge {{ $transfer->from_biometric ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $transfer->from_biometric ? 'Verified' : 'Not Verified' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border p-3 h-100">
                            <h6 class="fw-bold text-primary mb-3">Transferred To (Buyer)</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $transfer->to_name }}</p>
                            <p class="mb-1"><strong>S/O:</strong> {{ $transfer->to_s_o ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>NIC:</strong> {{ $transfer->to_nic }}</p>
                            <p><strong>Biometric:</strong>
                                <span class="badge {{ $transfer->to_biometric ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $transfer->to_biometric ? 'Verified' : 'Not Verified' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Information --}}
                <div class="mb-5">
                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Vehicle Information</h6>
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th width="180">Regd. No.</th>
                            <td><strong>{{ $transfer->vehicleCase->vehicle_reg_no }}</strong></td>
                        </tr>
                        <tr>
                            <th>Make & Year</th>
                            <td>{{ $transfer->vehicleCase->make ?? 'N/A' }}
                                @if ($transfer->vehicleCase->year)
                                    ({{ $transfer->vehicleCase->year }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Engine Number</th>
                            <td>{{ $transfer->engine_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Chassis Number</th>
                            <td>{{ $transfer->chassis_no ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Wheels / Weight</th>
                            <td>{{ $transfer->wheels ?? 'N/A' }} &nbsp;|&nbsp; {{ $transfer->weight ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Last Tax Paid</th>
                            <td>{{ $transfer->last_tax ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- Declaration --}}
                <div class="border p-4 mb-5 text-center bg-light">
                    <p class="lead fw-semibold mb-3">
                        The above-mentioned vehicle has been officially transferred from the seller to the buyer
                        as per the documents and biometric verification submitted.
                    </p>
                    <div class="mt-4">
                        <span class="badge bg-success fs-5 px-5 py-3" style="letter-spacing: 1px;">
                            TRANSFER COMPLETED SUCCESSFULLY
                        </span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="text-center pt-4 border-top">
                    <small class="text-muted">
                        This is a computer-generated receipt. No physical signature is required.<br>
                        Thank you for trusting <strong>{{ \App\Helpers\Helper::getCompanyName() }}</strong> • Karachi,
                        Pakistan
                    </small>
                </div>

                {{-- Optional Watermark / Stamp --}}
                <div
                    style="position: absolute; bottom: 50%; opacity: 0.15; transform: rotate(-25deg); font-size: 60px; font-weight: bold; color: #28a745;">
                    PAID &amp; TRANSFERRED
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="text-center mt-5">
            <button onclick="printReceipt()" class="btn btn-primary btn-lg px-5 me-3">
                <i class="ti ti-printer me-2"></i> Print A4 Receipt
            </button>
            <button onclick="downloadPDF()" class="btn btn-success btn-lg px-5">
                <i class="ti ti-download me-2"></i> Download PDF
            </button>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function printReceipt() {
            const receipt = document.getElementById('receipt').outerHTML;
            const original = document.body.innerHTML;

            document.body.innerHTML = `
                <div style="padding: 0; margin: 0;">
                    ${receipt}
                </div>`;
            window.print();
            document.body.innerHTML = original;
            location.reload();
        }
        async function downloadPDF() {
            const receipt = document.getElementById('receipt');

            // Show loading (optional)
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.innerHTML = `<i class="ti ti-loader me-2"></i> Generating PDF...`;
            btn.disabled = true;

            try {
                const canvas = await html2canvas(receipt, {
                    scale: 2, // Higher quality
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff'
                });

                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });

                const imgWidth = 210; // A4 width in mm
                const pageHeight = 297; // A4 height in mm
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                let heightLeft = imgHeight;
                let position = 0;

                pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                // Add new pages if content is longer than one A4 page
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save(`Transfer-Receipt-${{ $transfer->id }}.pdf`);

            } catch (error) {
                console.error(error);
                alert('Failed to generate PDF. Please try again.');
            }

            // Reset button
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    </script>

@endsection
