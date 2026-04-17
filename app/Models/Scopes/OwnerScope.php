<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check() && auth()->user()->role !== 'super_admin') {
            $ownerId = auth()->user()->owner_id;

            if ($model instanceof \App\Models\Property) {
                $builder->where('owner_id', $ownerId);
            } elseif ($model instanceof \App\Models\Lease) {
                $builder->whereHas('property', function ($query) use ($ownerId) {
                    $query->where('owner_id', $ownerId);
                });
            } elseif ($model instanceof \App\Models\Invoice) {
                $builder->whereHas('lease.property', function ($query) use ($ownerId) {
                    $query->where('owner_id', $ownerId);
                });
            } elseif ($model instanceof \App\Models\Payment) {
                $builder->whereHas('invoice.lease.property', function ($query) use ($ownerId) {
                    $query->where('owner_id', $ownerId);
                });
            } elseif ($model instanceof \App\Models\Tenant) {
                $builder->whereHas('leases.property', function ($query) use ($ownerId) {
                    $query->where('owner_id', $ownerId);
                });
            }
        }
    }
}
