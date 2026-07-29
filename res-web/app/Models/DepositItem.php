<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositItem extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'deposit_item_name',
        'deposit_item_description',
        'deposit_price',
        'description',
        'is_active',
        'is_synced',
        'sync_date_time',
    ];
}