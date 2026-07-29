<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller implements HasMiddleware
{
    use SyncsData;

    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum'),
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', User::class);

           $user = User::all();

           return response()->json([
              'message' => 'Users retrieved successfully',
              'users' => $user,    
           ],200); 
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => ' Unauthorize to view users',
              'error' => $e->getMessage(),
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving users',
              'error' => $e->getMessage(),
           ],500); 
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', User::class);

           $fields = $request->validate([
              'branch_id' => 'sometimes|nullable|integer',
              'first_name' => 'required|string|max:255',
              'last_name' => 'required|string|max:255',
              'email' => 'required|string|max:255|unique:users',
              'phone' => 'required|string|max:15',
              'address' => 'required|string|max:500',
              'role' => 'required|string|max:255',
              'password' => 'required|string|min:8|confirmed',
              'status' => 'sometimes|string|in:active,inactive'
           ]);
           
           $fields['password'] = Hash::make($fields['password']);
           $fields['status'] = $fields['status'] ?? 'active';
           $fields['user_id'] = $request->user()->id;

           $user = User::create($fields);

           return response()->json([
              'message' => 'User created successfully',
              'user' => $user,     
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create user',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
             'message' => 'Validation failed',
             'error' => $e->errors(),
           ],422); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while creating customer',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function show(User $user)
    {
        try{
           Gate::authorize('view', $user);

           return response()->json([
              'message' => 'User retrieved successfully',
              'user' => $user,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
             'message' => 'Unauthorize to view users',
             'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'User not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
           return response()->json([
             'message' => 'An error occurred while retrieving the user',
             'error' => $e->getMessage()
            ],500);
        }
    }

    public function update(Request $request, User $user)
    {
        try{
           Gate::authorize('update', $user);
           
           $fields = $request->validate([
              'branch_id' => 'sometimes|nullable|integer',
              'first_name' => 'sometimes|required|string|max:255',
              'last_name' => 'sometimes|required|string|max:255',
              'email' => 'sometimes|required|string|max:255|unique:users',
              'phone' => 'sometimes|required|string|max:15',
              'address' => 'sometimes|required|string|max:500',
              'role' => 'sometimes|required|string|max:255',
              'password' => 'sometimes|required|string|min:8|confirmed',
              'status' => 'sometimes|sometimes|string|in:active,inactive'
           ]);

           if(isset($fields['password'])){
             $request->validate([
                'password' => 'sometimes|required|string|min:8|confirmed',
             ]);
             $fields['password'] = Hash::make($fields['password']);
           }
           $fields['user_id'] = $request->user()->id;

           $user->update($fields);

           return response()->json([
              'message' => 'User updated successfully',
              'user' => $user,
           ]);
        }catch (AuthorizationException $e){
           return response()->json([
                'message' => 'Unauthorized to update this user',
                'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'User not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
           return response()->json([
             'message' => 'An error occurred while updating the user',
             'error' => $e->getMessage()
            ],500);
        }
    }

    public function destroy(User $user)
    {
      try{
         Gate::authorize('delete', $user);

         $user->delete();

         return response()->json([
            'message' => 'User deleted successfully',
         ]);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to delete user',
            'error' => $e->getMessage(),
         ],403);
      }catch (ModelNotFoundException $e){
         return response()->json([
            'message' => 'User not found',
            'error' => $e->getMessage(),
         ],404);
      }catch (Exception $e){
         return response()->json([
            'message' => 'An error occured while deleting user',
            'error' => $e->getMessage(),
         ],500);
      }
   }

   public function updateStatus(Request $request, User $user)
   {
      try{
         Gate::authorize('update', $user);

         $request->validate([
            'status' => 'required|string|in:active,inactive',
         ]);

         $user->update(['status' => $request->status]);

         return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
         ],201);
      }catch (AuthorizationException $e){
         return response()->json([
            'message' => 'Unauthorize to delete users',
            'error' => $e->getMessage(), 
         ],403);
      }catch (ValidationException $e) {
         return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
         ],422);
      }catch (Exception $e) {
         return response()->json([
            'message' => 'An error occurred while updating user status',
            'error' => $e->getMessage()
         ],500);
      }
   }
}
