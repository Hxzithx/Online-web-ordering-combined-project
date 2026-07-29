<?php

namespace App\Http\Controllers;

use App\Models\Steward;
use App\Traits\SyncsData;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class StewardController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model =  Steward::class;

    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum') 
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Steward::class);

           $steward = Steward::all();

           return response()->json([
              'message' => 'Stewards retrieved successfully',
              'stewards' => $steward,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view stewards',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving stewards',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function store(Request $request, Steward $steward)
    {
        try{
           Gate::authorize('create', $steward);

           $fields = $request->validate([
              'user_id' => 'sometimes|nullable|exists:users,id',
              'branch_id' => 'sometimes|nullable|integer',
              'name' => 'required|string|max:255',
              'employee_id' => 'required|string|max:255|unique:stewards,employee_id',
              'is_active' => 'sometimes|boolean',
              'status' => 'sometimes|string|max:255',
           ]);

           if(!isset($fields['status'])){
              $fields['status'] = 'available';
           }

           if(!isset($fields['is_active'])){
              $fields['is_active'] = true; 
           }

           $steward = Steward::create($fields);

           return response()->json([
              'message' => 'Steward create successfully',
              'steward' => $steward,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create steward',
              'error' => $e->getMessage(),  
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating steward',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function show(Steward $steward)
    {
        try{
           Gate::authorize('view', $steward);

           return response()->json([
              'message' => 'Steward retrieved successfully',
              'error' => $steward,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view steward',
              'error' => $e->getMessage(),                      
           ],403); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Steward not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving steward',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function update(Request $request, Steward $steward)
    {
        try{
           Gate::authorize('update', $steward);
            $fields = $request->validate([
              'user_id' => 'sometimes|nullable|exists:users,id',
              'branch_id' => 'sometimes|nullable|integer',
              'name' => 'sometimes|required|string|max:255',
              'employee_id' => 'sometimes|required|string|max:255|unique:stewards,employee_id',
              'is_active' => 'sometimes|boolean',
              'status' => 'sometimes|string|max:255',
            ]);

            $steward->update($fields);

            return response()->json([
               'message' => 'Steward updated successfully',
               'steward' => $steward->fresh(),
            ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update steward',
              'error' => $e->getMessage(),  
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Steward not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating steward',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function destroy(Steward $steward)
    {
        try{
           Gate::authorize('delete', $steward);

           if($steward->orders()->exists()){
             return response()->json([
                'message' => 'Cannot delete steward as they have associated orders',
                'error' => 'Steward is in use',
             ],409);
           }

           $steward->delete();

           return response()->json([
              'message' => 'Steward deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to delete steward',
              'error' => $e->getMessage(),  
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Steward not found',
              'error' => $e->getMessage(),   
           ],404); 
        }catch (Exception $e){
            if(
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ){
                return response()->json([
                   'message' => 'Cannot delete steward as they have associated records',
                   'error' => 'Steward is referenced in other tables',  
                ],409);
            }
            return response()->json([
               'message' => 'An error occured while deleting steward',
               'error' => $e->getMessage(),
            ],422);
        }
    }

    public function search(Request $request)
    {
        try{
           Gate::authorize('viewAny', Steward::class);

            $request->validate([
              'query' => 'required|string|min:1',
              'inlude_inactive' => 'sometimes|boolean',
           ]);

           $query = $request->input('query');
           $includeInactive = $request->input('include_inactive', false);

           $steward = Steward::where('name', 'like', "%{$query}%")
           ->orWhere('employee_id', 'like', "%{$query}%")->when(!$includeInactive, function ($query) {
              return $query->where('is_active', true);
           })->orderBy('name')->get();

           return response()->json([
              'message' => 'Steward retieved successfully',
              'steward' => $steward, 
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to search steward',
              'error' => $e->getMessage(),
           ]);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while searching stewards',
              'error' => $e->getMessage()
            ],500);
        }
    }
}