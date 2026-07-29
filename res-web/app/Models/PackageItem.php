<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'package_id',
        'item_id',
        'extra_service_id',
        'quantity',
        'cost_price',
        'status',
        'is_synced',
        'sync_date_time',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function extraService()
    {
        return $this->belongsTo(ExtraService::class);
    }
}