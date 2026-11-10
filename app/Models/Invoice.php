<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'lease_id',
        'billing_month',
        'base_rent',
        'arrears',
        'utility_charges',
        'utility_notes',
        'security_due',
        'security_paid',
        'amount_due',
        'amount_paid',
        'status',
    ];

    protected $appends = [
        'remaining_balance',
        'month_name',
    ];

    protected function casts(): array
    {
        return [
            'billing_month' => 'date',
            'base_rent' => 'decimal:2',
            'arrears' => 'decimal:2',
            'utility_charges' => 'decimal:2',
            'security_due' => 'decimal:2',
            'security_paid' => 'decimal:2',
            'amount_due' => 'decimal:2',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingBalanceAttribute(): float
    {
        return (float) max(0, $this->amount_due - $this->amount_paid);
    }

    public function getMonthNameAttribute(): string
    {
        return $this->billing_month ? $this->billing_month->format('F Y') : '';
    }
}
