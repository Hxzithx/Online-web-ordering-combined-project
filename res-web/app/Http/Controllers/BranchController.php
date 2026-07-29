<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller implements HasMiddleware
{
    use SyncsData;

    public static function middleware()
    {
        return[
          new Middleware('auth:snactum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', Branch::class);

           $branch = Branch::all();

           return response()->json([
              'message' => 'Brnaches retrieved successfully',
              'bramches' => $branch,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view branches',
              'error' => $e->getMessage(), 
           ],403);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving branches',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function store(Request $request, Branch $branch)
    {
        try{
           Gate::authorize('create', $branch);

           $fields = $request->validate([
              'code' => 'required|string|unique:branches,code|max:255',
              'name' => 'required|string|max:255',
              'legal_name' => 'nullable|string|max:255',
              'address_line_1' => 'nullable|string|max:255',
              'address_line_2' => 'nullable|string|max:255',
              'city' => 'nullable|string|max:255',
              'district' => 'nullable|string|max:255',
              'province' => 'nullable|string|max:255',
              'country' => 'nullable|string|max:255',
              'phone' => 'nullable|string|max:255',
              'email' => 'nullable|email|max:255',
              'currency_code' => 'nullable|string|size:3',
              'tax_profile_id' => 'nullable|integer',
              'is_active' => 'sometimes|boolean',
              'is_live' => 'sometimes|boolean',
           ]);

           $fields['created_by'] = $request->user()->id;
           $branch = Branch::create($fields);

           return response()->json([
              'message' => 'Branch created successfully',
              'branch' => $branch, 
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to create branch',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
            return response()->json([
               'message' => 'Validation failed',
               'error' => $e->errors(),
            ],422);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while creating branch',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function show(Branch $branch)
    {
        try{
           Gate::authorize('view', $branch);

           return response()->json([
              'message' => 'Branch retrieved successfully',
              'branch' => $branch, 
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view branch',
              'error' => $e->getMessage(),
           ],403);
        }catch (ModelNotFoundException $e){
            return response()->json([
               'message' => 'Branch not found',
               'error' => $e->getMessage(),
            ],404);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while retrieving branch',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, Branch $branch)
    {
        try{
           Gate::authorize('update', $branch);

           $fields = $request->validate([
                'code' => 'sometimes|required|string|unique:branches,code,' . $branch->id . '|max:255',
                'name' => 'sometimes|required|string|max:255',
                'legal_name' => 'sometimes|nullable|string|max:255',
                'address_line_1' => 'sometimes|nullable|string|max:255',
                'address_line_2' => 'sometimes|nullable|string|max:255',
                'city' => 'sometimes|nullable|string|max:255',
                'district' => 'sometimes|nullable|string|max:255',
                'province' => 'sometimes|nullable|string|max:255',
                'country' => 'sometimes|nullable|string|max:255',
                'phone' => 'sometimes|nullable|string|max:255',
                'email' => 'sometimes|nullable|email|max:255',
                'currency_code' => 'sometimes|nullable|string|size:3',
                'tax_profile_id' => 'sometimes|nullable|integer',
                'is_active' => 'sometimes|boolean',
                'is_live' => 'sometimes|boolean',
           ]);

           $branch->update($fields);

           return response()->json([
              'message' => 'Branch updated succefully',
              'branch' => $branch->fresh(),
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to update branch',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422);
        }catch (ModelNotFoundException $e){
            return response()->json([
               'message' => 'Branch not found',
               'error' => $e->getMessage(), 
            ],404);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while updating branch',
               'error' => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Branch $branch)
    {
        try{
           Gate::authorize('delete', $branch);

           $branch->delete();

           return response()->json([
              'message' => 'Branch deleted successfully',
           ]);
        }catch (AuthorizationException $e){
           return response()->json([
                'message' => 'Unauthorized to delete this branch',
                'error' => $e->getMessage()
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Branch not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete branch as it is currently in use',
                    'error' => 'This branch is assigned to one or more records'
                ], 409);
            }

            return response()->json([
                'message' => 'An error occurred while deleting the branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }  
}