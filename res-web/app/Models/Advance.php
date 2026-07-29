<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_number',
        'order_id',
        'reservation_id',
        'customer_id',
        'payment_method_id',
        'branch_id',
        'order_type_id',
        'type',
        'reference_id',
        'direction',
        'amount',
        'balance_amount',
        'purpose',
        'advance_date',
        'event_date',
        'user_id',
        'status',
        'is_cancelled',
        'cancelled_by',
        'cancellation_reason',
        'cancelled_at',
        'notes',
        'created_by',
    ];
}