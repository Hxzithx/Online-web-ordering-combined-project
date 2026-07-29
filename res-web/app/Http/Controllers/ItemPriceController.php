<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemPrice;
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

class ItemPriceController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = ItemPrice::class;

    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
          Gate::authorize('viewAny', ItemPrice::class); 

          $itemPrices = ItemPrice::with(['item', 'variant', 'portion', 'orderType'])->
          orderBy('item_id')->
          orderBy('order_type_id')->
          get();

          return response()->json([
             'message' => 'Item prices retrieved successfully',
             'item_prices' => $itemPrices, 
          ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view item prices',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving item prices',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function store(Request $request)
    {
      try{
         Gate::authorize('create', ItemPrice::class);

         $fields = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'variant_id' => 'nullable|integer|exists:variants,id',
            'portion_id' => 'nullable|integer|exists:portions,id',
            'order_type_id' => 'required|integer|exists:order_types,id',
            'cost_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'package_charge' => 'nullable|numeric|min:0',
            'have_deposit_item' => 'sometimes|boolean',
            'deposit_item_id' => 'nullable|exists:deposit_items,id',
         ]);

         $existingPrice = ItemPrice::where('item_id', $fields['item_id'])->
         where('variant_id', $fields['variant_id'])->
         where('portion_id', $fields['portion_id'])->
         where('order_type_id', $fields['order_type_id'])->
         first();

         if($existingPrice){
            return response()->json([
               'message' => 'A price already exists for this item combination',
               'error' => 'Duplicate price combination',
            ],409);
         }

         $itemPrices = ItemPrice::create($fields);
         $itemPrices->load(['item', 'variant', 'portion', 'orderType']);

         return response()->json([
            'message' => 'Item price created successfully',
            'item_price' => $itemPrices,    
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to create item price',
            'error' => $e->getMessage(),  
         ],403);
      }catch (ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ],422);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while creating item price',
            'error' => $e->getMessage(),
         ],500);
      }
    }

    public function show(ItemPrice $itemPrices)
    {
        try{
           Gate::authorize('view', $itemPrices);

           return response()->json([
              'message' => 'Item prices retrieved successfully',
              'item_price' => $itemPrices,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view item prices',
              'error' => $e->getMessage(),
           ],403); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Item price not found',
              'error' => $e->getMessage(),   
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving item price',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function update(Request $request, ItemPrice $itemPrices)
    {
      try{
         Gate::authorize('update', $itemPrices);
         
         $fields = $request->validate([
            'item_id' => 'sometimes|required|integer|exists:items,id',
            'variant_id' => 'sometimes|nullable|integer|exists:variants,id',
            'portion_id' => 'sometimes|nullable|integer|exists:portions,id',
            'order_type_id' => 'sometimes|required|integer|exists:order_types,id',
            'cost_price' => 'sometimes|nullable|numeric|min:0',
            'price' => 'sometimes|required|numeric|min:0',
            'package_charge' => 'sometimes|nullable|numeric|min:0',
            'have_deposit_item' => 'sometimes|boolean',
            'deposit_item_id' => 'sometimes|nullable|exists:deposit_items,id',
         ]);

         //check for duplicate price

         if(
            isset($fields['item_id']) ||
            isset($fields['variant_id']) ||
            isset($fields['portion_id']) ||
            isset($fields['order_type_id'])
         ){
            $itemId = $fields['item_id'] ?? $itemPrices->item_id;
            $variantId = $fields['variant_id'] ?? $itemPrices->variant_id;
            $portionId = $fields['portion_id'] ?? $itemPrices->portion_id;
            $orderTypeId = $fields['order_type_id'] ?? $itemPrices->order_type_id;

            $existingPrice = ItemPrice::where('item_id', $itemId)->
               where('variant_id', $variantId)->
               where('portion_id', $portionId)->
               where('order_type_id', $orderTypeId)->
               where('id', '!=', $itemPrices->id)->
               first();

            if($existingPrice){
            return response()->json([
               'message' => 'Another price already exists for this item combination',
               'error' => 'Duplicate price combination',
            ],409);
         }
      }

       $itemPrices->update($fields);
       $itemPrices->load(['item', 'variant', 'portion', 'orderType']);

       return response()->json([
          'message' => 'Item price updated successfully',
          'item_price' => $itemPrices->fresh(['item', 'variant', 'portion', 'orderType']),
       ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorized to update this item price',
            'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Item price not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while updating the item price',
              'error' => $e->getMessage()
            ],500);
        }
     }

     public function destroy(ItemPrice $itemPrices)
     {
      try{
         Gate::authorize('delete', $itemPrices);

         if(!$itemPrices->variant_id && !$itemPrices->portion_id){
            $hasOtherPrices = ItemPrice::where('item_id', $itemPrices->item_id)->
            where('order_type_id', $itemPrices->order_type_id)->
            where('id', '!=', $itemPrices->id)->
            exists();

            if(!$hasOtherPrices){
               return response()->json([
                  'message' => 'Can not delete base price. At least must exists for for each item and order type combination.',
                  'error' => 'Base price required',
               ],409);
            }
         }

         $itemPrices->delete();

         return response()->json([
            'message' => 'Item price deleted successfully',
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauuthorize to delete item price',
            'error' => $e->getMessage(), 
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Item price not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){
         if(
            strpos($e->getMessage(), 'foreign key constraint') !== false ||
            strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
         ){
            return response()->json([
               'message' => 'Cannot delete item price as it is currently in use',
               'error' => 'This item price is referenced in orders',
            ],409);
         }
         return response()->json([
            'message' => 'An error occured while deleting item price',
            'error' => $e->getMessage(),
         ],500);
      }
   }

   //get price for specific item
   public function getPrice(Request $request)
   {
      try{
         Gate::authorize('viewAny', ItemPrice::class);

         $fields = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'variant_id' => 'nullable|integer|exists:variants,id',
            'portion_id' => 'nullable|integer|exists:portions,id',
            'order_type_id' => 'required|integer|exists:order_types,id',
         ]);

         $itemPrices = ItemPrice::where('item_id', $fields['item_id'])->
               where('variant_id', $fields['variant_id'])->
               where('portion_id', $fields['portion_id'])->
               where('order_type_id', $fields['order_type_id'])->
               first();

         if(!$itemPrices){

            $itemPrices = ItemPrice::where('item_id', $fields['item_id'])->
               whereNull('variant_id', $fields['variant_id'])->
               whereNull('portion_id', $fields['portion_id'])->
               where('order_type_id', $fields['order_type_id'])->
               first();

            if(!$itemPrices){
               return response()->json([
                  'message' => 'No price found in this combination',
                  'error' => 'Price not found',
               ],404);
            }
         }

         $itemPrices->load(['item', 'variant', 'portion', 'orderType']);

         return response()->json([
            'message' => 'Price retrieved successfully',
            'item_price' => $itemPrices, 
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to search item prices',
            'error' => $e->getMessage(),
         ],403);
      }catch (ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(), 
         ],422);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retrieving item prices',
            'error' => $e->getMessage(), 
         ],500);
      }
   }

   //get all prices for specific item
   public function getItemPrices($itemId)
   {
      try{
        Gate::authorize('viewAny', ItemPrice::class);

        $item = Item::findOrFail($itemId);

        $item->load(['category', 'variant', 'portion', 'kitchen']);
        $itemPrices = ItemPrice::with(['variant', 'portion', 'orderType'])->
              where('item_id', $itemId)->
              orderBy('variant_id')->
              orderBy('portion_id')->
              get();

            return response()->json([
               'message' => 'Item price retrieved successfully',
               'item' => $item,
               'item_price' => $itemPrices,    
            ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to search item price',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'Item not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while retreiving item prices',
            'error' => $e->getMessage(),
         ],500);
      }
   }

   public function getPricesByOrderType($orderTypeId)
   {
      try{
         Gate::authorize('viewAny', ItemPrice::class);

         $orderType = OrderType::findOrFail($orderTypeId);

         $itemPrices = ItemPrice::with(['item', 'variant', 'portion'])->
                    where('order_type_id', $orderTypeId)->
                    orderBy('item_id')->
                    get();
         
         return response()->json([
            'message' => 'Prices for order type retrieved successfully',
            'order_type' => $orderType,
            'item_price' => $itemPrices,
         ],200);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorized to view prices',
            'error' => $e->getMessage()
         ],403);
      }catch (ModelNotFoundException $e) {
         return response()->json([
            'message' => 'Order type not found',
            'error' => $e->getMessage()
         ],404);
      }catch (Exception $e) {
         return response()->json([
            'message' => 'An error occurred while retrieving prices',
            'error' => $e->getMessage()
         ],500);
      }
   }
}