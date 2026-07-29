<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
     use HasFactory, Syncable;
 
    protected $fillable = [
        'category_id',
        'portions_id',
        'variant_id',
        'item_type',
        'base_name',
        'full_name',
        'description',
        'status',
        'kitchen_id',
        'image_url',
        'include_tax',
        'include_service_charge',
        'user_id',
        'is_synced',
        'sync_date_time',
    ];    
 
    public function portion()
    {
        return $this->belongsTo(Portion::class, 'portions_id');
    }
 
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function category()
    {
    return $this->belongsTo(Category::class);  
    }
 
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ItemPrices()
    {
        return $this->hasMany(ItemPrice::class); 
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class);
    }
}
