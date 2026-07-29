<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Traits\SyncsData;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class TableController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = Table::class;

    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Table::class);

           $table = Table::all();

           return response()->json([
              'message' => 'Tables retrieved successfully',
              'tables' => $table,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view tables',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving tables',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{ 
           Gate::authorize('create', Table::class);

           $fields = $request->validate([
              'table_number' => 'required|string|max:255|unique:tables,table_number',
              'capacity' => 'required|integer|min:0|max:50',
              'reserved_seats' => 'nullable|integer|min:0',
              'zone' => 'required|string|max:255',
              'status' => 'required|string|in:available,occupied,reserved,merged',
              'description' => 'nullable|string|max:1000',
              'location' => 'nullable|string|max:255',
              'table_type' => 'required|string|in:standard,bar,family,double',
              'min_occupancy' => 'nullable|integer|min:1',
              'max_occupancy' => 'nullable|integer|min:1',
              'features' => 'nullable|array',
              'features.*' => 'in:outdoor,indoor,window_view,private',   
           ]);

           if(isset($fields['reserved_seats']) && $fields['reserved_seats'] > $fields['capacity']){
              throw ValidationException::withMessages([
                'reserved_seats' => 'Reserved seats can not exceed table capacity',
              ],422);
           }    

           if(isset($fields['min_occupancy']) && $fields['min_occupancy'] > $fields['capacity']){
              throw ValidationException::withMessages([
                'min_occupancy' => 'Min occupancy can not exceed table capacity', 
              ],422);
           }

           if(isset($fields['max_occupancy']) && $fields['max_occupancy'] > $fields['capacity']){
              throw ValidationException::withMessages([
                'max_occupancy' => 'Max occupancy cannot exceed table capacity',
              ],422); 
           }
           $fields['user_id'] = $request->user()->id;

           $table = Table::create($fields);

           return response()->json([
              'message' => 'Table created successfully',
              'table' => $table,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create table',
              'error' => $e->getMessage(), 
           ],403); 
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),  
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating table',
              'error' => $e->getMessage(), 
           ],500);         
        }
    }

    public function show(Table $table)
    {
      try{
         Gate::authorize('view', $table);
         
         return response()->json([
            'message' => 'Table retreived successfully',
            'table' => $table,  
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to view table',
            'error' => $e->getMessage(),
         ],403); 
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Table not found',
            'error' => $e->getMessage(),  
         ],404);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving table',
            'error' => $e->getMessage(),  
         ],500);
      }
    }

    public function update(Request $request, Table $table)
    {
      try{
         Gate::authorize('update', $table);
         
         $fields = $request->validate([
              'table_number' => 'sometimes|required|string|max:255|unique:tables,table_number',
              'capacity' => 'sometimes|required|integer|min:0|max:50',
              'reserved_seats' => 'sometimes|nullable|integer|min:0',
              'zone' => 'sometimes|required|string|max:255',
              'status' => 'sometimes|required|string|in:available,occupied,reserved,merged',
              'description' => 'sometimes|nullable|string|max:1000',
              'location' => 'sometimes|nullable|string|max:255',
              'table_type' => 'sometimes|required|string|in:standard,bar,family,double',
              'min_occupancy' => 'sometimes|nullable|integer|min:1',
              'max_occupancy' => 'sometimes|nullable|integer|min:1',
              'features' => 'sometimes|nullable|array',
              'features.*' => 'in:outdoor,indoor,window_view,private',
         ]);

         $capacity = $fields['capacity'] ?? $table->capacity;
         if(isset($fields['reserved_seats']) && $fields['reserved_seats'] > $capacity){
              throw ValidationException::withMessages([
                'reserved_seats' => 'Reserved seats can not exceed table capacity',
              ],422);
           }    

           if(isset($fields['min_occupancy']) && $fields['min_occupancy'] > $capacity){
              throw ValidationException::withMessages([
                'min_occupancy' => 'Min occupancy can not exceed table capacity', 
              ],422);
           }

           if(isset($fields['max_occupancy']) && $fields['max_occupancy'] > $capacity){
              throw ValidationException::withMessages([
                'max_occupancy' => 'Max occupancy cannot exceed table capacity',
              ],422);
           }

           $fields ['user_id'] = $request->user()->id;
           $table->update($fields);

           return response()->json([
              'message' => 'Table updated successfully',
              'table' => $table->fresh(), 
           ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to update table',
            'error' => $e->getMessage(),    
         ],403);
      }catch (ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ],422);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Table not found',
            'error' => $e->getMessage(),    
         ],404);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while updating table',
            'error' => $e->getMessage(),    
         ],500);
      }
    }

    public function destroy(Table $table)
    {
      try{
         Gate::authorize('delete', $table);

         if($table->status === 'occupied'|| $table->status === 'reserved'){
            return response()->json([
               'message' => 'Cannot delete table as it is currently ' . $table->status,
               'error' => 'Table has active customers or reservations',
            ],409);
         }

         $table->delete();

         return response()->json([
            'message' => 'Table deleted succussfully', 
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to delete table',
            'error' => $e->getMessage(),
         ],403);  
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Table not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){

            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete table as it is currently in use',
                   'error' => 'This table has associated reservations, orders, or merges',
                ],409);
            }
         return response()->json([
            'message' => 'An error occured while deleting table',
            'error' => $e->getMessage(),
         ],500);
      }
   }

   public function available()
   {
      try{
        Gate::authorize('viewAny', Table::class);

        $table = Table::where('status', 'available')->get();

        return response()->json([
           'message' => 'Available tables retrieved successfully',
           'tables' => $table,
        ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to view available tables',
            'error' => $e->getMessage(),
         ],403);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving available tables',
            'error' => $e->getMessage(),
         ],500);
      }
   }
}