<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VariantController extends Controller implements HasMiddleware
{
    use SyncsData;

    public static function middleware()
    {
        return[
          new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Variant::class);

           $variant = Variant::all();

           return response()->json([
              'message' => 'Variant retrieve successfully',
              'variants' => $variant,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view variants',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving variants',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', Variant::class);

           $fields = $request->validate([
               'name' => 'required|string|max:255|unique:variants,name',
               'description' => 'nullable|string',
               'multiplier' => 'nullable|numeric|min:0',
               'additional_price' => 'nullable|numeric|min:0',
           ]);

           $variant = Variant::create($fields);

           return response()->json([
              'message' => 'Variant create successfully',
              'variant' => $variant,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create variant',
              'error' => $e->getMessage(), 
           ],403);          
        }catch (ValidationException $e){
            return response()->json([
               'message' => 'Validation failed',
               'error' => $e->errors(),
            ],422);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while creating variant',
               'error' => $e->getMessage(), 
            ],500);
        }
    }

    public function show(Variant $variant)
    {
        try{ 
           Gate::authorize('view', $variant);

           return response()->json([
              'message' => 'Variant retrieve successfully',
              'variant' => $variant,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view variant',
              'error' => $e->getMessage(),   
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Variant not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving variant',
              'error' => $e->getMessage(), 
           ],500);
        }
    }

    public function update(Request $request, Variant $variant)
    {
        try{
           Gate::authorize('update', $variant);

           $fields = $request->validate([
               'name' => 'required|string|max:255|unique:variants,name',
               'description' => 'nullable|string|max:255',
               'multiplier' => 'nullable|numeric|min:0',
               'additional_price' => 'nullable|numeric|min:0',
           ]);

           $variant->update($fields);

           return response()->json([
              'message' => 'Variant updated successfully',
              'variant' => $variant->fresh(),  
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update variant',
              'error' => $e->getMessage(), 
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Variant not found to update',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating variant',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(Variant $variant)
    {
        try{
           Gate::authorize('delete', $variant);

           if($variant->Items()->exsists()){
            return response()->json([
                'message' => 'Cannot delete variant as it is assigned to one or more items',
                'error' => 'This variant is currently in use'
                ], 409);
           }
           $variant->delete();

           return response()->json([
              'message' => 'Variant deleted successfully'
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
               'message' => 'Unauthorized to delete this variant',
               'error' => $e->getMessage()
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
               'message' => 'Variant not found',
               'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
             return response()->json([
                'message' => 'Cannot delete variant as it is currently in use',
                'error' => 'This variant is assigned to one or more items'
                ], 409);
            }

             return response()->json([
                'message' => 'An error occurred while deleting the variant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try{
           Gate::authorize('view', Variant::class);

           $request->validate([
              'query' => 'required|string|min:1'
           ]);

           $query = $request->input('query');

           $variant = Variant::where('id', 'like', "%{$query}%")->orWhere('description', 'like', "%{$query}%")->get();

           return response()->json([
              'message' => 'Variant retrieved successfully',
               'variant' => $variant,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to search variants',
              'error' => $e->getMessage(),    
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Variant not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving variant',
              'error' => $e->getMessage(),
           ],500);
        }
    }
}