<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasePermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_case_id',
        'type',
        'province',
        'details',
        'province_status',
    ];

    protected $casts = [
        'province_status' => 'array',
    ];
}
