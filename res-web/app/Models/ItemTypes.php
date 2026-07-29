<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTypes extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'name',
        'code',
        'affected_stock',
        'is_sellable',
        'requires_recipe',
        'is_active',
        'is_synced',
        'sync_date_time',
    ];
}