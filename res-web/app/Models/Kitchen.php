<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Syncable;

class Kitchen extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'name',
        'location',
        'status',
        'user_id',
        'is_synced',
        'sync_date_time',
    ];

}