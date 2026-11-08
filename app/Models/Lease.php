<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'property_id',
        'unit_id',
        'tenant_id',
        'start_date',
        'base_rent',
        'security_deposit_expected',
        'security_deposit_paid',
        'e_stamp_file_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'base_rent' => 'decimal:2',
            'security_deposit_expected' => 'decimal:2',
            'security_deposit_paid' => 'decimal:2',
            'start_date' => 'date',
        ];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class)->latestOfMany();
    }

    public function getSecurityRemainingAttribute(): float
    {
        return (float) max(0, $this->security_deposit_expected - $this->security_deposit_paid);
    }

    /**
     * Get Portion / Shop label or fallback
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->unit) {
            return $this->property->title . ' → ' . $this->unit->name;
        }

        return $this->property->title . ' (Full Property)';
    }
}
