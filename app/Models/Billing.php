<?php

namespace App\Models;

use App\Models\Scopes\OwnedByUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'billings';

    protected $fillable = [
        'vehicle_case_id',
        'customer_id',
        'created_by',
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

    protected static function booted(): void
    {
        static::addGlobalScope(new OwnedByUserScope());

        static::creating(function ($model) {
            if (is_null($model->created_by) && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(BillingItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function vehicleCase()
    {
        return $this->belongsTo(VehicleCase::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
