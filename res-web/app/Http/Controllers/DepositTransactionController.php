<?php

namespace App\Http\Controllers;

use App\Models\DepositTransaction;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class DepositTransactionController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', DepositTransaction::class);
           
           $depositTransaction = DepositTransaction::all();

           return response()->json([
              'message' => 'Deposit Transaction retrieved successfully',
              'deposit_transaction' => $depositTransaction,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view deposit transactions',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving deposit transactions',
              'error' => $e->getMessage(),
           ]); 
        }
    }

    public function store(Request $request)
    {
        try{
          Gate::authorize('create', DepositTransaction::class);
          
          $fields = $request->validate([
             'customer_id' => 'required|integer|exists:customers,id',
             'deposit_item_id' => 'required|integer|exists:deposit_items,id',
             'item_id' => 'nullable|integer',
             'extra_service_id' => 'nullable|integer',
             'order_id' => 'nullable|integer',
             'quantity' => 'integer|min:1',
             'deposit_amount' => 'required|numeric|min:0',
             'return_date' => 'nullable|date',
             'refund_amount' => 'nullable|numeric|min:0',
             'refund_date' => 'nullable|date',
             'status' => 'string|in:active,returned,refunded,overdue',
             'notes' => 'nullable|string',
             'isOverdue' => 'boolean',
             'refund_by' => 'nullable|string|max:255',
          ]);

          $depositTransaction = DepositTransaction::create($fields);

          return response()->json([
             'message' => 'Deposit Transaction created successfully',
             'deposit_transaction' => $depositTransaction, 
          ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to create deposit transactions',
              'error'   => $e->getMessage(),
           ],403);
        }catch (ValidationException $e) {
            return response()->json([
               'message' => 'Validation failed',
               'errors'  => $e->errors(),
           ],422);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while creating the deposit transaction',
              'error'   => $e->getMessage(),
           ],500);
        }
    }

    public function show(DepositTransaction $depositTransaction)
    {
        try{
           Gate::authorize('view', $depositTransaction);

           return response()->json([
              'message' => 'Deposit transaction retrieved successfully',
              'deposit_transaction' => $depositTransaction,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to retrieve deposit transaction',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Deposit transaction not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving deposit transaction',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function update(Request $request, DepositTransaction $depositTransaction)
    {
        try{
           Gate::authorize('update', $depositTransaction);

           $fields = $request->validate([
             'customer_id' => 'sometimes|required|integer|exists:customers,id',
             'deposit_item_id' => 'sometimes|required|integer|exists:deposit_items,id',
             'item_id' => 'sometimes|nullable|integer',
             'extra_service_id' => 'sometimes|nullable|integer',
             'order_id' => 'sometimes|nullable|integer',
             'quantity' => 'sometimes|integer|min:1',
             'deposit_amount' => 'sometimes|required|numeric|min:0',
             'return_date' => 'sometimes|nullable|date',
             'refund_amount' => 'sometimes|nullable|numeric|min:0',
             'refund_date' => 'sometimes|nullable|date',
             'status' => 'sometimes|string|in:active,returned,refunded,overdue',
             'notes' => 'sometimes|nullable|string',
             'isOverdue' => 'sometimes|boolean',
             'refund_by' => 'sometimes|nullable|string|max:255',
           ]);

           $depositTransaction->update($fields);

           return response()->json([
              'message' => 'Deposit transaction updated successfully',
              'deposit_transaction' => $depositTransaction->fresh(),
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to update this deposit transaction',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors(),
           ],422);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Deposit transaction not found',
              'error' => $e->getMessage(),
           ],404);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while updating the deposit transaction',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function destroy(DepositTransaction $depositTransaction)
    {
        try{
           Gate::authorize('delete', $depositTransaction);

           $depositTransaction->delete();

           return response()->json([
              'message' => 'Deposit transaction deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to delete this deposit transaction',
              'error' => $e->getMessage(),
            ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Deposit transaction not found',
              'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete deposit transaction as it is currently referenced by other records',
                   'error' => 'This transaction is linked to one or more related records',
                ],409);
            }

            return response()->json([
               'message' => 'An error occurred while deleting the deposit transaction',
               'error' => $e->getMessage(),
            ],500);
        }
    }
}