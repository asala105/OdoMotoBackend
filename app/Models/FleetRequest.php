<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Destination;

class FleetRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'department_id',
        'date',
        'start_time',
        'end_time',
        'purpose'
    ];

    function destinations()
    {
        return $this->hasMany(Destination::class, 'fleet_request_id', 'id');
    }
    function driver()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }
    function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }
    function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
