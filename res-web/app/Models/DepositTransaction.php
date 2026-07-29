<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'cusromer_id',
        'deposit_item_id',
        'item_id',
        'extra_service_id',
        'order_id',
        'quantity',
        'deposit_amount',
        'return_date',
        'refund_amount',
        'refund_date',
        'status',
        'notes',
        'isOverdue',
        'refund_by',
    ];
}