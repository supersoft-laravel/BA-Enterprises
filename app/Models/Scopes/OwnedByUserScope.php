<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnedByUserScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Only filter when a user is logged in — public/unauthenticated routes skip this
        if (auth()->check()) {
            $builder->where($model->getTable() . '.created_by', auth()->id());
        }
    }
}
