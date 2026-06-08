<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCase extends Model
{
    use HasFactory;

    protected $table = 'vehicle_cases';

    protected $fillable = [
        // Common Info
        'city',
        'vehicle_no',
        'vehicle_make',
        'vehicle_model',
        'engine_no',
        'chassis_no',

        // Party Info
        'party_name',
        'party_mobile',

        // Case Info
        'case_date',
        'comment',

        // Tracking
        'submitted_at',
    ];

    public function transfer()
    {
        return $this->hasOne(CaseTransfer::class, 'vehicle_case_id');
    }

    public function alteration()
    {
        return $this->hasOne(CaseAlteration::class, 'vehicle_case_id');
    }

    public function tax()
    {
        return $this->hasOne(CaseTax::class, 'vehicle_case_id');
    }

    public function insurance()
    {
        return $this->hasOne(CaseInsurance::class, 'vehicle_case_id');
    }

    public function permit()
    {
        return $this->hasOne(CasePermit::class, 'vehicle_case_id');
    }

    public function fitness()
    {
        return $this->hasOne(CaseFitness::class, 'vehicle_case_id');
    }

    public function fileReturn()
    {
        return $this->hasOne(CaseFileReturn::class, 'vehicle_case_id');
    }

    public function other()
    {
        return $this->hasOne(CaseOther::class, 'vehicle_case_id');
    }

    public function billing()
    {
        return $this->hasOne(Billing::class, 'vehicle_case_id');
    }

    public function activities()
    {
        return $this->hasMany(CaseActivity::class, 'case_id');
    }
}
