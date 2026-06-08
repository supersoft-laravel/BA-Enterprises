<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_case_id',
        'tax_from',
        'tax_to',
    ];
}
