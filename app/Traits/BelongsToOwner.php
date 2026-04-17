<?php

namespace App\Traits;

use App\Models\Scopes\OwnerScope;

trait BelongsToOwner
{
    protected static function booted()
    {
        static::addGlobalScope(new OwnerScope);
    }
}
