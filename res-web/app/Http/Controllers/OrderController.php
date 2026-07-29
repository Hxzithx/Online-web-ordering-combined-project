<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderType;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

use function Symfony\Component\Clock\now;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return[
            new Middleware('auth:sanctum')
        ];
    }

    public function index(Request $request)
    {
        try{
           Gate::authorize('viewAny', Order::class);

           $query = Order::with([
             'table',
             'tableMerge',
             'user',
             'orderType',
             'openedBy',
             'closedBy',
             'cancelledBy',
             'orderItems.item'
           ]);

           //filter by order number
           if($request->has('order_number')){
              $query->where('order_number', 'like', '%'. $request->order_number . '%');
           }

           //filter by status
           if($request->has('status')){
             $query->where('status', $request->status); 
           }

           //filter by payment status
           if($request->has('payment_status')){
             $query->where('payment_status', $request->payment_status);
           }

           //filter by order type
           if($request->has('order_type_id')){
             $query->where('order_type_id', $request->order_type_id);
           }

           //filter by table
           if($request->has('table_id')){
              $query->where('table_id', $request->table_id);
           }

           //filter by customer name
           if($request->has('customer_name')){
              $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
           }

           //filter by date range
            if($request->has('from_date')){
               $query->whereDate('created_at', '>=', $request->from_date); 
            }
            if($request->has('to_date')){
               $query->whereDate('created_at',' <=', $request->to_date); 
            }

            //order by latest by default
            $query->orderBy('created_at', 'desc');

            //check if pagination is request
            if($request->has('paginate') && $request->paginate === 'false'){
                $orders = $query->get();
            }else {
                $perPage = $request->has('per_page') ? $request->per_page : 20;
                $orders = $query->paginate($perPage);
            }

            return response()->json([
               'message' => 'Orders retrieved successfully',
               'orders' => $orders,
            ]);
        }catch (AuthorizationException $e){
            return response()->json([
               'message' => 'Unauthorize to view orders',
               'error' => $e->getMessage(),
            ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving orders',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', Order::class);

           $fields = $request->validate([
              'branch_id' => 'sometimes|nullable|integer',
              'order_number' => 'required|string|unique:orders,order_number',
              'table_id' => 'nullable|exists:tables,id',
              'table_merge_id' => 'nullable|exists:table_merges,id',
              'steward_id' => 'nullable|exists:users,id',
              'order_type_id' => 'required|exists:order_types,id',
              'customer_name' => 'nullable|string|max:255',
              'customer_id' => 'nullable|string|max:255',
              'status' => 'nullable|string|in:pending,confirmed,preparing,ready,served,completed,cancelled',
              'reserved_seats' => 'nullable|integer|min:1',
              'discount_amount' => 'nullable|numeric|min:0',
              'tax_amount' => 'nullable|numeric|min:0',
              'service_charge' => 'nullable|numeric|min:0',
              'packaging_charge' => 'nullable|numeric|min:0',
              'payment_status' => 'nullable|string|in:pending,unpaid,partial,paid,refunded',
              'payment_method' => 'nullable|string|in:cash,card,mobile,bank_transfer',
              'notes' => 'nullable|string',
              'items' => 'required|array|min:1',
              'items.*.item_id' => 'required|exists:items,id',
              'items.*.quantity' => 'required|integer:min:1',
              'items.*.price' => 'required|numeric|min:0',
              'items.*.variant_id' => 'nullable|exists:variants,id',
              'items.*.portion_id' => 'nullable|exists:portions,id',
              'items.*.notes' => 'nullable|string',
           ]);
             //get order type to check if it requires table
           $orderType = OrderType::findOrFail($fields['order_type_id']);

           if($orderType->requires_table && !isset($fields['table_id'])){
             return response()->json([
                'message' => 'This order type requires table',
                'error' => 'Table required',
             ],422);
           }

           if(empty($customer->phone) || empty($customer->address)) {
                return response()->json([
                   'message' => 'Complete profile first'
               ],422);
             }

           //validate table availability if provided
           if(isset($fields['table_id'])){
              $table = Table::find($fields['table_id']);
               if($table->status === 'maintenance'){
                 return response()->json([
                    'message' => 'Can not create order for table under maintenance', 
                 ],422);
               }
           }

           DB::beginTransaction();

           //create order 
           $orderData = [
              'branch_id' => $fields['branch_id'] ?? null,
              'order_number' => $fields['order_number'],
              'table_id' => $fields['table_id'] ?? null,
              'table_merge_id' => $fields['table_merge_id'] ?? null,
              'user_id' => $request->user()->id,
              'opened_by' => $request->user()->id,
              'steward_id' => $fields['steward_id'] ?? null,
              'order_type_id' => $fields['order_type_id'],
              'customer_name' => $fields['customer_name'] ?? null,
              'customer_id' => $fields['customer_id'] ?? null,
              'status' => $fields['status'] ?? 'pending',
              'reserved_seat' => $fields['reserved_seat'] ?? null,
              'discount_amount' => $fields['discount_amount'] ?? null,
              'tax_amount' => $fields['tax_amount'] ?? null,
              'service_charge' => $fields['service_charge'] ?? null,
              'packaging_charge' => $fields['packaging_charge'] ?? null,
              'payment_status' => $fields['payment_status'] ?? 'pending',
              'payment_method' => $fields['payment_method'] ?? null,
              'notes' => $fields['notes'] ?? null,
              'opened_at' => now(),
              'total_amount' => 0,
           ];

           $order = Order::create($orderData);

           //add order items
           $itemTotal = 0;
           foreach($fields['items'] as $itemData){
             $itemTotal = $itemData['price'] * $itemData['quantity'];
             $itemTotal += $itemTotal;

             $orderItem = $order->orderItems()->create([
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'],
                'variant_id' => $itemData['variant_id'] ?? null,
                'portion_id' => $itemData['portion_id'] ?? null,
                'notes' => $itemData['notes'] ?? null,
                'status' => 'pending',
             ]);
           }

           //calculate total
           $subTotal = $itemTotal;
           $discount = $fields['discount_amount'] ?? 0;
           $tax = $fields['tax_amount'] ?? 0;
           $serviceCharge = $fields['service_charge'] ?? 0;
           $packagingCharge = $fields['packaging_charge'] ?? 0;

           $finalTotal = $subTotal - $discount + $tax + $serviceCharge + $packagingCharge;

           $order->update(['total_amount' => max(0, $finalTotal)]);

           if(isset($fields['table_id'])){
              Table::where('id', $fields['table_id'])->update(['status' => 'occupied']);
           }

           DB::commit();

           $order->load([
             'table',
             'tableMerge',
             'user',
             'steward',
             'orderType',
             'orderItems.item',
             'orderItems.variant',
             'orderItems.portion',
           ]);

           return response()->json([
              'message' => 'Order created successfully',
              'order' => $order,
           ],201);
        }catch(AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create order',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating order',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(Order $order)
    {
      try{
         Gate::authorize('view', $order);

         $order->load([
             'table',
             'tableMerge',
             'user',
             'steward',
             'orderType',
             'openedBy',
             'closedBy',
             'cancelledBy',
             'orderItems.item',
             'orderItems.variant',
             'orderItems.portion',
         ]);

         return response()->json([
            'message' => 'Order retrieved successfully',
            'order' => $order,
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to view orders',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Order not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving orders',
            'error' => $e->getMessage(), 
         ],500);
      }
   }

   //get order by number
   public function showByOrderNo($orderNo)
   {
      try{
         $order = Order::where('order_number', $orderNo)->firstOrFail();

         Gate::authorize('viewAny', $order);

         $order->load([
             'table',
             'tableMerge',
             'user',
             'steward',
             'orderType',
             'openedBy',
             'closedBy',
             'cancelledBy',
             'orderItems.item',
             'orderItems.variant',
             'orderItems.portion',
         ]);

         return response()->json([
            'message' => 'Order retrieved successfully',
            'order' => $order,
         ],200);
      }catch(AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to view orders',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Order not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving orders',
            'error' => $e->getMessage(),
         ],500);
      }
   }

   public function update(Request $request, Order $order)
   {
      try{
         Gate::authorize('update', $order);
         
         $fields = $request->validate([
             'branch_id' => 'sometimes|nullable|integer',
             'order_number' => 'sometimes|string|max:50|unique:orders,order_number',
             'steward_id' => 'sometimes|nullable|exists:users,id',
             'customer_name' => 'sometimes|nullable|string|max:255',
             'customer_id' => 'sometimes|nullable|string|max:50',
             'status' => 'sometimes|string|in:pending,confirmed,preparing,ready,served,completed,cancelled',
             'reserved_seats' => 'sometimes|nullable|integer|min:1',
             'discount_amount' => 'sometimes|nullable|numeric|min:0',
             'tax_amount' => 'sometimes|nullable|numeric|min:0',
             'service_charge' => 'sometimes|nullable|numeric|min:0',
             'packaging_charge' => 'sometimes|nullable|numeric|min:0',
             'payment_status' => 'sometimes|nullable|string|in:pending,unpaid,partial,paid,refunded',
             'payment_method' => 'sometimes|nullable|string|in:cash,card,mobile,bank_transfer',
             'notes' => 'sometimes|nullable|string',
         ]);

         DB::beginTransaction();

         //recalculate total if  any amount field changed
         $needCalculation = isset($fields['discount_amoutn']) ||
         isset($fields['tax_amount']) || 
         isset($fields['service_charge']) ||
         isset($fields['packaging_charge']);

         if($needCalculation){
            $itemTotal = $order->orderItems->sum(function ($item) {
               return $item->price * $item->quantity;
            });

            $discount = $fields['discount_amount'] ?? $order->discount_amount;
            $tax = $fields['tax_amount'] ?? $order->tax_amount;
            $serviceCharge = $fields['service_charge'] ?? $order->service_charge;
            $packagingCharge = $fields['packaging_charge'] ?? $order->packaging_charge;

            $subTotal = $itemTotal - $discount;
            $finalTotal = $subTotal + $tax + $serviceCharge + $packagingCharge;

            $fields['total_amount'] = max(0, $finalTotal);
         }

         //update timestamp based on status changed
         if(isset($fields['status'])){
            $timestampField = null;
            switch($fields['status']){
               case 'confirmed' :
                  $timestampField = 'confirmed_at';
                  break;
               case 'preparing' :
                  $timestampField = 'prepared_at';
                  break;
               case 'served' :
                  $timestampField = 'served_at';
                  break;
               case 'completed' :
                  $timestampField = 'closed_at';
                  $fields['closed_by'] = $request->user()->id;
                  break;
               case 'cancelled' :
                  $timestampField = 'cancelled_at';
                  $fields['cancelled_by'] = $request->user()->id;
                  $fields['cancel_reason'] = $fields['cancel_reason'] ?? 'Updated via API';
            }
            if($timestampField && !$order->$timestampField){
               $fields[$timestampField] = now();
            }
         }

         $order->update($fields);

         //if order is complete or cancelled update the table status

         if(isset($fields['status']) && in_array($fields['status'], ['completed', 'cancelled'])){
            if($order->table_id){
               Table::where('id', $order->table_id)->update(['status' => 'available']);
            }
         }
         DB::commit();

         $order->load([
             'table',
             'tableMerge',
             'user',
             'steward',
             'orderType',
             'openedBy',
             'closedBy',
             'cancelledBy',
             'orderItems.item',
         ]);

         return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order->fresh(),
         ],200);
      }catch (AuthorizationException $e){
         DB::rollBack();
         return response()->json([
            'message' => 'Unauthorize to update order',
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
            'message' => 'Order not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){
         DB::rollBack();
         return response()->json([
            'message' => 'An error occured while updating order',
            'error' => $e->getMessage(), 
         ],500);
      }
   }

   //update order status with proper timestamp handling
   public function updateStatus(Request $request, Order $order)
   {
      try{
         Gate::authorize('updateStatus', $order);

         $fields = $request->validate([
            'status' => 'required|string|in:pending,confirmed,preparing,ready,served,completed,cancelled',
            'cancel_reason' => 'required_if:status,cancelled|nullable|string|max:500',
         ]);

         DB::beginTransaction();

         $timestampData = [];

         switch($fields['status']){
            case 'confirmed' : 
               if(!$order->confirmed_at){
                  $timestampData['confirmed_at'] = now();
               }
               break;
            case 'preparing' :
               if(!$order->prepared_at){
                  $timestampData['prepared_at'] = now();
               }
               break;
            case 'served' :
               if(!$order->served_at){
                  $timestampData['served_at'] = now();
               }
               break;
            case 'completed' :
               if(!$order->completed_at){
                  $timestampData['closed_at'] = now();
                  $timestampData['closed_by'] = $request->user()->id;
               }
               break;
            case 'cancelled' :
               if(!$order->cancelled_at){
                  $timestampData['cancelled_at'] = now();
                  $timestampData['cancelled_by'] = $request->user()->id;
                  $timestampData['cancel_reason'] = $fields['cancel_reason'] ?? 'Cancel via API';

                  if($order->payment_status === 'paid'){
                     $timestampData['payment_status'] = 'refunded';
                  }
               }
               break;
         }

         $updateData = array_merge(['status' => $fields['status']], $timestampData);
         $order->update($updateData);

         //update table status based on order completion/cancellation
         if(in_array($fields['status'], ['completed', 'cancelled'])){
             Table::where('id', $order->table_id)->update(['status' => 'available']);
         }

         DB::commit();

         $order->load([
            'table',
            'user',
            'steward',
            'orderType',
            'orderItems.item'
         ]);

         return response()->json([
             'message' => 'Order status updated successfully',
             'order' => $order,
         ]);
      }catch (AuthorizationException $e){
         DB::rollBack();
         return response()->json([
            'message' => 'Unauthorized to update order status',
            'error' => $e->getMessage()
         ],403);
      }catch (ValidationException $e) {
         DB::rollBack();
         return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
         ], 422);
      } catch (Exception $e) {
         DB::rollBack();
         return response()->json([
            'message' => 'An error occurred while updating order status',
            'error' => $e->getMessage()
         ],500);
      }
   }

   //get active orders
   public function active()
   {
      try{
        Gate::authorize('viewAny', Order::class);

        $activeOrders = Order::with([
            'table',
            'user',
            'steward',
            'orderType',
            'orderItems.item'
        ])
          ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'served'])
          ->orderBy('created_at', 'desc')
          ->get();
         
      return response()->json([
         'message' => 'Active retrived successfully',
         'orders' => $activeOrders,
      ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to view active orders',
            'error' => $e->getMessage(), 
         ],403);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving order',
            'error' => $e->getMessage(), 
         ],500);
      }
   }

   //get orders by table
   public function getTable(Table $table)
   {
      try{
         Gate::authorize('vewAny', $table);
         
         $order = Order::with([
            'user',
            'steward',
            'orderType',
            'orderItems.item'
         ]) 
         ->where('table_id', $table->id)
         ->orderBy('created_at', 'desc')
         ->get();

         return response()->json([
            'message' => 'Table orders retreived successfully',
            'orders' => $order,
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
           'message' => 'Unauthorize to view table orders',
           'error' => $e->getMessage(),
         ],403);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving table orders',
            'error' => $e->getMessage(),
         ],500);
      }
   }

    public function cancel(Request $request, Order $order)
    {
        try {
            Gate::authorize('update', $order);

            if ($order->status !== 'pending') {
                return response()->json([
                    'message' => 'Cannot cancel order in status ' . $order->status,
                    'error' => 'Only pending orders can be cancelled',
                ], 422);
            }

            DB::beginTransaction();

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => $request->user()->id,
                'cancel_reason' => $request->cancel_reason ?? 'Cancelled by customer'
            ]);

            if ($order->table_id) {
                Table::where('id', $order->table_id)->update(['status' => 'available']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order cancelled successfully',
                'order' => $order->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Unauthorized to cancel this order',
                'error' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while cancelling the order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function confirmPayment(Request $request, Order $order)
    {
        try {
            Gate::authorize('update', $order);

            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'card'
            ]);

            return response()->json([
                'message' => 'Payment confirmed successfully',
                'order' => $order->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to confirm payment for this order',
                'error' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while confirming payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
