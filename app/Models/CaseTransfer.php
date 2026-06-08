<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_case_id',

        // From
        'from_name',
        'from_s_o',
        'from_nic',

        // To
        'to_name',
        'to_s_o',
        'to_nic',
    ];

    public function vehicleCase()
    {
        return $this->belongsTo(VehicleCase::class, 'vehicle_case_id');
    }
}
