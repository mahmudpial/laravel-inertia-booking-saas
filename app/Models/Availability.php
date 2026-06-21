<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['business_id', 'day_of_week', 'start_time', 'end_time', 'is_open'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}