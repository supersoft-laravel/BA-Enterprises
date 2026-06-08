<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseInsurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_case_id',
        'details',
    ];
}
