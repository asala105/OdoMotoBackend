<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
