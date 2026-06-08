{{-- resources/views/dashboard/cases/partials/fitness-form.blade.php --}}
<h6 class="text-primary mb-3 mt-3">Fitness Certificate Details</h6>
<div class="row g-3 pb-3">
    <div class="col-md-6">
        <label class="form-label">From Authority</label>
        <select name="fitness_from" class="form-select">
            <option value="">Select Authority</option>
            <option value="Hub">Hub</option>
            <option value="Sindh">Sindh</option>
            <option value="KPK">KPK</option>
            <option value="Punjab">Punjab</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Documents / Remarks</label>
        <input type="text" name="docs" class="form-control" placeholder="List of submitted documents">
    </div>
</div>
