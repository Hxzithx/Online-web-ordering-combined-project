<?php


namespace App\Http\Controllers;

use App\Models\Role;
use App\Traits\SyncsData;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = Role::class;

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Role::class);

           $role = Role::all();

           return response()->json([
              'message' => 'Roles retrieved successfully',
              'roles' => $role,
           ],200);
        }catch (AuthenticationException $e){
           return response()->json([
              'message' => 'Unauthorize to view roles',
              'error' => $e->getMessage(),   
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving roles',
              'error' => $e->getMessage(), 
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', Role::class);

           $fields = $request->validate([
              'name' => 'required|string|max:255',
              'description' => 'nullable|string',
           ]);

           $fields['user_id'] = $request->user()->id;

           $role = Role::create($fields);

           return response()->json([
              'message' => 'Role created successfully',
              'role' => $role, 
           ],201);
        }catch (AuthenticationException $e){
           return response()->json([
              'message' => 'Unauthorize to create role',
              'error' => $e->getMessage(),       
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating role',
              'error' => $e->getMessage(),     
           ],500); 
        }
    }

    public function show(Role $role)
    {
        try{
           Gate::authorize('view', $role);

           return response()->json([
              'message' => 'Role retrieved successfully',
              'role' => $role,
           ],200);
        }catch (AuthenticationException $e){
           return response()->json([
              'message' => 'Unauthorize to view role',
              'error' => $e->getMessage(),  
           ],403); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Role not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving role',
              'error' => $e->getMessage(),    
           ],500); 
        }
    }

    public function update(Request $request, Role $role)
    {
        try{
           Gate::authorize('update', $role);
           
           $fields = $request->validate([
              'name' => 'sometimes|required|string|max:255',
              'description' => 'sometimes|nullable|string',
           ]);

           $fields['user_id'] = $request->user()->id;

           $role->update($fields);

           return response()->json([
              'message' => 'Role updated successfully',
              'role' => $role->fresh(),
           ],201);
        }catch (AuthenticationException $e){
           return response()->json([
              'message' => 'Unauthorize to update roles',
              'error' => $e->getMessage(),
           ],403); 
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422); 
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Role not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating role',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function destroy(Role $role)
    {
        try{
           Gate::authorize('delete', $role);

           $role->delete();

           return response()->json([
              'message' => 'Role deleted successfully',
           ],200);
        }catch (AuthenticationException $e){
           return response()->json([
              'message' => 'Unauthorize to delete role',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'Role not found',
              'error' => $e->getMessage(),
           ],404); 
        }catch (Exception $e){
           if(
             strpos($e->getMessage(), 'foreign key constraint') !== false ||
             strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
           ) {
             return response()->json([
                'message' => 'Cannot delete role as it is currently in use',
                'error' => 'This role is assigned to one or more users',
             ],409);
           }
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while deleting role',
              'error' =>  $e->getMessage(),
           ],500); 
        }
    }
}