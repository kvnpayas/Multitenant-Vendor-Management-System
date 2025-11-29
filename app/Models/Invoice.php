<?php

namespace App\Models;

use App\Models\Vendor;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Invoice extends Model
{
    protected $fillable = [
        'organization_id',
        'vendor_id',
        'amount',
        'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeForTenant(Builder $query, $tenantId)
    {
        return $query->where('organization_id', $tenantId);
    }
}
