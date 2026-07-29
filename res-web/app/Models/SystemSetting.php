<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'name',
        'location',
        'tax_percentage',
        'service_charge_percentage',
        'key',
        'value',
        'group',
        'data_type',
        'is_editable',
        'is_synced',
        'sync_date_time',
    ];
}