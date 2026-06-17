<?php

namespace App\Models;

use App\Models\Scopes\OwnedByUserScope;
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
        'created_by',
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

    public function vehicleCases()
    {
        return $this->hasMany(VehicleCase::class, 'customer_id');
    }

    public function billing()
    {
        return $this->hasOne(Billing::class, 'customer_id');
    }
}
