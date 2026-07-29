<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory, Syncable;

    protected $fillable = [
        'name',  //Cash,Card,Uer,PickMe
        'code',  //CASH,CARD,UBER,PICKME
        'type',  //cash,card,online
        'is_online',
        'requires_reference',
        'is_active',
        'sort_order',
        'is_synced',
        'sync_date_time',
    ];
}