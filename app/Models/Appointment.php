<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'service_id', 'appointment_date', 'status', 'notes'];
public function patient() {
    return $this->belongsTo(User::class, 'patient_id');
}

public function doctor() {
    return $this->belongsTo(User::class, 'doctor_id');
}

public function user()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}

