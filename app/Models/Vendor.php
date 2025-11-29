<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
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

}
