<?php

namespace App\Models;

use App\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use BelongsToTenant;
    protected $fillable = ['business_id', 'name', 'email', 'role', 'is_active'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}