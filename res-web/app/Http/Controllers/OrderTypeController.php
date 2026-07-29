<?php
 

 namespace App\Http\Controllers;

use App\Models\OrderType;
use App\Traits\SyncsData;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

 class OrderTypeController extends Controller implements HasMiddleware
 {
    use SyncsData;

    protected $model = OrderType::class;

    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', OrderType::class);

           $orderType = OrderType::all();

           return response()->json([
              'message' => 'Order types retrieve successfully',
              'Order_types' => $orderType,
           ],201);
        }catch (AuthorizationException $e){
            return response()->json([
               'message' => 'Unauthorize to view order types',
               'error' => $e->getMessage(), 
            ],403);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured during order types retrieving',
               'error' => $e->getMessage(),  
            ],500);
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', OrderType::class);

           $fields = $request->validate([
              'name' => 'required|string|max:255|unique:order_types,name',
              'description' => 'nullable|string',
              'requires_table' => 'boolean',
              'allow_service_charge' => 'boolean',
              'allow_packaging_charge' => 'boolean',
              'allow_split_payment' => 'boolean',
              'allow_discounts' => 'boolean',
              'tax_inclusive' => 'boolean',
              'steward_required' => 'boolean',
              'is_active' => 'boolean',
           ]);

           $orderType = OrderType::create($fields);

           return response()->json([
              'message' => 'Order type created successfully',
              'order_type' => $orderType,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create order type',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
            return response()->json([
               'message' => 'Validation failed',
               'error' => $e->errors(),
            ],422);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while creating order type',
               'error' => $e->getMessage(),  
            ],500);
        }
    }

    public function show(OrderType $orderType)
    {
        try{
          Gate::authorize('view', $orderType);

          return response()->json([
             'message' => 'Order type retrieved successfully',
             'order_type' => $orderType, 
          ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view order type',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
            return response()->json([
               'message' => 'Order type not found',
               'error' => $e->getMessage(),
            ],404);
        }catch (ValidationException $e){
            return response()->json([
               'message' => 'Validation failed',
               'error' => $e->getMessage(), 
            ],422);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while retrieving order type',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, OrderType $orderType)
    {
        try{
           Gate::authorize('update', $orderType);

           $fields = $request->validate([
              'name' => 'sometimes|required|string|max:255|unique:order_types,name',
              'description' => 'sometimes|nullable|string',
              'requires_table' => 'sometimes|boolean',
              'allow_service_charge' => 'sometimes|boolean',
              'allow_packaging_charge' => 'sometimes|boolean',
              'allow_split_payment' => 'sometimes|boolean',
              'allow_discounts' => 'sometimes|boolean',
              'tax_inclusive' => 'sometimes|boolean',
              'steward_required' => 'sometimes|boolean',
              'is_active' => 'sometimes|boolean',
           ]);

           $orderType->update($fields);

           return response()->json([
              'message' => 'Order type updated successfully',
              'order_type' => $orderType->fresh(),
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update order type',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Order type not found',
              'error' => $e->getMessage(),
           ],404);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating order type',
              'error' => $e->getMessage(), 
           ],500);  
        }
    }

    public function destroy(OrderType $orderType)
    {
        try{
           Gate::authorize('delete', $orderType);

           $orderType->delete();

           return response()->json([
               'message' => 'Order type deleted succefully', 
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to delete order type',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
            return response()->json([
               'message' => 'Order type not found',
               'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e){
            if(
                strpos($e->getMessage(), 'foreign key constraint') !== false || 
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ){
                return response()->json([
                  'message' => 'Can not delete order type as it is currently in use',
                  'error' => 'This order type is assigned to one or more records',
                ],409);
            }
               return response()->json([
                 'message' => 'An error occured while deleting order type',
                 'error' => $e->getMessage(),
               ],500);
        }
    }

    public function search(Request $request)
    {
        try{
           $request->validate([
              'query' => 'required|string|min:1',
              'inlude_inactive' => 'sometimes|boolean',
           ]);
           $query = $request->input('query');
           $includeInactive = $request->input('include_inactive',false);

           $orderType = OrderType::where('name', 'like', "%{$query}%")->
           orWhere('description', 'like', "%{$query}%")->when(!$includeInactive, function ($query) {
            return $query->where('is_active', true);
           })->get();

           return response()->json([
              'message' => 'Order type retrieved successfully',
              'order_type' => $orderType,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to search order types',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while searching order items',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function toggleStatus(OrderType $orderType)
    {
      try{
         Gate::authorize('update', $orderType);

         $orderType->update([
             'is_active' => !$orderType->is_active
         ]);

         return response()->json([
            'message' => 'Order type status updated successfully',
            'order_type' => $orderType->fresh(),
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to update status',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Order type not found',
            'error' => $e->getMessage(),  
         ],404);
      }catch (ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ],422);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while updating status',
            'error' => $e->getMessage(),
         ],500);
      }
    }

    public function withFeatures(Request $request)
    {
      try{
         Gate::authorize('viewAny', OrderType::class);

         $request->validate([
           'requires_table' => 'sometimes|boolean',
           'allow_split_payment' => 'sometimes|boolean',
         ]);

         $query = OrderType::where('is_active', true);

         if($request->has('requires_table')){
               $query->where('requires_table', $request->requires_table);
         }
         if($request->has('allow_split_payment')){
               $query->where('allow_split_payment', $request->allow_split_payment);
         }

         $orderType = $query->get();

         return response()->json([
            'message' => 'Order type retrieved successfully',
            'order_type' => $orderType,
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to retrieve order types',
            'error' => $e->getMessage(),
         ],403);
      }catch (ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ],422);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving order types',
            'error' => $e->getMessage(),
         ],500);
      }
    }
 }

 