<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'name',
        'description',
        'requires_table',
        'allow_service_charge',
        'allow_packaging_charge',
        'allow_split_payment',
        'allow_discounts',
        'tax_inclusive',
        'steward_required',
        'is_active',
        'is_synced',
        'sync_date_time',
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class);
    }

    public function orderItem()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class);
    }
    
}