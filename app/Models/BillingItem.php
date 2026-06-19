<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    use HasFactory;

    protected $table = 'billing_items';

    protected $fillable = [
        'billing_id',
        'vehicle_case_id',
        'item_name',
        'item_amount',
        'service_date',
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id');
    }

    public function vehicleCase()
    {
        return $this->belongsTo(VehicleCase::class, 'vehicle_case_id');
    }
}
