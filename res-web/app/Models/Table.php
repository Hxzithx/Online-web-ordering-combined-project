<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    //
    protected $fillable = [
      'table_number',
      'capacity',
      'reserved_seats',
      'zone',
      'status',
      'description',
      'location',
      'table_type',
      'min_occupancy',
      'max_occupancy',
      'features',
      'user_id',
      'is_synced',
      'sync_date_time',
    ];

    protected $casts = [
        'features' => 'array',
        'min_occupancy' => 'integer',
        'max_occupancy' => 'integer',
        'reserved_seats' => 'integer',
        'capacity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tableMergesAsTable1()
    {
        return $this->hasMany(TableMerge::class, 'table_id_1');
    }

    public function tableMergesAsTable2()
    {
        return $this->hasMany(TableMerge::class, 'table_id_2');
    }

    public function tableMergesAsTable3()
    {
        return $this->hasMany(TableMerge::class, 'table_id_3');
    }

    //status available
    public function statusAvailable($query)
    {
        return $query->where('status', 'available');
    }

    //status occupied
    public function statusOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    //status reserved
    public function statusReserved($query)
    {
        return $query->where('status', 'reserved');
    }
    
    //check if table can be merged
    public function canBeMerged() : bool
    {
        return $this->status === 'available' && $this->reserved_seats === 0;
    }
      
    //get available seats
    public function getAvailableSeatsAttribute()
    {
        return $this->capacity - $this->reserved_seats;
    }
}
