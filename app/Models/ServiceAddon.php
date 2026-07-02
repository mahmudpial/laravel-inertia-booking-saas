<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAddon extends Model
{
    /**
     * যে কলামগুলো Mass Assignment এর মাধ্যমে সেভ করা যাবে।
     * এগুলো ছাড়া Laravel অন্য কোনো ডাটা সেভ করতে দিবে না।
     */
    protected $fillable = [
        'service_id',
        'name',
        'price',
        'duration_minutes'
    ];

    /**
     * এটি কোন সার্ভিসের অ্যাড-অন তা বোঝাতে রিলেশন
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}