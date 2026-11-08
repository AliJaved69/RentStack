<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'owner_id',
        'title',
        'address',
        'image_path',
        'status',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function units()
    {
        return $this->hasMany(PropertyUnit::class);
    }

    public function occupiedUnits()
    {
        return $this->hasMany(PropertyUnit::class)->where('status', 'occupied');
    }

    public function vacantUnits()
    {
        return $this->hasMany(PropertyUnit::class)->where('status', 'vacant');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    /**
     * Get accessible image URL or null
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }

        return null;
    }
}
