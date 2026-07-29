<?php

namespace App\Http\Controllers;

use App\Models\Portion;
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

class PortionController extends Controller implements HasMiddleware
{
    use SyncsData;

    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum'),
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Portion::class);

           $portion = Portion::all();

           return response()->json([
              'message' => 'Portions retrieve successfully',
              'portions' => $portion,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view portions',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving portion',
              'error' => $e->getMessage(),
           ],500);  
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', Portion::class);

           $fields = $request->validate([
              'name' => 'required|string|max:255|unique:portions,name',
              'description' => 'nullable|string',
              'multiplier' => 'nullable|numeric|min:0',
              'additional_price' => 'nullable|numeric|min:0'
           ]);

           $portion = Portion::create($fields);

           return response()->json([
              'message' => 'Portion created successfully',
              'portion' => $portion,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create portions',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),     
           ],422);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating portion',
              'error' => $e->getMessage(),
           ],500);  
        }
    }

    public function show(Portion $portion)
    {
        try{
           Gate::authorize('view', $portion);

           return response()->json([
              'message' => 'Portion retrieved successfully',
              'portion' => $portion,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view portion',
              'error' => $e->getMessage(), 
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Portion not found',
              'error' => $e->getMessage(),
           ]);  
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving portion',
              'error' => $e->getMessage(),
           ],500);  
        }
    }

    public function update(Request $request, Portion $portion)
    {
        try{
           Gate::authorize('update', $portion);

           $fields = $request->validate([
              'name' => 'sometimes|required|string|max:255|unique:portions,name',
              'description' => 'sometimes|nullable|string',
              'multiplier' => 'sometimes|nullable|numeric|min:0',
              'additional_price' => 'sometimes|nullable|numeric|min:0'
           ]);

           $portion->update($fields);

           return response()->json([
              'message' => 'Portion updated successfully',
              'portion' => $portion->fresh(),
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update portion',
              'error' => $e->getMessage(), 
           ],403); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Portion not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating portion',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(Portion $portion)
    {
        try{
           Gate::authorize('delete', $portion);

           if($portion->Items()->exists()){
            return response()->json([
                'message' => 'Cannot delete portion as it is assigned to one or more items',
                'error' => 'This portion is currently in use'
                ], 409);
           }

           $portion->delete();

           return response()->json([
              'message' => 'Portion deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to delete portion',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Portion not found',
              'error' => $e->getMessage(),  
           ],404); 
        }catch (Exception $e){
            if(
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ){
                return response()->json([
                   'message' => 'Cannot delete portion as it is currently in use',
                    'error' => 'This portion is assigned to one or more items'
                ],409);
            }
            return response()->json([
               'message' => 'An error occured while deleting portion',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function search(Request $request)
    {
        try{
          Gate::authorize('view', Portion::class);

          $request->validate([
             'query' => 'required|string|min:1'
          ]);
           $query = $request->input('query');

           $portion = Portion::where('name', 'like', "%{$query}%")
           ->orWhere('description', 'like', "%{$query}%");

           return response()->json([
              'message' => 'Portion retrieved successfully',
              'portion' => $portion, 
           ],200);
        }catch (AuthorizationException $e) {
           return response()->json([
              'message' => 'Unauthorized to search portions',
              'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while searching portions',
              'error' => $e->getMessage()
            ],500);
        }
    }
}