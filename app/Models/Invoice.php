<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use BelongsToOwner;

    protected $fillable = ['lease_id', 'billing_month', 'amount_due', 'amount_paid', 'status'];

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
