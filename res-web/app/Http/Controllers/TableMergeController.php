<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\TableMerge;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

use function Symfony\Component\Clock\now;

class TableMergeController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
        new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
      try{
         Gate::authorize('viewAny', TableMerge::class);

         $tableMerge = TableMerge::with(['table1', 'table2', 'table3', 'user'])
                ->orderBy('merged_at', 'desc')
                ->get();
         return response()->json([
            'message' => 'Table merges retreved successfully',
            'table_merges' => $tableMerge,
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to view table merges',
            'error' => $e->getMessage(),
         ],403);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving table merges',
            'error' => $e->getMessage(), 
         ],500);
      }
    }

    public function store(Request $request)
    {
      try{
         Gate::authorize('create', TableMerge::class);

         $fields = $request->validate([
            'table_id_1' => 'required|exists:tables,id',
            'table_id_2' => 'required|exists:tables,id|different:table_id_1',
            'table_id_3' => 'nullable|exists:tables,id|different:tab;e_id_1,table_id_3',
            'merged_at' => 'nullable|date',
         ]); 

         $tableIds = array_filter([
             $fields['table_id_1'],
             $fields['table_id_2'],
             $fields['table_id_3'] ?? null
         ]);

         $unavailableTables = Table::whereIn('id', $tableIds)->where('status', '!=', 'available')->pluck('code');

         if($unavailableTables->isNotEmpty()){
            return response()->json([
               'message' => 'Can not merge tables that are not available',
               'unavailable_tables' => $unavailableTables,
            ],422);
         }

         DB::beginTransaction();

         $fields['user_id'] = $request->user()->id;
         $fields['merged_at'] = $fields['merged_at'] ?? now();

         $tableMerge = TableMerge::create($fields);

         Table::whereIn('id', $tableIds)->update(['status' => 'reserved']);

         DB::commit();

         $tableMerge->load(['table1', 'table2', 'table3', 'user']);

         return response()->json([
            'message' => 'Table merged successfully',
            'table_merge' => $tableMerge,
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to create table merge',
            'error' => $e->getMessage(),  
         ],403);
      }catch (ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(), 
         ],422);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while merging tables',
            'error' => $e->getMessage(),
         ]); 
      }
    }

    public function show(TableMerge $tableMerge)
    {
      try{
          Gate::authorize('view', $tableMerge);

          $tableMerge->load(['table1', 'table2', 'table3', 'user', 'orders']);

          return response()->json([
             'message' => 'Table merge retrieved successfully',
             'table_merge' => $tableMerge,
          ],200);
      }catch (AuthorizationException $e){
          return response()->json([
             'message' => 'Unauthorize to view table merge',
             'error' => $e->getMessage(),
          ],403); 
      }catch (ModelNotFoundException $e){
          return response()->json([
             'message' => 'Table merge not found',
             'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){
          return response()->json([
             'message' => 'An error occured while retrieving table merges',
             'error' => $e->getMessage(),
          ],500);
      }
    }

    public function update(Request $request, TableMerge $tableMerge)
    {
      try{
         Gate::authorize('update', $tableMerge);

         $fields = $request->validate([
            'table_id_1' => 'sometimes|exists:tables,id',
            'table_id_2' => 'sometimes|required|exists:tables,id|different:table_id_1',
            'table_id_3' => 'sometimes|nullable|exists:tables,id|different:tab;e_id_1,table_id_3',
            'merged_at' => 'sometimes|nullable|date',
         ]);

         $oldTableIds = array_filter([
             $tableMerge->table1(),
             $tableMerge->table2(),
             $tableMerge->table3(),
         ]);

         //get new tables ids

         $newTablesIds = array_filter([
            $fields['table_id_1'] ?? $tableMerge->table_id_1,
            $fields['table_id_2'] ?? $tableMerge->table_id_2,
            $fields['table_id_3'] ?? $tableMerge->table_id_3,
         ]);

         //check new tables are available
         $tableToCheck = array_diff($newTablesIds, $oldTableIds);

         if(!empty($tableToCheck)){
            $unavailableTables =Table::whereIn('id', $tableToCheck)->where('status', '!=', 'available')->pluck('code');
         }

         if($unavailableTables->isNotEmpty()){
            DB::rollBack();

            return response()->json([
               'message' => 'Can not merge tables that are not available',
               'unavailable_tables' => $unavailableTables,
            ],422);
         }

         $fields['user_id'] = $request->user()->id;
         $tableMerge->update($fields);

         //release old table
         $tableToRelease = array_diff($oldTableIds, $newTablesIds);
         if(!empty($tableToRelease)){
            Table::whereIn('id', $tableToRelease)->update(['status' => 'available']);
         }
         if(!empty($tableToCheck)){
            Table::whereIn('id', $tableToCheck)->update(['status' => 'reserved']);
         }
         DB::commit();

         $tableMerge->load(['table1', 'table2', 'table3', 'user']);

         return response()->json([
            'message' => 'Table merge update successfully',
            'table_merge' => $tableMerge->fresh(['table1', 'table2', 'table3', 'user']),
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to update table merge',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Table merge not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ],422);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured updating table merge',
            'error' => $e->getMessage()
         ],500);
      }
    }

    public function destroy(TableMerge $tableMerge)
    {
      try{
         Gate::authorize('delete', $tableMerge);

         DB::beginTransaction();

         $tableIds = array_filter([
             $tableMerge -> table_id_1,
             $tableMerge -> table_id_2,
             $tableMerge -> table_id_3
         ]);

         $tableMerge->delete();

         Table::whereIn('id',$tableIds)->update(['status' => 'available']);

         DB::commit();

         return response()->json([
            'message' => 'Table merge deleted successfully',    
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to delete table merge',
            'error' => $e->getMessage(),  
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Table merge not found',
            'error' => $e->getMessage(),  
         ],404);
      }catch (Exception $e){
         DB::rollBack();

         if(
                strpos($e->getMessage(), 'foreign key constraint') !== false || 
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ){
                return response()->json([
                  'message' => 'Cannot delete table merge as it has associated orders',
                  'error' => 'This table merge is currently in use',
                ],409);
            }
               return response()->json([
                 'message' => 'An error occured while deleting table merge',
                 'error' => $e->getMessage(),
               ],500);
       }
    }

    public function active()
    {
      try{
         Gate::authorize('viewAny', TableMerge::class);

         $activeMerges = TableMerge::with(['table1', 'table2', 'table3', 'user'])->
               whereHas('table1', function ($query){
                  $query->where('status', 'reserved');
               })->
               orderBy('merge_at', 'desc')->
               get();
         
         return response()->json([
            'message' => 'Active table merges retreived successfully',
            'table_merges' => $activeMerges,
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorized to view active table merges',
            'error' => $e->getMessage()
         ],403);
      }catch (Exception $e) {
         return response()->json([
            'message' => 'An error occurred while retrieving active table merges',
            'error' => $e->getMessage()
         ],500);
      }
   }
}