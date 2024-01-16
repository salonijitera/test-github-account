<?php

namespace App\Models\Traits;

use App\EloquentBuilders\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

trait FilterQueryBuilder
{
    public function newEloquentBuilder($query): Builder
    {
        return new FilterBuilder($query);
    }
}
