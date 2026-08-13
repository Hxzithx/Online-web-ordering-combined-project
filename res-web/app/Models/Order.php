<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'branch_id',
        'order_number',
        'sub_order_id',
        'table_id',
        'table_merge_id',
        'user_id',
        'steward_id',
        'order_type_id',
        'customer_name',
        'customer_id',
        'status',
        'reserved_seats',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'service_charge',
        'packaging_charge',
        'payment_status',
        'payment_method',
        'opened_at',
        'confirmed_at',
        'prepared_at',
        'served_at',
        'closed_at',
        'cancelled_at',
        'notes',
        'cashier_id',
        'opened_by',
        'closed_by',
        'void_reason',
        'cancel_reason',
        'cancelled_by',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()                          
    {
        return $this->hasMany(OrderItem::class);     
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tableMerge()
    {
        return $this->belongsTo(TableMerge::class);
    }

    public function steward()
    {
        return $this->belongsTo(Steward::class);
    }

    public function orderType()
    {
        return $this->belongsTo(OrderType::class);
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

}
