<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddOn;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class OrderItemAdOnController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum')
        ];
    }

    public function index(Request $request, $order_item_id)
    {
        try{
            $orderItem = OrderItem::findOrFail($order_item_id);
            Gate::authorize('viewAny', $orderItem);

            // 3. Get the data
            $orderItemAddOns = OrderItemAddOn::where('order_item_id', $order_item_id)->get();

            return response()->json([
                'message' => 'Order item add-ons retrieved successfully',
                'order_item_add_ons' => $orderItemAddOns,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occured while retrieving order item add-ons',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try{
           $fields = $request->validate([
              'order_id' => 'required|exists:orders,id',
              'order_item_id' => 'required|exists:order_items,id',
              'add_on_id' => 'required|exists:add_ons,id',
              'quantity' => 'required|integer|min:1',
              'unit_price' => 'required|numeric|min:0',
              'total_price' => 'required|numeric|min:0',
              'packaging_charge' => 'nullable|numeric|min:0',
              'discount_amount' => 'nullable|numeric|min:0',
              'tax_amount' => 'nullable|numeric|min:0',
              'service_charge_amount' => 'nullable|numeric|min:0',
              'total_amount' => 'required|numeric|min:0',
           ]);

           $order = Order::findOrFail($fields['order_id']);
           Gate::authorize('update', $order);

           $orderItemAddOn = OrderItemAddOn::create($fields);

           return response()->json([
              'message' => 'Order item add-ons created successfully',
              'order_item_add_on' => $orderItemAddOn,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create order item add-on',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Order not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating order item-add-on',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(OrderItemAddOn $orderItemAddOn)
    {
        try{
            return response()->json([
               'message' => 'Order item add-ons retrieved successfully',
               'order_item_add_on' => $orderItemAddOn,  
            ],200);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving order item add-on',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function update(Request $request, OrderItemAddOn $orderItemAddOn)
    {
        try{
           $order = Order::findOrFail($orderItemAddOn->order_id);
           Gate::authorize('update', $order);

           $fields = $request->validate([
              'quantity' => 'sometimes|required|integer|min:1',
              'unit_price' => 'sometimes|required|numeric|min:0',
              'total_price' => 'sometimes|required|numeric|min:0',
              'packaging_charge' => 'sometimes|nullable|numeric|min:0',
              'discount_amount' => 'sometimes|nullable|numeric|min:0',
              'tax_amount' => 'sometimes|nullable|numeric|min:0',
              'service_charge_amount' => 'sometimes|nullable|numeric|min:0',
              'total_amount' => 'sometimes|required|numeric|min:0',
           ]);

           $orderItemAddOn->update($fields);

           return response()->json([
              'message' => 'Order item add-on updated successfully',
              'order_item_add_on' => $orderItemAddOn,
           ]);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update order item add-on',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating order item add-on',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(OrderItemAddOn $orderItemAddOn)
    {
        try{
           $order = Order::findOrFail($orderItemAddOn->order_id);
           Gate::authorize('update', $order);

           $orderItemAddOn->delete();

           return response()->json([
              'message' => 'Order item add-on deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to delete from this order',
              'error' => $e->getMessage()
            ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Order item add-on not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while deleting order item add-on',
              'error' => $e->getMessage()
            ],500);
        }
    }
}