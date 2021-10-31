<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FleetRequest;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = [
        'fleet_request_id',
        'location_from',
        'location_to'
    ];

    function fleet()
    {
        return $this->belongsTo(FleetRequest::class, 'fleet_request_id', 'id');
    }
}
