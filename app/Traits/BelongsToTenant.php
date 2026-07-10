<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

trait BelongsToTenant
{
    /**
    * Boot the trait and apply global scope + auto-binding.
    */
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            $user = Auth::user();

            if (!$user || $user->role === 'super_admin' || $user->role === 'customer') {
                return;
            }

            if (!$user->business_id) {
                throw new RuntimeException('Tenant models require an authenticated user with a business_id.');
            }

            $model->business_id = $model->business_id ?? $user->business_id;
        });
    }
}
