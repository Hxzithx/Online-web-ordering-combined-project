<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'name',
        'cost_price',
        'price',
        'packaging_charge',
        'include_tax',
        'include_service_charge',
        'status',
        'is_synced',
        'sync_date_time',
    ];
}