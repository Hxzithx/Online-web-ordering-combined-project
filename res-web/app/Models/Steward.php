<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Steward extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'user_id',
        'branch_id',
        'name',
        'employee_id',
        'is_active',
        'status',
        'is_synced',
        'sync_date_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}