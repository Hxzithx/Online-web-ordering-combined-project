<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
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

class SystemSettingController extends Controller implements HasMiddleware
{
    use SyncsData;

    protected $model = SystemSetting::class;

    public static function middleware()
    {
        return[
           new Middleware('auth:sanctum')
        ];
    }
    
    public function index()
    {
        try{
           Gate::authorize('viewAny', SystemSetting::class);

           $systemSetting = SystemSetting::all();

           return response()->json([
              'message' => 'System settings retrieved successfully',
              'system_setting' => $systemSetting,
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view system setting',
              'error' => $e->getMessage(), 
           ],403);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while retrieving system setting',
               'error' => $e->getMessage(), 
            ],500);
        }
    }

    public function store(Request $request)
    {
        try{
          Gate::authorize('create', SystemSetting::class);

          $fields = $request->validate([
             'name' => 'required|string|max:255',
             'location' => 'nullable|string|max:255',
             'tax_percentage' => 'sometimes|numeric|min:0|max:100',
             'service_charge_percentage' => 'sometimes|numeric|min:0|max:100',
             'key' => 'required|string|uniqe:system_settings,key|max:255',
             'value' => 'nullable|string',
             'group' => 'nullable|string|max:255',
             'data_type' => ['sometimes','string', Rule::in(['string', 'integer', 'boolean', 'decimal', 'json'])],
             'is_editable' => 'sometimes|boolean',
          ]);

          $systemSetting = SystemSetting::create($fields);

            return response()->json([
               'message' => 'System setting created successfully',
               'system_setting' => $systemSetting,
          ],201);
        }catch (AuthorizationException $e){
            return response()->json([
               'message' => 'Unauthorize to create system setting',
               'error' => $e->getMessage(), 
             ],403);
        }catch (ValidationException $e){
            return response()->json([
               'message' => 'Validation failed',
               'error' => $e->errors(),
            ],422);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while creating system setting',
               'error' => $e->getMessage(),
            ],500);
        }
    }

    public function show(SystemSetting $systemSetting)
    {
        try{
           Gate::authorize('view', $systemSetting);

           return response()->json([
              'message' => 'Syste, setting retrieved succefully',
              'system_setting' => $systemSetting,  
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to view system setting',
              'error' => $e->getMessage(), 
           ],403);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'System setting not found',
              'error' => $e->getMessage(),
            ],404);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(),
           ],422);
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while retrieving system setting',
              'error' => $e->getMessage(),  
           ],500);  
        }
    }

    public function update(Request $request, SystemSetting $systemSetting)
    {
        try{
           Gate::authorize('update', $systemSetting);

           if (!$systemSetting->is_editable) {
                return response()->json([
                    'message' => 'This system setting cannot be edited',
                    'error' => 'Setting is marked as non-editable'
                ], 403);
            }

           $fields = $request->validate([
             'name' => 'sometimes|required|string|max:255',
             'location' => 'sometimes|nullable|string|max:255',
             'tax_percentage' => 'sometimes|numeric|min:0|max:100',
             'service_charge_percentage' => 'sometimes|numeric|min:0|max:100',
             'key' => 'sometimes|required|string|uniqe:system_settings,key|max:255',
             'value' => 'sometimes|nullable|string',
             'group' => 'sometimes|nullable|string|max:255',
             'data_type' => ['sometimes','string', Rule::in(['string', 'integer', 'boolean', 'decimal', 'json'])],
             'is_editable' => 'sometimes|boolean',
           ]);

           $systemSetting = SystemSetting::update($fields);

           return response()->json([
              'message' => 'System setting updated successfully',
              'system_setting' => $systemSetting, 
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorize to edit system setting',
              'error' => $e->getMessage(),
           ],403);
        }catch (ValidationException $e){
           return response()->json([
              'message' => 'Validation failed',
              'error' => $e->errors(), 
           ],422);
        }catch (ModelNotFoundException $e){
           return response()->json([
              'message' => 'System setting not found',
              'error' => $e->getMessage(), 
           ],404); 
        }catch (Exception $e){
           return response()->json([
              'message' => 'An error occured while updating system setting',
              'error' => $e->getMessage(),
           ],500);
        }
    }

    public function destroy(SystemSetting $systemSetting)
    {
        try{
          Gate::authorize('delete', $systemSetting);

          if(!$systemSetting->is_editable){
            return response()->json([
               'message' => 'This system setting cannot be deleted',
               'error' => 'Setting is marked as non-editable'
            ], 403);
          }
          $systemSetting->delete();

           return response()->json([
              'message' => 'System delete successfully',
           ]);
        }catch (AuthorizationException $e){
          return response()->json([
             'message' => 'Unauthorize to delete system settings',
             'error' => $e->getMessage(), 
          ],403);
        }catch (ModelNotFoundException $e){
          return response()->json([
             'message' => 'System setting not found',
             'error' => $e->getMessage(),
          ],404);  
        }catch (Exception $e){
            if(
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ){
                return response()->json([
                   'message' => 'Cannot delete system setting as it is currently in use',
                   'error' => 'This system setting is assigned to one or more records',
                ],409);
            }
            return response()->json([
               'message' => 'An error occured while deleting system setting',
               'error' => $e->getMessage(),
            ],500);
        }
    }
}