<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToOwner;

    protected $fillable = ['invoice_id', 'amount', 'payment_method', 'payment_date'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
