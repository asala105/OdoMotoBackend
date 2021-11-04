<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelOdometerPerTrip extends Model
{
    protected $table = 'fuel_odometer_values_per_trip';
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'fleet_request_id',
        'odometer_before_trip',
        'odometer_after_trip',
        'fuel_before_trip',
        'fuel_after_trip',
    ];

    public function fleet()
    {
        return $this->belongsTo(FleetRequest::class, 'fleet_request_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
}
