<?php

namespace App\Http\Controllers;

use App\Models\VoidOrder;
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

class VoidOrderController extends Controller implements HasMiddleware
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
           Gate::authorize('viewAny', VoidOrder::class);

           $voidOrders = VoidOrder::with(['item', 'order', 'branch', 'approver'])->latest()->paginate(15);

           return response()->json([
              'message' => 'Void orders retrieved sucessfully',
              'void_order' => $voidOrders,    
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view void orders',
              'error' => $e->getMessage()
            ],403);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while retrieving void orders',
              'error' => $e->getMessage()
            ],500);
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', VoidOrder::class);

           $fields = $request->validate([
              'item_id' => 'required|integer|exists:items,id',
              'order_id' => 'required|integer|exists:orders,id',
              'branch_id' => 'required|integer|exists:branches,id',
              'reason' => 'required|string|max:255',
              'approved_by' => 'nullable|integer|exists:users,id',
           ]);

           DB::beginTransaction();

           $fields['voided_at'] = now();
           $voidOrders = VoidOrder::create($fields);

           DB::commit();

           return response()->json([
              'message' => 'Void order created successfully',
              'void_order' => $voidOrders->load(['item_id', 'order_id', 'branch_id', 'reason', 'approver']) 
           ],201);
        }catch (AuthorizationException $e){
           DB::rollBack();
           return response()->json([
              'message' => 'Unauthorized to void orders',
              'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
           DB::rollBack();
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (Exception $e) {
           DB::rollBack();
           return response()->json([
              'message' => 'An error occurred while voiding the order',
              'error' => $e->getMessage()
            ],500);
        }
    }

    public function show(VoidOrder $voidOrders)
    {
      try{
         Gate::authorize('view', $voidOrders);

         return response()->json([
            'message' => 'Void order retrieved successfully',
            'void_order' => $voidOrders,
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to view void orders',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e) {
         return response()->json([
            'message' => 'Void order not found',
            'error' => $e->getMessage()
         ],404);
      }catch (Exception $e) {
         return response()->json([
            'message' => 'An error occurred while retrieving the void order',
            'error' => $e->getMessage()
         ],500);
        }      
    }

    public function update(Request $request, VoidOrder $voidOrders)
    {
      try{
         Gate::authorize('update', $voidOrders);

         $fields = $request->validate([
            'item_id' => 'sometimes|required|integer|exists:items,id',
            'order_id' => 'sometimes|required|integer|exists:orders,id',
            'branch_id' => 'sometimes|required|integer|exists:branches,id',
            'reason' => 'sometimes|required|string|max:255',
            'approved_by' => 'sometimes|nullable|integer|exists:users,id',
         ]);

         DB::beginTransaction();

         $voidOrders->update();

         DB::commit();

         return response()->json([
            'message' => 'Void order updated successfully',
            'void_order' => $voidOrders->fresh()->load(['item', 'order', 'branch', 'approver'])
         ]);
      }catch (AuthorizationException $e){
          DB::rollBack();
         return response()->json([
            'message' => 'Unauthorized to update this void order',
            'error' => $e->getMessage()
           ],403);
        }catch (ValidationException $e) {
           DB::rollBack();
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
           ],422);
        }catch (ModelNotFoundException $e) {
           DB::rollBack();
           return response()->json([
              'message' => 'Void order not found',
              'error' => $e->getMessage()
           ],404);
        }catch (Exception $e) {
           DB::rollBack();
           return response()->json([
             'message' => 'An error occurred while updating the void order',
             'error' => $e->getMessage()
           ],500);
        }
    }

    public function approve(Request $request, VoidOrder $voidOrders)
    {
      try{ 
         Gate::authorize('approve', $voidOrders);

         DB::beginTransaction();

         $voidOrders->update([
            'approved_by' => $request->user()->id,
            'voided_at' => now()
         ]);

         DB::commit();
         return response()->json([
            'message' => 'Void order approved successfully',
            'void_order' => $voidOrders->fresh()->load(['item', 'order', 'branch', 'approver'])
         ],200);
      }catch (AuthorizationException $e){
         DB::rollBack();
         return response()->json([
            'message' => 'Unauthorize to approve void orders',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e) {
         DB::rollBack();
         return response()->json([
            'message' => 'Void order not found',
            'error' => $e->getMessage()
         ],404);
      }catch (Exception $e) {
         DB::rollBack();
         return response()->json([
            'message' => 'An error occurred while approving the void order',
            'error' => $e->getMessage()
         ],500);
      }
   }

   public function destroy(VoidOrder $voidOrders)
   {
      try{
        Gate::authorize('delete', $voidOrders);

         $voidOrders->delete();

            return response()->json([
               'message' => 'Void order deleted successfully'
            ],200);
        }catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete this void order',
                'error' => $e->getMessage()
            ], 403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Void order not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete void order as it is currently in use',
                   'error' => 'This void order is assigned to one or more records'
                ],409);
            }

            return response()->json([
               'message' => 'An error occurred while deleting the void order',
               'error' => $e->getMessage()
            ],500);
      }
   }
}