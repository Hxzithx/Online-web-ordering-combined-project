<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_type_id',
        'item_id',
        'portion_id',
        'variant_id',
        'extra_service_id',
        'package_id',
        'quantity',
        'price',
        'unit_price',
        'subtotal',
        'sub_total',
        'discount_amount',
        'discount_percentage',
        'service_charge_amount',
        'service_charge_percentage',
        'tax_amount',
        'tax_percentage',
        'notes',
        'kitchen_id',
        'status',
        'added_at',
        'accepted_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'is_voided',
        'void_reason',
        'voide_reason',
        'voided_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function subtotal()
    {
        return $this->quantity * $this->unit_price;
    }

    public function orderType()
    {
        return $this->belongsTo(OrderType::class);
    }

    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function portion()
    {
        return $this->belongsTo(Portion::class);
    }

    public function voidedBy()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }
}