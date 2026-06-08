<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseActivity extends Model
{
    use HasFactory;

    protected $table = 'case_activities';

    protected $fillable = [
        'case_id',
        'activity_type',
        'description',
    ];
}
