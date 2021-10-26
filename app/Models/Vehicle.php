<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id',
        'category',
        'registration_code',
        'plate_number',
        'model',
        'weight',
        'odometer',
        'fuel_level',
        'is_rented',
        'driver_license_requirements'
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }

    public function inspectionSchedules()
    {
        return $this->hasMany(InspectionSchedule::class, 'vehicle_id', 'id');
    }
}
