{{-- resources/views/dashboard/cases/partials/alteration-form.blade.php --}}
<h6 class="text-primary mb-3 mt-3">Vehicle Details</h6>
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

<h6 class="text-primary mt-4 mb-3">Alteration Details</h6>
<div class="row g-3 pb-3">
    <div class="col-md-6">
        <label class="form-label">Wheel From → To</label>
        <div class="input-group">
            <input type="text" name="alt_from" class="form-control" placeholder="Old">
            <span class="input-group-text">→</span>
            <input type="text" name="alt_to" class="form-control" placeholder="New">
        </div>
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
        <textarea name="alt_docs" class="form-control" rows="4" placeholder="List all documents submitted..."></textarea>
    </div>
</div>
