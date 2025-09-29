<?php

namespace App\Traits;



use Illuminate\Database\Eloquent\Builder;

trait CommonQueryScopes
{
    public function scopeFilterByDate(Builder $q, ?string $from = null, ?string $to = null): Builder
    {
        if ($from) $q->where('date', '>=', $from);
        if ($to)   $q->where('date', '<=', $to);
        return $q;
    }

    public function scopeSearchByTitle(Builder $q, ?string $term = null): Builder
    {
        if ($term) $q->where('title', 'LIKE', "%{$term}%");
        return $q;
    }
}
