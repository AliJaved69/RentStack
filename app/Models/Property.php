<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use BelongsToOwner;

    protected $fillable = ['owner_id', 'title', 'address', 'status'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }
}
