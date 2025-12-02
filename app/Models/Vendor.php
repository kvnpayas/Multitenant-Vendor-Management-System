<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'name',
        'contact'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function scopeForTenant(Builder $query, $tenantId)
    {
        return $query->where('organization_id', $tenantId);
    }

}
