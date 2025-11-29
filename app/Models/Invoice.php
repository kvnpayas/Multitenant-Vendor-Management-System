<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'organization_id',
        'vendor_id',
        'amount',
        'status'
    ];
}
