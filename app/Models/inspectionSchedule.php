<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'status_id', 'inspection_type', 'date', 'driver_id', 'vehicle_id'
    ];
}
