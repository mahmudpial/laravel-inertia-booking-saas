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
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // super_admin sees everything; customer isolation is by user_id via
        // relationships, not business_id — neither should hit the tenant filter.
        if (in_array($user->role, ['super_admin', 'customer'])) {
            return;
        }

        if ($user->business_id) {
            $builder->where($model->getTable() . '.business_id', $user->business_id);
        } else {
            // owner (or future business-side roles) with no business_id yet:
            // fail closed, not open.
            $builder->whereRaw('1 = 0');
        }
    }
}
