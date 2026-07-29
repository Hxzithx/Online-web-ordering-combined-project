<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class PackageController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model =  Package::class;
    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Package::class);

           $package = Package::all();

           return response()->json([
              'message' => 'Packages retrieved successfully',
              'packages' => $package,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view packages',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving packages',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', Package::class);

           $fields = $request->validate([
              'name' => 'required|string|max:255',
              'code' => 'required|string|max:50|unique:packages,code',
              'description' => 'nullable|string',
              'cost_price' => 'nullable|integer|min:0',
              'actual_price' => 'required|integer|min:0',
              'discount_price' => 'nullable|integer|min:0',
              'price' => 'required|integer|min:0',
              'status' => 'sometimes|string|in:active,inactive',
           ]);

           $package = Package::create($fields);

           return response()->json([
              'message' => 'Package created successfully',
              'package' => $package,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create package',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating package',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function show(Package $package)
    {
        try{
           Gate::authorize('view', $package);

           return response()->json([
              'message' => 'Package retrieved successfully',
              'package' => $package,         
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view package',
              'error' => $e->getMessage(),   
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Package not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving package',
              'error' => $e->getMessage(),  
           ],500); 
        }
    }

    public function update(Request $request, Package $package)
    {
        try{
           Gate::authorize('update', $package);

           $fields = $request->validate([
              'name' => 'sometimes|required|string|max:255',
              'code' => 'sometimes|required|string|max:50|unique:packages,code',
              'description' => 'sometimes|nullable|string',
              'cost_price' => 'sometimes|nullable|integer|min:0',
              'actual_price' => 'sometimes|required|integer|min:0',
              'discount_price' => 'sometimes|nullable|integer|min:0',
              'price' => 'sometimes|required|integer|min:0',
              'status' => 'sometimes|string|in:active,inactive',
           ]);

           $package->update($fields);

           return response()->json([
              'message' => 'Package updated successfully',
              'package' => $package->fresh(),
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message'=> 'Unauthorize to update package',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Package not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating package',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(Package $package)
    {
        try{
           Gate::authorize('delete', $package);

           $package->delete();

           return response()->json([
              'message' => 'Package deleted successfully',   
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to delete package',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           if(
            strpos($e->getMessage(), 'foreign key constraint') !== false ||
            strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
           ){
            return response()->json([
               'message' => 'Cannot delete package as it is currently in use',
               'error' => 'This package is referenced in other records', 
            ],409);
           }

           return response()->json([
              'message' => 'An error occured while deleting package',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function search(Request $request)
    {
        try{
           Gate::authorize('viewAny', Package::class);

           $query = $request->input('query');
           $package = Package::where('name', 'like', "%{$query}%")->orWhere('code', 'like', "%{$query}%")->get();

           return response()->json([
              'message' => 'Package retreived successfully',
              'package' => $package,    
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to search packages',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving package',
              'error' => $e->getMessage(),  
           ],404); 
        }
    }
}