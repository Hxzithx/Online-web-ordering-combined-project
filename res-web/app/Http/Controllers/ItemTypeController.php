<?php

namespace App\Http\Controllers;

use App\Models\ItemTypes;
use App\Traits\SyncsData;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ItemTypeController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = ItemTypes::class;
    
    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', ItemTypes::class);

           $itemType = ItemTypes::all();

           return response()->json([
              'message' => 'Item types retrieved successfully',
              'item_types' => $itemType,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view item types',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving item types',
              'error' => $e->getMessage(),  
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', ItemTypes::class);

           $fields = $request->validate([
              'name' => 'required|string|unique:items_type,name|max:255',
              'code' => 'required|string|unique:item_types,code|max:255',
              'affected_stock' => 'sometimes|boolean',
              'is_sellable' => 'sometimes|boolean',
              'requires_recipe' => 'sometimes|boolean',
              'is_active' => 'sometimes|boolean',
           ]);

           $itemType = ItemTypes::create($fields);

           return response()->json([
              'message' => 'Item type created successfully',
              'item_type' => $itemType,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create item types',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating item type',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(ItemTypes $itemType)
    {
        try{
           Gate::authorize('view', $itemType);

           return response()->json([
              'message' => 'Item types retrieved successfully',
              'item_type' => $itemType, 
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view item types',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Item type not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving item types',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function update(Request $request, ItemTypes $itemType)
    {
        try{
           Gate::authorize('update', $itemType);
           
           $fields = $request->validate([
              'name' => 'sometimes|required|string|unique:item_types,name|'. $itemType->id .'|max:255',
              'code' => 'sometimes|required|string|unique:item_types,code'. $itemType->id .'|max:255',
              'affected_stock' => 'sometimes|boolean',
              'is_sellable' => 'sometimes|boolean',
              'requires_recipe' => 'sometimes|boolean',
              'is_active' => 'sometimes|boolean',
           ]);

           $itemType->update($fields);

           return response()->json([
              'message' => 'Item type updated successfully',
              'item_type' => $itemType->fresh(),
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update item types',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Item type not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating item type',
              'error' => $e->getMessage(),    
           ],500); 
        }
    }

    public function destroy(ItemTypes $itemType)
    {
        try{
           Gate::authorize('delete', $itemType);

           $itemType->delete();

           return response()->json([
              'message' => 'Item type deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to delete item type',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
            return response()->json([
               'message' => 'item type not found',
               'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e){
            if(
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
             ){
            return response()->json([
               'message' => 'Can not delete item type as it is currently in use',
               'error' => 'This item type is assigned to one or more records'     
            ]);
             }
            return response()->json([
               'message' => 'An error occured while deleting item type',
               'error' => $e->getMessage(),
            ],500);
        }
    }
}