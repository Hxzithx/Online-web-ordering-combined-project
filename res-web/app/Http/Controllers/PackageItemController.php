<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageItem;
use App\Traits\SyncsData;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class PackageItemController extends Controller implements HasMiddleware
{
    use SyncsData;
    
    protected $model = PackageItem::class;
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', PackageItem::class);

           $packageItem = PackageItem::all();
           return response()->json([
              'message' => 'Package items retrieved successfully',
              'Package_items' => $packageItem,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view package items',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => $e->getMessage(),   
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', PackageItem::class);

           $fields = $request->validate([
              'package_id' => 'required|exists:package,id',
              'item_id' => 'nullable|exists:items,id',
              'extra_service_id' => 'nullable|exists:extra_services,id',
              'quantity' => 'required|integer|min:1',
              'cost_price' => 'nullable|numeric|min:0',
              'price' => 'required|numeric|min:0',
              'status' => 'sometimes|string|in:active,inactive',
           ]);

           if (empty($fields['item_id']) && empty($fields['extra_service_id'])) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => ['item_id' => ['Either item_id or extra_service_id is required.']]
                ], 422);
            }

            $package = Package::findOrFail($fields['package_id']);
            Gate::authorize('update', $package);

           $packageItem = PackageItem::create($fields);

           return response()->json([
              'message' => 'Package item created successfully',
              'package_item' => $packageItem,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create package item',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->getMessage(), 
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating package item',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(PackageItem $packageItem)
    {
        try{
           Gate::authorize('view', $packageItem);

           return response()->json([
              'message' => 'Package item retrieved successfully',
              'package_item' => $packageItem,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view package item',
              'error' => $e->getMessage()
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Package item not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieve package item',
              'error' => $e->getMessage(),    
           ],500); 
        }
    }

    public function update(Request $request, PackageItem $packageItem)
    {
        try{
           Gate::authorize('update', $packageItem);

           $fields = $request->validate([
              'package_id' => 'sometimes|required|exists:package,id',
              'item_id' => 'sometimes|nullable|exists:items,id',
              'extra_service_id' => 'sometimes|nullable|exists:extra_services,id',
              'quantity' => 'sometimes|required|integer|min:1',
              'cost_price' => 'sometimes|nullable|numeric|min:0',
              'price' => 'sometimes|required|numeric|min:0',
              'status' => 'sometimes|string|in:active,inactive',
           ]);

           $packageItem->update($fields);

           return response()->json([
              'message' => 'Package item updated successfully',
              'package_item' => $packageItem->fresh(), 
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to update this package item',
              'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
            return response()->json([
               'message' => 'Validation failed',
               'errors' => $e->errors()
            ],422);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while updating the package item',
              'error' => $e->getMessage()
            ],500);
        }
    }

    public function destroy(PackageItem $packageItem)
    {
        try{
           Gate::authorize('delete', $packageItem);

           $package = Package::findOrFail($packageItem->package_id);
            Gate::authorize('update', $package);

           $packageItem->delete();

           return response()->json([
              'message' => 'Package item deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to delete this package item',
              'error' => $e->getMessage()
            ],403);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while deleting the package item',
              'error' => $e->getMessage()
            ],500);
        }
    }
}