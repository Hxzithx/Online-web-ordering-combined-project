<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'code',
        'name',
        'legal_name',
        'address_line_1',
        'address_line_2',
        'city',
        'district',
        'province',
        'country',
        'phone',
        'email',
        'currency_code',
        'tax_profile_id',
        'is_active',
        'is_live',
        'created_by',
        'is_synced',
        'sync_date_time',
    ];
}