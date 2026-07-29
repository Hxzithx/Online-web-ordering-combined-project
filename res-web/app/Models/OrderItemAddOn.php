<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemAddOn extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'add_on_id',
        'quantity',
        'unit_price',
        'total_price',
        'packaging_charge',
        'discount_amount',
        'tax_amount',
        'service_charge_amount',
        'total_amount',
    ];
}