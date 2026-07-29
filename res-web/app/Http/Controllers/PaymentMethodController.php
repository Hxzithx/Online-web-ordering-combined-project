<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Traits\SyncsData;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = PaymentMethod::class;

    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
          Gate::authorize('viewAny', PaymentMethod::class);

          $paymentMethods = PaymentMethod::all();

          return response()->json([
             'message' => 'Payment methods retrieved succefully',
             'Payment_methods' => $paymentMethods,
          ],201);
        }catch (AuthorizationException $e){
          return response()->json([
             'message' => 'Unauthorize to view payment methods',
             'error' => $e->getMessage(),
          ],403);
        }catch (Exception $e){
          return response()->json([
             'message' => 'An error occured during retrieving payment methods',
             'error' => $e->getMessage(),
          ],500);  
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', PaymentMethod::class);

           $fields = $request->validate([
             'name' => 'required|string|max:255',
             'code' => 'required|string|unique:payment_methods,code|max:255',
             'type' => ['required', 'string', Rule::in(['cash', 'card', 'online', 'wallet'])],
             'is_online' => 'sometimes|boolean',
             'requires_reference' => 'sometimes|boolean',
             'is_active' => 'sometimes|boolean',
             'sort_order' => 'sometimes|integer|min:0',
           ]);

           $paymentMethod = PaymentMethod::creat($fields);

           return response()->json([
              'message' => 'Payment method create successfully',
              'payment_method' => $paymentMethod,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to create payment method',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
            return response()->json([
               'message' => 'Validation failed',
               'error' => $e->errors(),
            ],422);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while creating a payment method',
               'error' => $e->getMessage(),
            ],500);
        }
    }
    
    public function show(PaymentMethod $paymentMethod)
    {
        try{
           Gate::authorize('view', $paymentMethod);

           return response()->json([
              'message' => 'Payment method retrieved successfully',
              'payment_methods' => $paymentMethod,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view payment methods',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
            return response()->json([
               'message' => 'Payment method not found',
               'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while retrieving payment methods',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        try{
           Gate::authorize('update', $paymentMethod);

           $fields = $request->validate([
             'name' => 'sometimes|required|string|max:255',
             'code' => 'sometimes|required|string|unique:payment_methods,code|max:255',
             'type' => ['sometimes', 'required', 'string', Rule::in(['cash', 'card', 'online', 'wallet'])],
             'is_online' => 'sometimes|boolean',
             'requires_reference' => 'sometimes|boolean',
             'is_active' => 'sometimes|boolean',
             'sort_order' => 'sometimes|integer|min:0',
           ]);

           $paymentMethod->update($fields);

           return response()->json([
              'message' => 'Payment method updated successfully',
              'payment_method' => $paymentMethod->fresh(),
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update payment methods',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Payment method not found',
              'error' => $e->getMessage(),
           ],404);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while updating payment method',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        try{
           Gate::authorize('delete', $paymentMethod);

           $paymentMethod->delete();

           return response()->json([
              'message' => 'Payment method deleted succesfully',
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to delete payment mathod',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
            return response()->json([
               'message' => 'Payment method not found',
               'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e){
            if(
                strpos($e->getMessage(), 'foreign key constraint') !== false || 
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ){
                return response()->json([
                  'message' => 'Can not delete payment method as it is currently in use',
                  'error' => 'This payment method is assigned to one or more records',
                ],409);
            }
               return response()->json([
                 'message' => 'An error occured while deleting payment method',
                 'error' => $e->getMessage(),
               ],500);
        }
    }
}