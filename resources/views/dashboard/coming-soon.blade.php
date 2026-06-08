@extends('layouts.master')
@section('title', 'Coming Soon')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-9 col-md-10">

            <div class="text-center mb-5">
                <div class="mb-4">
                    <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}"
                         alt="AutoExportz"
                         height="90"
                         onerror="this.src='https://via.placeholder.com/250x90/003087/ffffff?text=AutoExportz';">
                </div>
                <h1 class="display-4 fw-bold text-primary mb-2">Coming Soon</h1>
                <p class="lead text-muted">We're working hard to bring something amazing for you</p>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-5 p-md-6">

                    {{-- Countdown Timer --}}
                    <div class="row text-center mb-5" id="countdown">
                        <div class="col-3">
                            <div class="bg-label-primary rounded-3 py-3">
                                <h2 class="mb-0 fw-bold text-primary" id="days">00</h2>
                                <small class="text-muted">Days</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-label-primary rounded-3 py-3">
                                <h2 class="mb-0 fw-bold text-primary" id="hours">00</h2>
                                <small class="text-muted">Hours</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-label-primary rounded-3 py-3">
                                <h2 class="mb-0 fw-bold text-primary" id="minutes">00</h2>
                                <small class="text-muted">Minutes</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-label-primary rounded-3 py-3">
                                <h2 class="mb-0 fw-bold text-primary" id="seconds">00</h2>
                                <small class="text-muted">Seconds</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-5">
                        <h4 class="fw-semibold mb-3">Get Notified When We Launch</h4>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="input-group input-group-lg">
                                    <input type="email" id="email" class="form-control" placeholder="Enter your email address">
                                    <button class="btn btn-primary" onclick="subscribe()">
                                        Notify Me
                                    </button>
                                </div>
                                <div id="message" class="mt-3 small"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-muted mb-4">We are launching our new <strong>Vehicle Export Management System</strong> very soon.</p>

                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <div class="text-center">
                                <i class="ti ti-car text-primary" style="font-size: 2.5rem;"></i>
                                <p class="small mt-2 mb-0">Vehicle Management</p>
                            </div>
                            <div class="text-center">
                                <i class="ti ti-file-text text-primary" style="font-size: 2.5rem;"></i>
                                <p class="small mt-2 mb-0">Document Processing</p>
                            </div>
                            <div class="text-center">
                                <i class="ti ti-receipt text-primary" style="font-size: 2.5rem;"></i>
                                <p class="small mt-2 mb-0">Receipt & Invoices</p>
                            </div>
                            <div class="text-center">
                                <i class="ti ti-chart-bar text-primary" style="font-size: 2.5rem;"></i>
                                <p class="small mt-2 mb-0">Reports & Analytics</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="text-center mt-4">
                <small class="text-muted">
                    © {{ date('Y') }} {{\App\Helpers\Helper::getCompanyName()}} • All Rights Reserved
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
// Countdown Timer (Example: Launching in 30 days)
function startCountdown() {
    // Set your launch date here (Year, Month-1, Day)
    const launchDate = new Date("2026-06-15").getTime();

    const countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = launchDate - now;

        if (distance < 0) {
            clearInterval(countdownInterval);
            document.getElementById('countdown').innerHTML = `
                <div class="col-12">
                    <h3 class="text-success fw-bold">We are Live Now!</h3>
                </div>`;
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }, 1000);
}

// Notify Me Function
function subscribe() {
    const email = document.getElementById('email').value.trim();
    const messageDiv = document.getElementById('message');

    if (!email) {
        messageDiv.innerHTML = `<span class="text-danger">Please enter a valid email address</span>`;
        return;
    }

    // Simulate API call
    messageDiv.innerHTML = `<span class="text-success">Thank you! We'll notify you when we launch.</span>`;
    document.getElementById('email').value = '';
}

// Initialize Countdown
document.addEventListener('DOMContentLoaded', startCountdown);
</script>
@endsection
