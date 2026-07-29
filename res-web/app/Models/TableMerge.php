<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class TableMerge extends Model
{
    use HasFactory;
    
    protected $fillable = [
         'table_id_1',
         'table_id_2',
         'table_id_3',
         'merge_at',
         'user_id',
    ];

    public function table1()
    {
        return $this->belongsTo(Table::class, 'table_id_1');
    }

    public function table2()
    {
        return $this->belongsTo(Table::class, 'table_id_2');
    }

    public function table3()
    {
        return $this->belongsTo(Table::class, 'table_id_3');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
