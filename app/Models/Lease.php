<?php

namespace App\Models;

use App\Traits\BelongsToOwner;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use BelongsToOwner;

    protected $fillable = [
        'property_id', 'tenant_id', 'start_date', 'base_rent',
        'security_deposit_expected', 'security_deposit_paid',
        'e_stamp_file_path', 'status'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
