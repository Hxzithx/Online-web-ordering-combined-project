<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portion extends Model
{
    use HasFactory, Syncable;
    //
    protected $fillable = [
        'name',
        'description',
        'multiplier',
        'additional_price',
        'is_synced',
        'sync_date_time',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class);
    }
}
