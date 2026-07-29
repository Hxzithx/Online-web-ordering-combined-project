<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\OrderType;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationData;
use Illuminate\Validation\ValidationException;

use function Symfony\Component\Clock\now;

class OrderItemController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum') 
        ];
    }


    public function index(Order $order)
    {
        try{
           Gate::authorize('viewAny', [OrderItem::class, $order]);

           $orderItems = OrderItem::with([
              'item',
              'orderType',
              'kitchen',
              'variant',
              'portion',
           ])
           ->where('order_id', $order->id)
           ->orderBy('created_at', 'desc')
           ->get();

           return response()->json([
              'message' => 'Order Items retrieved successfully',
              'order_items' => $orderItems,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view order items',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving order items',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    //add item to existing order
    public function store(Request $request, Order $order)
    {
        try{
           Gate::authorize('create', $order);

        if(in_array($order->status, ['completed', 'cancelled'])){
            return response()->json([
               'message' => 'Can not add items to completed or cancelled order',
            ],422);
        }

        $fields = $request->validate([
           'items' => 'required|array|min:1',
           'items.*.item_id' => 'sometimes|nullable|exists:items,id',
           'items.*.extra_service_id' => 'sometimes|nullable|exists:extra_services,id',
           'items.*.package_id' => 'sometimes|nullable|exists:packages,id',
           'items.*.order_type_id' => 'sometimes|nullable|exists:order_types,id',
           'items.*.quantity' => 'required|integer|min:1',
           'items.*.unit_price' => 'required|numeric|min:0',
           'items.*.variant_id' => 'nullable|exists:variants,id',
           'items.*.portion_id' => 'nullable|exists:portions,id',
           'items.*.discount_amount' => 'nullable|numeric|min:0',
           'items.*.discount_percentage' => 'nullable|numeric|min:0',
           'items.*.service_charge_amount' => 'nullable|numeric|min:0',
           'items.*.service_charge_percentage' => 'nullable|numeric|min:0',
           'items.*.tax_amount' => 'nullable|numeric|min:0',
           'items.*.tax_percentage' => 'nullable|numeric|min:0',
           'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        $addedItems = [];
        $additionalAmount = 0;

        foreach($fields['items'] as $itemData){
            $kitchenId = null;
            if(isset($itemData['item_id'])){
                $item = Item::findOrFail($itemData['item_id']);
                $kitchenId = $item->kitchen_id;
            }
        }

        $orderTypeId = $itemData['order_type_id'] ?? $order->order_type_id;
        $orderType = OrderType::findOrFail($orderTypeId);

        $subTotal = $itemData['unit_price'] * $itemData['quantity'];
        $additionalAmount += $subTotal;

        //create order item
        $orderItemData = [
           'order_number' => $order->order_number,
           'order_type_id' => $orderTypeId,
           'item_id' => $itemData['item_id'],
           'quantity' => $itemData['quantity'],
           'unit_price' => $itemData['unit_price'],
           'sub_total' => $subTotal,
           'discount_amount' => $itemData['discount_amount'] ?? 0,
           'discount_percentage' => $itemData['discount_percentage'] ?? 0,
           'service_charge_amount' => $itemData['service_charge_amount'] ?? 0,
           'service_charge_percentage' => $itemData['service_charge_percentage'] ?? 0,
           'tax_amount' => $itemData['tax_amount'] ?? 0,
           'tax_percentage' => $itemData['tax_percentage'] ?? 0,
           'notes' => $itemData['notes'] ?? null,
           'kitchen_id' => $kitchenId,
           'extra_service_id' => $itemData['extra_service_id'] ?? null,
           'package_id' => $itemData['package_id'] ?? null,
           'variant_id' => $itemData['variant_id'] ?? null,
           'portion_id' => $itemData['portion_id'] ?? null,
           'status' => 'pending',
           'added_at' => now(),
        ];

        $orderItem = OrderItem::create($orderItemData);
        $addedItems[] = $orderItem;

        //update order total
        $newTotal = $order->total_amount + $additionalAmount;
        $order->update(['total_amount' => $newTotal]);

        DB::commit();

        //load relationships for response
        foreach($addedItems as &$item){
            $item->load(['item', 'orderType', 'kitchen', 'variant', 'portion']);
        }

        return response()->json([
           'message' => 'Item added order to successfully',
           'added_item' => $addedItems,
           'order' => $order->fresh([
              'orderItems.item',
              'orderItems.orderType',
              'orderItems.kitchen',
              'table',
              'user',
           ])
        ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to add item to order',
              'error' => $e->getMessage(),
           ],403); 
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while adding items to order',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(Order $order, OrderItem $orderItem)
    {
        try{
           Gate::authorize('view', $order);

           if($orderItem->order_id !== $order->id){
              return response()->json([
                 'message' => 'Order item does not belong to this order',
              ],404);
           }

           $orderItem->load([
              'item',
              'orderType',
              'kitchen',
              'variant',
              'portion',
              'voidedBy',
           ]);

           return response()->json([
              'message' => 'Order item retrieved successfully',
              'order_item' => $orderItem,
           ],200);
        }catch(AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view this order items',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Order item nott found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retreiving order items',
              'error' => $e->getMessage(),
           ],500); 
        }
    }
}