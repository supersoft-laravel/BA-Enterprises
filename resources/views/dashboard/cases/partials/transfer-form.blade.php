{{-- resources/views/dashboard/cases/partials/transfer-form.blade.php --}}
<h6 class="text-primary mb-3 mt-3">Transfer From</h6>
<div class="row g-3 pb-3">
    <div class="col-md-6">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="from_name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">S/O</label>
        <input type="text" name="from_s_o" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">NIC <span class="text-danger">*</span></label>
        <input type="text" name="from_nic" class="form-control" placeholder="12345-1234567-1" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Biometric</label>
        <div class="form-check">
            <input type="checkbox" name="from_biometric" value="1" class="form-check-input">
            <label class="form-check-label">Biometric Done</label>
        </div>
    </div>
</div>

<h6 class="text-primary mt-4 mb-3">Transfer To</h6>
<div class="row g-3 pb-3">
    <div class="col-md-6">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="to_name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">S/O</label>
        <input type="text" name="to_s_o" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">NIC <span class="text-danger">*</span></label>
        <input type="text" name="to_nic" class="form-control" placeholder="12345-1234567-1" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Biometric</label>
        <div class="form-check">
            <input type="checkbox" name="to_biometric" value="1" class="form-check-input">
            <label class="form-check-label">Biometric Done</label>
        </div>
    </div>
</div>

<h6 class="text-primary mt-4 mb-3">Vehicle Details</h6>
<div class="row g-3 pb-3">
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
    <div class="col-md-6">
        <label class="form-label">Weight</label>
        <input type="text" name="weight" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Last Tax</label>
        <input type="text" name="last_tax" class="form-control">
    </div>
</div>
