<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'name',
        'code',
        'description',
        'cost_price',
        'actual_price',
        'discounted_price',
        'price',
        'status',
        'is_synced',
        'sync_date_time',
    ];
}