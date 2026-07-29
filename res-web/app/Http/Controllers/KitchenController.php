<?php

namespace App\Http\Controllers;

use App\Models\Kitchen;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class KitchenController extends Controller implements HasMiddleware
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
           Gate::authorize('viewAny', Kitchen::class);

           $kitchen = Kitchen::all();

           return response()->json([
              'message' => 'Kitchens retrieved successfully',
              'kitchens' => $kitchen,  
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view kitchens',
              'error' => $e->getMessage()
            ],403);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while retrieving kitchens',
              'error' => $e->getMessage()
            ],500);
        } 
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', Kitchen::class);

           $fields = $request->validate([
              'name' => 'required|string|max:255',
              'location' => 'required|string|max:255',
              'status' => 'required|string|max:255',
           ]);

           $kitchen = Kitchen::create($fields);

           return response()->json([
              'message' => 'Kitchen created successfully',
              'kitchen' => $kitchen,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to create kitchens',
              'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
           return response()->json([
               'message' => 'Validation failed',
               'errors' => $e->errors()
            ],422);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while creating the kitchen',
              'error' => $e->getMessage()
            ],500);
        }
    }

    public function show(Kitchen $kitchen)
    {
        try{
           Gate::authorize('view', $kitchen);

          return response()->json([
             'message' => 'Kitchen retrieved successfully',
             'kitchen' => $kitchen,
          ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view this kitchen',
              'error' => $e->getMessage()
            ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
            'message' => 'Kitchen not found',
            'error' => $e->getMessage()
            ],404);
        } catch (Exception $e) {
            return response()->json([
               'message' => 'An error occurred while retrieving the kitchen',
               'error' => $e->getMessage()
            ],500);
        }
    }

    public function update(Request $request, Kitchen $kitchen)
    {
        try{
          Gate::authorize('update', $kitchen);

          $fields = $request->validate([
              'name' => 'sometimes|required|string|max:255',
              'location' => 'sometimes|required|string|max:255',
              'status' => 'sometimes|required|string|max:255', 
          ]);

          $kitchen->update($fields);
          $fields['user_id'] = $request->user()->id;
          return response()->json([
             'message' => 'Kitchen updated successfully',
             'kitchen' => $kitchen->fresh(),
          ],200);
        }catch (AuthorizationException $e){
           return response()->json([
                'message' => 'Unauthorized to update this kitchen',
                'error' => $e->getMessage()
            ], 403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Kitchen not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while updating the kitchen',
              'error' => $e->getMessage()
            ],500);
        }
    }

    public function destroy(Kitchen $kitchen)
    {
        try{
           Gate::authorize('delete', $kitchen);

           $kitchen->delete();

           return response()->json([
              'message' => 'Kitchen delete successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
                'message' => 'Unauthorized to delete this kitchen',
                'error' => $e->getMessage()
            ], 403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Kitchen not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete kitchen as it is currently in use',
                   'error' => 'This kitchen is assigned to one or more orders or items'
                ],409);
            }

            return response()->json([
               'message' => 'An error occurred while deleting the kitchen',
               'error' => $e->getMessage()
            ],500);
        }
    }
}
