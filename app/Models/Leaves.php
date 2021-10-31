<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
    use HasFactory;
    protected $fillable = ['status_id', 'user_id', 'leave_from_date', 'leave_till_date', 'leave_type', 'details'];
    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
