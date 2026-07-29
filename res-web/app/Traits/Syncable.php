<?php

namespace App\Traits;


trait Syncable
{
    //when the model is updated this function automatically called and update the other systems POS/UBER Eats/Pick Me
    public function bootSyncable()
    {
        static::updating(function ($model){
             if($model->isDirty('is_synced')){
                $model->is_synced = 0;
             }
        });
    }
}