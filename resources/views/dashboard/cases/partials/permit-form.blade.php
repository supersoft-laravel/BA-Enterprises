{{-- resources/views/dashboard/cases/partials/permit-form.blade.php --}}
<h6 class="text-primary mb-3 mt-3">Permit Details</h6>
<div class="row g-3 pb-3">
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
        <input type="text" name="docs" class="form-control" placeholder="List of documents">
    </div>
    <div class="col-md-6">
        <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
        <input type="date" name="expiry_date" class="form-control">
    </div>
</div>
