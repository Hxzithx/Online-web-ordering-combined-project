<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait SyncsData
{
    
    public function updateSyncsStatus(Request $request)
    {
       $fields = $request->validate([
           'ids' => 'required|array',
           'ids.*' => 'required',
           'is_synced' => 'required|boolean',
           'sync_date_time' => 'nullable|date_format:Y-m-d H:i:s'
       ]);

       $modelClass = $this->model;

       if(!$modelClass){
        return response()->json([
          'message' => 'Model class not defined in controller',
        ],500);
       }

       $modelClass::whereIn('id', $fields['ids'])->update([
           'is_synced' => $fields['is_synced'],
           'sync_date_time' => $fields['sync_date_time'] ?? now(),
       ]);

       return response()->json([
          'message' => 'Sync status updated succefully',
       ]);
    }
}