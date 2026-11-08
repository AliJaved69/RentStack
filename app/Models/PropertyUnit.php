<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    use HasFactory, BelongsToOwner;

    protected $fillable = [
        'property_id',
        'name',
        'type',
        'default_rent',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'default_rent' => 'decimal:2',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class, 'unit_id');
    }

    public function activeLease()
    {
        return $this->hasOne(Lease::class, 'unit_id')->where('status', 'active');
    }

    public function currentTenant()
    {
        return $this->hasOneThrough(
            Tenant::class,
            Lease::class,
            'unit_id',
            'id',
            'id',
            'tenant_id'
        )->where('leases.status', 'active');
    }
}

