@extends('layouts.master')
@section('title', __('Create Case'))

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.cases.index') }}">{{ __('Cases') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Create New Case') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header bg-label-primary border-bottom py-3">
                <h5 class="mb-0 text-primary fw-semibold">
                    <i class="ti ti-file-plus me-2"></i>
                    New Vehicle Case Registration
                </h5>
                <p class="mb-0 text-muted small">Multi-step wizard • Auto-saves progress • Matches your database schema</p>
            </div>

            <div class="card-body p-4">
                {{-- Progress Bar (Vuexy Style) --}}
                <div class="progress mb-4" style="height: 8px; border-radius: 50px;">
                    <div id="progressBar"
                         class="progress-bar bg-primary rounded-pill"
                         role="progressbar"
                         style="width: 33.33%"
                         aria-valuenow="33"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-4 text-center">
                    <div class="step-indicator active" id="indicator1">
                        <div class="step-circle">1</div>
                        <small class="d-block mt-1 fw-medium">Basic Info</small>
                    </div>
                    <div class="step-indicator" id="indicator2">
                        <div class="step-circle">2</div>
                        <small class="d-block mt-1 fw-medium">Case Info</small>
                    </div>
                    <div class="step-indicator" id="indicator3">
                        <div class="step-circle">3</div>
                        <small class="d-block mt-1 fw-medium">Work Details</small>
                    </div>
                </div>

                <form id="caseForm" method="POST" action="{{ route('dashboard.cases.store') }}">
                    @csrf

                    {{-- ================= STEP 1 ================= --}}
                    <div class="step-content" id="step1">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-car me-2"></i>Step 1 - Basic Information
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Vehicle Registration No. <span class="text-danger">*</span></label>
                                <input type="text" name="vehicle_reg_no" class="form-control" placeholder="ABC-1234" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Make</label>
                                <input type="text" name="make" class="form-control" placeholder="Toyota / Honda">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Year</label>
                                <input type="number" name="year" class="form-control" placeholder="2025" min="1990" max="2030">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Submitted By <span class="text-danger">*</span></label>
                                <input type="text" name="submitted_by" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile No. <span class="text-danger">*</span></label>
                                <input type="tel" name="mobile_no" class="form-control" placeholder="03XX-XXXXXXX" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Submission Date <span class="text-danger">*</span></label>
                                <input type="date" name="submission_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tentative Case Return Date</label>
                                <input type="date" name="tentative_return_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- ================= STEP 2 ================= --}}
                    <div class="step-content d-none" id="step2">
                        <h5 class="mb-3 text-primary">
                            <i class="ti ti-clipboard me-2"></i>Step 2 - Case Reference & Work Type
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Case Refer To <span class="text-danger">*</span></label>
                                <select name="case_refer_to" class="form-select" required>
                                    <option value="">Select Office</option>
                                    <option value="Karachi">Karachi</option>
                                    <option value="Lasbella">Lasbella</option>
                                    <option value="Quetta">Quetta</option>
                                    <option value="Peshawar">Peshawar</option>
                                    <option value="Gilgit">Gilgit</option>
                                    <option value="Punjab">Punjab</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Work To Be Done <span class="text-danger">*</span></label>
                                <select name="work_type" id="work_type" class="form-select" required>
                                    <option value="">Select Work Type</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="alteration">Alteration</option>
                                    <option value="tax">Tax</option>
                                    <option value="insurance">Insurance</option>
                                    <option value="permit">Permit</option>
                                    <option value="fitness">Fitness</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ================= STEP 3 ================= --}}
                    <div class="step-content d-none" id="step3">
                        <h5 class="mb-3 text-primary" id="step3Title">
                            <i class="ti ti-file-text me-2"></i>Step 3 - Work Details
                        </h5>

                        {{-- TRANSFER SECTION --}}
                        <div id="transfer_section" class="detail-section d-none">
                            <h6 class="mb-3 text-dark">Transfer From</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="from_name" class="form-control" placeholder="Full Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">S/O</label>
                                    <input type="text" name="from_s_o" class="form-control" placeholder="Father Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NIC <span class="text-danger">*</span></label>
                                    <input type="text" name="from_nic" class="form-control" placeholder="12345-1234567-1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Biometric</label>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="from_biometric" value="1" class="form-check-input">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-3 text-dark">Transfer To</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="to_name" class="form-control" placeholder="Full Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">S/O</label>
                                    <input type="text" name="to_s_o" class="form-control" placeholder="Father Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NIC <span class="text-danger">*</span></label>
                                    <input type="text" name="to_nic" class="form-control" placeholder="12345-1234567-1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Biometric</label>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="to_biometric" value="1" class="form-check-input">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-3 text-dark">Vehicle Details</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Engine No</label>
                                    <input type="text" name="engine_no" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Chassis No</label>
                                    <input type="text" name="chassis_no" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Wheels</label>
                                    <input type="text" name="wheels" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Weight</label>
                                    <input type="text" name="weight" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Tax</label>
                                    <input type="text" name="last_tax" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- ALTERATION SECTION --}}
                        <div id="alteration_section" class="detail-section d-none">
                            <h6 class="mb-3 text-dark">Vehicle Details</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">Engine No</label>
                                    <input type="text" name="engine_no" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Chassis No</label>
                                    <input type="text" name="chassis_no" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Wheels</label>
                                    <input type="text" name="wheels" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Weight</label>
                                    <input type="text" name="weight" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Tax</label>
                                    <input type="text" name="last_tax" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Other</label>
                                    <input type="text" name="other" class="form-control">
                                </div>
                            </div>

                            <h6 class="mb-3 text-dark">Details of Alteration</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Wheel From</label>
                                    <input type="text" name="alt_from" class="form-control" placeholder="Old spec">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Wheel To</label>
                                    <input type="text" name="alt_to" class="form-control" placeholder="New spec">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Altered Wheels</label>
                                    <input type="text" name="alt_wheels" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Engine</label>
                                    <input type="text" name="alt_engine" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Body</label>
                                    <input type="text" name="alt_body" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Documents / Remarks</label>
                                    <textarea name="alt_docs" class="form-control" rows="3" placeholder="List of documents submitted..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- TAX SECTION --}}
                        <div id="tax_section" class="detail-section d-none">
                            <h6 class="mb-3 text-dark">Tax Period</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">From</label>
                                    <input type="date" name="tax_from" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">To</label>
                                    <input type="date" name="tax_to" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- INSURANCE SECTION --}}
                        <div id="insurance_section" class="detail-section d-none">
                            <h6 class="mb-3 text-dark">Insurance Details</h6>
                            <textarea name="details" class="form-control" rows="6" placeholder="Insurance company, policy number, coverage details, expiry date etc..."></textarea>
                        </div>

                        {{-- PERMIT SECTION --}}
                        <div id="permit_section" class="detail-section d-none">
                            <h6 class="mb-3 text-dark">Permit Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Region</label>
                                    <select name="region" class="form-select">
                                        <option value="">Select Region</option>
                                        <option value="All">All Pakistan</option>
                                        <option value="KPK">KPK</option>
                                        <option value="Punjab">Punjab</option>
                                        <option value="Sindh">Sindh</option>
                                        <option value="Balochistan">Balochistan</option>
                                        <option value="RTA/PTA">RTA / PTA</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Documents / Remarks</label>
                                    <input type="text" name="docs" class="form-control" placeholder="List of required documents">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" name="expiry_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- FITNESS SECTION --}}
                        <div id="fitness_section" class="detail-section d-none">
                            <h6 class="mb-3 text-dark">Fitness Certificate</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">From Authority</label>
                                    <select name="fitness_from" class="form-select">
                                        <option value="">Select</option>
                                        <option value="Hub">Hub</option>
                                        <option value="Sindh">Sindh</option>
                                        <option value="KPK">KPK</option>
                                        <option value="Punjab">Punjab</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Documents / Remarks</label>
                                    <input type="text" name="docs" class="form-control" placeholder="List of documents">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                        <button type="button" id="prevBtn" class="btn btn-label-secondary d-none">
                            <i class="ti ti-chevron-left me-2"></i>Back
                        </button>

                        <button type="button" id="nextBtn" class="btn btn-primary">
                            Next <i class="ti ti-chevron-right ms-2"></i>
                        </button>

                        <button type="submit" id="submitBtn" class="btn btn-success d-none">
                            <i class="ti ti-send me-2"></i>Submit Case
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    let currentStep = 1;
    const totalSteps = 3;

    // Show step with smooth transition
    function showStep(step) {
        document.querySelectorAll('.step-content').forEach(el => {
            el.classList.add('d-none');
        });
        document.getElementById('step' + step).classList.remove('d-none');

        // Progress bar
        const percent = Math.round((step / totalSteps) * 100);
        const progressBar = document.getElementById('progressBar');
        progressBar.style.width = percent + '%';
        progressBar.setAttribute('aria-valuenow', percent);

        // Step indicators
        for (let i = 1; i <= totalSteps; i++) {
            const indicator = document.getElementById('indicator' + i);
            if (i < step) {
                indicator.classList.add('active', 'completed');
            } else if (i === step) {
                indicator.classList.add('active');
                indicator.classList.remove('completed');
            } else {
                indicator.classList.remove('active', 'completed');
            }
        }

        // Buttons
        document.getElementById('prevBtn').classList.toggle('d-none', step === 1);
        document.getElementById('nextBtn').classList.toggle('d-none', step === totalSteps);
        document.getElementById('submitBtn').classList.toggle('d-none', step !== totalSteps);
    }

    // Next button
    document.getElementById('nextBtn').addEventListener('click', function () {
        if (currentStep < totalSteps) {
            // Optional: simple validation for step 2
            if (currentStep === 2) {
                const workType = document.getElementById('work_type').value;
                if (!workType) {
                    alert('Please select Work Type before proceeding.');
                    return;
                }
            }
            currentStep++;
            showStep(currentStep);
        }
    });

    // Previous button
    document.getElementById('prevBtn').addEventListener('click', function () {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Work Type Change → Show only relevant section + enable/disable inputs (prevents duplicate field submission)
    function toggleDetailSections(selectedType) {
        const allSections = document.querySelectorAll('.detail-section');

        allSections.forEach(section => {
            const isActive = section.id === selectedType + '_section';

            // Hide / show
            section.classList.toggle('d-none', !isActive);

            // Enable only active section inputs (disabled fields are NOT submitted)
            section.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !isActive;
                // Optional: clear values when switching (prevents old data)
                if (!isActive) input.value = '';
            });
        });

        // Update step title dynamically
        const titleEl = document.getElementById('step3Title');
        if (selectedType) {
            titleEl.innerHTML = `<i class="ti ti-file-text me-2"></i>Step 3 - ${selectedType.charAt(0).toUpperCase() + selectedType.slice(1)} Details`;
        }
    }

    document.getElementById('work_type').addEventListener('change', function () {
        toggleDetailSections(this.value);
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        showStep(currentStep);

        // If someone refreshes with a value already selected (rare in create)
        const initialWorkType = document.getElementById('work_type').value;
        if (initialWorkType) {
            toggleDetailSections(initialWorkType);
        }
    });

    // Optional: Auto-generate case number preview (you can remove if backend handles)
    console.log('%c✅ Vuexy-styled multi-step wizard ready. All fields match your migration schema.', 'color: #00b8d4; font-weight: bold');
</script>
@endsection
