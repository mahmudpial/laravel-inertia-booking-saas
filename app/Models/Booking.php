<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'business_id',
        'user_id',
        'service_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function addons()
    {
        return $this->belongsToMany(ServiceAddon::class, 'booking_service_addon');
    }
}