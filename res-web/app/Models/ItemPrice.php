<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'item_id',
        'variant_id',
        'portion_id',
        'order_type_id',
        'cost_price',
        'price',
        'package_charge',
        'have_deposit_item',
        'deposit_item_id',
        'deposit_amount',
        'is_synced',
        'sync_date_time',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function portion()
    {
        return $this->belongsTo(Portion::class);
    }
    
    public function orderType()
    {
        return $this->belongsTo(OrderType::class);
    }
}
