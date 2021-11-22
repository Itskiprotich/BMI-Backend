<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_health',
        'on_diet',
        'on_drugs',
        'comments',
        'patient_id'
    ];
}
