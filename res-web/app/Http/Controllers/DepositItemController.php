<?php

namespace App\Http\Controllers;

use App\Models\DepositItem;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class DepositItemController extends Controller implements HasMiddleware
{
   protected $model = DepositItem::class;
   
    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum'),
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', DepositItem::class);

           $depositItem = DepositItem::all();

           return response()->json([
              'message' => 'Deposit item retrieved successfully',
              'deposit_item' => $depositItem,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view deposit items',
              'error' => $e->getMessage(),     
           ],403); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving deposit item',
              'error' => $e->getMessage(),
           ],500);  
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', DepositItem::class);

           $fields = $request->validate([
              'deposit_item_name' => 'required|string|max:255',
              'deposit_item_description' => 'nullable|string',
              'deposit_price' => 'required|numeric|min:0',
              'description' => 'nullable|string',
              'is_active' => 'boolean',
           ]);

           $depositItem = DepositItem::create($fields);

           return response()->json([
              'message' => 'Deposit item created successfully',
              'deposit_item' => $depositItem,  
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create deposit item',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating deposit item',
              'error' => $e->getMessage(), 
           ],500);
        }
    }

    public function show(DepositItem $depositItem)
    {
        try{
          Gate::authorize('view', $depositItem);

          return response()->json([
             'message' => 'Deposit item retrieved successfully',
             'deposit_item' => $depositItem,
          ]);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view this deposit item',
              'error' => $e->getMessage(),
            ], 403);
        }catch (ModelNotFoundException $e) {
            return response()->json([
               'message' => 'Deposit item not found',
               'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e) {
            return response()->json([
               'message' => 'An error occurred while retrieving the deposit item',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, DepositItem $depositItem)
    {
        try{
           Gate::authorize('update', $depositItem);

           $fields = $request->validate([
              'deposit_item_name' => 'sometimes|required|string|max:255',
              'deposit_item_description' => 'sometimes|nullable|string',
              'deposit_price' => 'sometimes|required|numeric|min:0',
              'description' => 'sometimes|nullable|string',
              'is_active' => 'sometimes|boolean',
           ]);

           $depositItem->update($fields);

           return response()->json([
              'message' => 'Deposit item updated successfully',
              'deposit_item' => $depositItem->fresh(),    
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
                'message' => 'Unauthorized to update this deposit item',
                'error' => $e->getMessage(),
            ],403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors(),
            ],422);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Deposit item not found',
              'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while updating the deposit item',
              'error' => $e->getMessage(),
            ],500);
        }
    }

    public function destroy(DepositItem $depositItem)
    {
        try{
           Gate::authorize('delete', $depositItem);

           $depositItem->delete();

           return response()->json([
              'message' => 'Deposit item deleted successfully',
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to delete this deposit item',
              'error' => $e->getMessage(),
            ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
             'message' => 'Deposit item not found',
             'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete deposit item as it is currently in use',
                    'error' => 'This deposit item is assigned to one or more transactions',
                ],409);
            }

            return response()->json([
                'message' => 'An error occurred while deleting the deposit item',
                'error' => $e->getMessage(),
            ],500);
        }
     } 
}