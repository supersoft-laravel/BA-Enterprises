<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'billings';

    protected $fillable = [
        'vehicle_case_id',
        'billing_type',
        'bill_no',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'billing_date',
        'billing_name',
        'description',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(BillingItem::class);
    }

    public function vehicleCase()
    {
        return $this->belongsTo(VehicleCase::class);
    }
}
