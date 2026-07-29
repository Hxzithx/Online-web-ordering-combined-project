<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraService extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'serviceName',
        'description',
        'default_price',
        'cost_price',
        'is_tax_included',
        'is_service_charge_included',
        'applicable_order_types_id',
        'is_active',
        'measure_unit',
        'is_synced',
        'sync_date_time',
    ];
}