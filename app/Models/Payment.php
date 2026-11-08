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

    /**
     * Get one-click WhatsApp receipt link for this payment.
     */
    public function getWhatsappReceiptUrlAttribute(): ?string
    {
        try {
            $tenant = $this->invoice?->lease?->tenant;
            if (!$tenant || empty($tenant->phone)) {
                return null;
            }

            $phone = preg_replace('/[^0-9]/', '', $tenant->phone);
            if (str_starts_with($phone, '03')) {
                $phone = '92' . substr($phone, 1);
            }

            $text = app(\App\Services\RentCycleService::class)->buildWhatsAppReceipt($this);
            return "https://wa.me/{$phone}?text=" . rawurlencode($text);
        } catch (\Throwable $t) {
            return null;
        }
    }
}
