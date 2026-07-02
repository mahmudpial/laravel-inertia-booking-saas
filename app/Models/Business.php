<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = ['user_id', 'name', 'slug', 'logo', 'address', 'phone', 'status'];


    // ইউজারের সাথে রিলেশন
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }
    // একটি বিজনেসের অনেকগুলো বুকিং থাকতে পারে (Has Many)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}