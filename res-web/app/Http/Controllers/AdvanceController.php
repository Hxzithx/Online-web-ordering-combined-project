<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Advance;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AdvanceController extends Controller implements HasMiddleware
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
           Gate::authorize('viewAny', Advance::class);

           $advance = Advance::with([
               'branch',
               'creator',
               'order',
               'reservation',
               'customer',
               'paymentMethod',
               'orderType',
               'cancelledBy',
           ])
           ->latest()
           ->paginate(15);

           return response()->json([
              'message' => 'Advances retreived successfully',
              'advances' => $advance,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view advances',
              'error' => $e->getMessage()
           ],403);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while retrieving advances',
              'error' => $e->getMessage()
           ],500);
        }
    }

    public function store(Request $request)
    {
       try{
          Gate::authorize('create', Advance::class);

          $fields = $request->validate([
             'advance_number' => 'nullable|string|max:100|unique:advances,advance_number',
             'order_id' => 'nullable|integer|exists:orders,id',
             'reservation_id' => 'nullable|integer',
             'customer_id' => 'nullable|integer|exists:customers,id',
             'payment_method_id' => 'nullable|integer|exists:payment_methods,id',
             'branch_id' => 'nullable|integer|exists:branches,id',
             'order_type_id' => 'nullable|integer|exists:order_types,id',
             'type' => 'nullable|in:customer,staff',
             'reference_id' => 'nullable|integer',
             'direction' => 'nullable|in:in,out',
             'amount' => 'nullable|numeric|min:0',
             'balance_amount' => 'nullable|numeric|min:0',
             'purpose' => 'nullable|string|max:500',
             'advance_date' => 'nullable|date',
             'event_date' => 'nullable|date',
             'user_id' => 'nullable|integer|exists:users,id',
             'status' => 'nullable|in:pending,completed,cancelled',
             'notes' => 'nullable|string|max:1000',
          ]);

          DB::beginTransaction();

          $fields['created_by'] = $request->user()->id;
          $fields['is_cancelled'] = false;

          $advance = Advance::create($fields);

          DB::commit();

          return response()->json([
             'message' => 'Advance created successfully',
             'advance' => $advance->load([
               'branch',
               'creator',
               'order',
               'reservation',
               'customer',
               'paymentMethod',
               'orderType', 
             ])
          ],201);
       }catch (AuthorizationException $e){
          DB::rollBack();
          return response()->json([
             'message' => 'Unauthorized to create advances',
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
            'message' => 'An error occurred while creating the advance',
            'error' => $e->getMessage()
          ],500);
       }
    }

    public function show(Advance $advance)
    {
        try{
           Gate::authorize('view', $advance);

           return response()->json([
              'message' => 'Advance retrieved successfully',
              'advance' => $advance->load([
               'branch',
               'creator',
               'order',
               'reservation',
               'customer',
               'paymentMethod',
               'orderType',
               'cancelledBy',
              ])
           ]);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view advance',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Advance not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving advance',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function update(Request $request, Advance $advance)
    {
        try{
           Gate::authorize('update', $advance);

           $fields = $request->validate([
             'advance_number' => 'nullable|string|max:100|unique:advances,advance_number',
             'order_id' => 'nullable|integer|exists:orders,id',
             'reservation_id' => 'nullable|integer',
             'customer_id' => 'nullable|integer|exists:customers,id',
             'payment_method_id' => 'nullable|integer|exists:payment_methods,id',
             'branch_id' => 'nullable|integer|exists:branches,id',
             'order_type_id' => 'nullable|integer|exists:order_types,id',
             'type' => 'nullable|in:customer,staff',
             'reference_id' => 'nullable|integer',
             'direction' => 'nullable|in:in,out',
             'amount' => 'nullable|numeric|min:0',
             'balance_amount' => 'nullable|numeric|min:0',
             'purpose' => 'nullable|string|max:500',
             'advance_date' => 'nullable|date',
             'event_date' => 'nullable|date',
             'user_id' => 'nullable|integer|exists:users,id',
             'status' => 'nullable|in:pending,completed,cancelled',
             'notes' => 'nullable|string|max:1000',
           ]);

           DB::beginTransaction();

           $advance->update($fields);

           DB::commit();

           return response()->json([
              'message' => 'Advance updated successfully',
              'advance' => $advance->from()->load([
                 'branch',
                 'creator',
                 'order',
                 'reservation',
                 'customer',
                 'paymentMethod',
                 'orderType',
                 'cancelledBy',                
              ])
           ],200);
        }catch (AuthorizationException $e){
            DB::rollBack();
           return response()->json([
              'message' => 'Unauthorize to update advance',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
            DB::rollBack();
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (ModelNotFoundException $e){
            DB::rollBack();
           return response()->json([
              'message' => 'Advance not found',
              'error' => $e->getMessage(),
           ],404);  
        }catch (Exception $e){
            DB::rollBack();
           return response()->json([
              'message' => 'An error occured while updating advance',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function cancel (Request $request, Advance $advance)
    {
        try{
           Gate::authorize('cancel', $advance);

           $fields = $request->validate([
              'cancellation_reason' => 'nullable|string|max:500',
           ]);

           if($advance->is_cancelled == true){
            return response()->json([
               'message' => 'Advance is already cancelled',
            ],409);
           }

           DB::beginTransaction();

           $advance->update([
              'is_cancelled' => true,
              'cancelled_by' => $request->user()->id,
              'cancellation_reason' => $fields['cancellation_reason'] ?? null,
              'cancelled_at' => now(),
              'status' => 'cancelled',
           ]);

           DB::commit();

           return response()->json([
              'message' => 'Advance cancelled successfully',
              'advance' => $advance->load([
                 'branch',
                 'creator',
                 'order',
                 'reservation',
                 'customer',
                 'paymentMethod',
                 'orderType',
                 'cancelledBy',
              ])
           ]);
        }catch (AuthorizationException $e){
           DB::rollBack();
           return response()->json([
              'message' => 'Unauthorize to cancel advance',
              'error' => $e->getMessage(), 
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while cancelling advance',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(Advance $advance)
    {
        try{
            Gate::authorize('delete', $advance);

            $advance->delete();

            return response()->json([
               'message' => 'Advance deleted successfully'
            ],200);
        }catch (AuthorizationException $e) {
            return response()->json([
               'message' => 'Unauthorized to delete this advance',
               'error' => $e->getMessage()
            ],403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
               'message' => 'Advance not found',
               'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete advance as it is currently in use',
                   'error' => 'This advance is assigned to one or more records'
                ],409);
            }

            return response()->json([
              'message' => 'An error occurred while deleting the advance',
              'error' => $e->getMessage()
            ],500);
        }
    }
}

