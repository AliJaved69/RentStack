<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use BelongsToOwner;

    protected $fillable = ['name', 'phone', 'cnic_or_id'];

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }
}
