<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
class ItemController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = Item::class;
    
    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Item::class);

           $items = Item::with(['category', 'variant', 'portion', 'kitchen'])->get();

           return response()->json([
              'message' => 'Items retrieved successfully',
              'items' => $items,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view items',
              'error' => $e->getMessage(), 
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving items',
              'error' => $e->getMessage(),  
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', Item::class);

           $fields = $request->validate([
              'category_id' => 'required|integer|exists:categories,id',
              'portions_id' => 'nullable|integer|exists:portions,id',
              'variant_id' => 'nullable|integer|exists:variants,id',
              'item_type' => 'required|string|max:255',
              'base_name' => 'required|string|max:255',
              'full_name' => 'nullable|string|max:255',
              'description' => 'nullable|string|max:255',
              'status' => 'required|string|max:255',
              'kitchen_id' => 'required|integer|exists:kitchens,id',
              'image_url' => 'nullable|string|max:2048',
              'include_tax' => 'sometimes|boolean',
              'include_service_charge' => 'sometimes|boolean',
           ]);

           $fields['user_id'] = $request->user()->id;
           $item = Item::create($fields);
           $item->load(['category', 'variant', 'portion', 'kitchen']);

           return response()->json([
              'message' => 'Item create successfully',
              'item' => $item,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create item',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' =>'An error occured while creating item',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(Item $item)
    {
        try{
           Gate::authorize('view', $item);

           $item->load(['category', 'variant', 'portion', 'kitchen']);
           
           return response()->json([
              'message' => 'Item retrieved successfully',
              'item' => $item,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view item',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Item not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving item',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function update(Request $request, Item $item)
    {
        try{
           Gate::authorize('update', $item);

           $fields = $request->validate([
              'category_id' => 'sometimes|required|integer|exists:categories,id',
              'portions_id' => 'sometimes|nullable|integer|exists:portions,id',
              'variant_id' => 'sometimes|nullable|integer|exists:variants,id',
              'item_type' => 'sometimes|required|string|max:255',
              'base_name' => 'sometimes|required|string|max:255',
              'full_name' => 'sometimes|nullable|string|max:255',
              'description' => 'sometimes|nullable|string|max:255',
              'status' => 'sometimes|required|string|max:255',
              'kitchen_id' => 'sometimes|required|integer|exists:kitchens,id',
              'image_url' => 'sometimes|nullable|string|max:2048',
              'include_tax' => 'sometimes|boolean',
              'include_service_charge' => 'sometimes|boolean',
           ]);

           if(!empty($fields)){
                $fields['user_id'] = $request->user()->id;
           }
           $item->update($fields);

           return response()->json([
              'message' => 'Item updated successfully',
              'item' => $item->fresh(['category', 'portion', 'variant', 'kitchen', 'user'])  
           ]);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update item',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422);  
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Item not found',
              'error' => $e->getMessage(),
           ],404);  
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating item',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(Item $item)
    {
        try{
           Gate::authorize('delete', $item);

           $item->delete();

           return response()->json([
              'message' => 'Item deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to delete this item',
              'error' => $e->getMessage()
            ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Item not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete item as it is currently in use',
                   'error' => 'This item is assigned to one or more orders'
                ],409);
            }

            return response()->json([
               'message' => 'An error occurred while deleting the item',
               'error' => $e->getMessage()
            ],500);
        }
    }
}