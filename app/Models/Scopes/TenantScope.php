<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //user role is super admin, then no need to apply the scope
        if (Auth::check() && Auth::user()->role !== 'super_admin') {
            if (Auth::user()->business_id) {
                $builder->where($model->getTable() . '.business_id', Auth::user()->business_id);
            } else {
                $builder->whereRaw('1 = 0');
            }
        }
    }
}
