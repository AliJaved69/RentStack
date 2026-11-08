<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'invoice_id',
        'rent_amount',
        'security_amount',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'rent_amount' => 'decimal:2',
            'security_amount' => 'decimal:2',
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
