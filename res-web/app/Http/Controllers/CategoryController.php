<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\SyncsData;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class CategoryController extends Controller implements HasMiddleware
{
   use SyncsData;

   protected $model = Category::class;

   public static function middleware()
   {
      return [
         new Middleware('auth:sanctum')
      ];
   }

   public function index()
   {
      try {
         Gate::authorize('viewAny', Category::class);

         $categories = Category::all();

         return response()->json([
            'message' => 'Category retrieved succefully',
            'categories' => $categories,
         ], 201);
      } catch (AuthorizationException $e) {
         return response()->json([
            'message' => 'Unauthorize to view categories',
            'error' => $e->getMessage(),
         ], 403);
      } catch (Exception $e) {
         return response()->json([
            'message' => 'An error occuren during retrieving category',
            'error' => $e->getMessage(),
         ], 500);
      }
   }

   public function store(Request $request)
   {
      try {
         Gate::authorize('create', Category::class);

         $fields = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
         ]);

         $fields['user_id'] = $request->user()->id;
         $category = Category::create($fields);

         return response()->json([
            'message' => 'Category created succefully',
            'category' => $category,
         ], 201);
      } catch (AuthorizationException $e) {
         return response()->json([
            'message' => 'Unauthorize to create category',
            'error' => $e->getMessage(),
         ], 403);
      } catch (ValidationException $e) {
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ], 422);
      } catch (Exception $e) {
         return response()->json([
            'message' => 'An error occured while creating category',
            'error' => $e->getMessage(),
         ], 500);
      }
   }

   public function show(Category $category)
   {
      try {
         Gate::authorize('view', $category);

         return response()->json([
            'message' => 'Category retrieved successfully',
            'categories' => $category,
         ], 201);
      } catch (AuthorizationException $e) {
         return response()->json([
            'message' => 'Unauthorize to view categories',
            'error' => $e->getMessage(),
         ], 403);
      } catch (ModelNotFoundException $e) {
         return response()->json([
            'message' => 'Category not found',
            'error' => $e->getMessage(),
         ], 404);
      } catch (Exception $e) {
         return response()->json([
            'message' => 'An error occured while retrieving category',
            'error' => $e->getMessage(),
         ], 500);
      }
   }

   public function update(Request $request, Category $category)
   {
      try {
         Gate::authorize('update', $category);

         $fields = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
         ]);

         $fields['user_id'] = $request->user()->id;
         $category->update($fields);

         return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category->fresh(),
         ], 200);
      } catch (AuthorizationException $e) {
         return response()->json([
            'message' => 'Unauthorize to update category',
            'error' => $e->getMessage(),
         ], 403);
      } catch (ValidationException $e) {
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ]);
      } catch (ModelNotFoundException $e) {
         return response()->json([
            'message' => 'Category not found',
            'error' => $e->getMessage(),
         ], 404);
      } catch (Exception $e) {
         return response()->json([
            'message' => 'An error occured while updating category',
            'error' => $e->getMessage(),
         ], 500);
      }
   }

   public function destroy(Category $category)
   {
      try {
         Gate::authorize('delete', $category);

         $category->delete();

         return response()->json([
            'message' => 'Category delete successfully',
         ], 201);
      } catch (AuthorizationException $e) {
         return response()->json([
            'message' => 'Unauthorize to delete category',
            'error' => $e->getMessage(),
         ], 403);
      } catch (ModelNotFoundException $e) {
         return response()->json([
            'message' => 'Category not found',
            'error' => $e->getMessage(),
         ], 404);
      } catch (Exception $e) {

         if (
            strpos($e->getMessage(), 'foreign key constraint') !== false ||
            strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
         ) {
            return response()->json([
               'message' => 'Can not delete category as it is currently in use',
               'error' => 'This category is assigned to one or more records'
            ]);
         }
         return response()->json([
            'message' => 'An error occured while deleting category',
            'error' => $e->getMessage(),
         ], 500);
      }
   }
}