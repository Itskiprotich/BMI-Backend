<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    use HasFactory;
    protected $fillable = [
        'visit_date',
        'height',
        'weight',
        'bmi',
        'patient_id'
    ];
}
