<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Syncable, HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'is_synced',
        'sync_date_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}