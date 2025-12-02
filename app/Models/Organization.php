<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Organization extends Model
{
  protected $fillable = ['name'];

  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function vendors()
  {
    return $this->hasMany(Vendor::class);
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
