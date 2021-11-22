<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    public function patients()
    {
        return $this->belongsTo(Patient::class);
    }

    protected $fillable = [
        'general_health',
        'on_diet',
        'on_drugs',
        'comments',
        'patient_id'
    ];
}
