<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AddOnController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = AddOnController::class;

    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum')
        ];
        
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', AddOn::class);

           $addOns = AddOn::all();

           return response()->json([
              'message' => 'Add Ons retrieved successfully',
              'AddOn' => $addOns,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view AddOns',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving AddOns',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function store(Request $request, AddOn $addOns)
    {
        try{
           Gate::authorize('create', $addOns);

           $fields = $request->validate([
              'name' => 'required|string|max:255',
              'cost_price' => 'nullable|numeric|min:0',
              'price' => 'required|numeric|min:0',
              'packaging_charge' => 'sometimes|numeric|min:0',
              'include_tax' => 'sometimes|boolean',
              'include_service_charge' => 'sometimes|boolean',
              'status' => 'sometimes|string|in:active,inactive',
           ]);

           $addOns = AddOn::create($fields);

           return response()->json([
              'message' => 'AddOn created successfully',
              'AddOn' => $addOns,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unaothorize to create AddOns',
              'error' => $e->getMessage(),   
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),  
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating AddOns',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(AddOn $addOns)
    {
        try{
           Gate::authorize('view', $addOns);

           return response()->json([
              'message' => 'AddOns retrieved successfully',
              'AddOns' => $addOns,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to retrieve AddOns',   
              'error' => $e->getMessage()
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'AddOn not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while rerieving AddOns',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function update(Request $request, AddOn $addOns)
    {
        try{
           Gate::authorize('update', $addOns);

           $fields = $request->validate([
              'name' => 'sometimes|required|string|max:255',
              'cost_price' => 'sometimes|nullable|numeric|min:0',
              'price' => 'sometimes|required|numeric|min:0',
              'packaging_charge' => 'sometimes|numeric|min:0',
              'include_tax' => 'sometimes|boolean',
              'include_service_charge' => 'sometimes|boolean',
              'status' => 'sometimes|string|in:active,inactive',
           ]);

           $addOns->update($fields);

           return response()->json([
              'message' => 'AddOn updated successfully',
              'AddOn' => $addOns->fresh(),
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update AddOns',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'AddOn not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating AddOn',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(AddOn $addOns)
    {
        try{
           Gate::authorize('delete', $addOns);

           $addOns->delete();

           return response()->json([
              'message' => 'AddOn deleted successfully',
           ]);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to delete this add-on',
              'error' => $e->getMessage()
          ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Add-on not found',
              'error' => $e->getMessage()
          ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete add-on as it is currently in use',
                   'error' => 'This add-on is assigned to one or more order items'
                ],409);
            }

            return response()->json([
               'message' => 'An error occurred while deleting the add-on',
               'error' => $e->getMessage()
          ],500);
        }
    }
}