<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_code',
        'name',
        'mobile',
    ];

    public function vehicleCases()
    {
        return $this->hasMany(VehicleCase::class, 'customer_id');
    }

    public function billing()
    {
        return $this->hasOne(Billing::class, 'customer_id');
    }
}
