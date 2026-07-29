<?php

namespace App\Http\Controllers;

use App\Models\ExtraService;
use App\Models\User;
use Exception;
use App\Traits\SyncsData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;


class ExtraServiceController extends Controller implements HasMiddleware
{
    use SyncsData;
    
    protected $model = ExtraService::class;
    public static function middleware()
    {
        return [
           new Middleware('auth:sanctum')
        ];
    }

    public function index()
    {
        try{
           Gate::authorize('viewAny', ExtraService::class);

           $extraService = ExtraService::orderBy('created_at', 'desc')->get();

           return response()->json([
              'message' => 'Extra services retrieved successfully',
              'extra_services' => $extraService, 
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
                'message' => 'Unauthorized to view extra services',
                'error' => $e->getMessage()
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving extra services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try{
           Gate::authorize('create', ExtraService::class);

           $fields = $request->validate([
              'serviceName' => 'required|string|max:255',
              'description' => 'sometimes|nullable|string',
              'default_price' => 'required|numeric|min:0',
              'cost_price' => 'sometimes|nullable|numeric|min:0',
              'is_tax_included' => 'sometimes|boolean',
              'is_service_charge_included' => 'sometimes|boolean',
              'applicable_order_types_id' => 'required|string',
              'is_active' => 'sometimes|boolean',
              'measure_unit' => 'sometimes|nullable|string|max:255',
           ]);

           $extraService = ExtraService::create($fields);

           return response()->json([
              'message' => 'Extra service created successfully',
              'Extra_service' => $extraService,
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to create extra service',
              'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (Exception $e) {
            return response()->json([
               'message' => 'An error occurred while creating the extra service',
               'error' => $e->getMessage()
            ],500);
        }
    }

    public function show(ExtraService $extraService)
    {
        try{
           Gate::authorize('view', $extraService);

           return response()->json([
              'message' => 'Extra service retrieved successfully',
              'extra_service' => $extraService, 
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view this extra service',
              'error' => $e->getMessage()
            ],403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Extra service not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while retrieving the extra service',
              'error' => $e->getMessage()
            ],500);
        }
    }

    public function update(Request $request, ExtraService $extraService)
    {
        try{
           Gate::authorize('update', $extraService);

           $fields = $request->validate([
              'serviceName' => 'sometimes|required|string|max:255',
              'description' => 'sometimes|nullable|string',
              'default_price' => 'sometimes|required|numeric|min:0',
              'cost_price' => 'sometimes|nullable|numeric|min:0',
              'is_tax_included' => 'sometimes|boolean',
              'is_service_charge_included' => 'sometimes|boolean',
              'applicable_order_types_id' => 'sometimes|required|string',
              'is_active' => 'sometimes|boolean',
              'measure_unit' => 'sometimes|nullable|string|max:255',
           ]);

           $extraService->update($fields);

           return response()->json([
              'message' => 'Extra service updated successfully',
              'extra_service' => $extraService->fresh(),  
           ],201);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to update this extra service',
              'error' => $e->getMessage()
            ],403);
        }catch (ValidationException $e) {
           return response()->json([
              'message' => 'Validation failed',
              'errors' => $e->errors()
            ],422);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Extra service not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while updating the extra service',
              'error' => $e->getMessage()
            ],500);
        }
    }

    public function destroy(ExtraService $extraService)
    {
        try{
           Gate::authorize('delete', $extraService);

           $extraService->delete();

           return response()->json([
              'messge' => 'Extra service deleted successfully',
           ],200);
        }catch (AuthorizationException $e){
           return response()->json([
                'message' => 'Unauthorized to delete this extra service',
                'error' => $e->getMessage()
            ], 403);
        }catch (ModelNotFoundException $e) {
           return response()->json([
              'message' => 'Extra service not found',
              'error' => $e->getMessage()
            ],404);
        }catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                   'message' => 'Cannot delete extra service as it is currently in use',
                   'error' => 'This extra service has associated records'
                ],409);
            }

            return response()->json([
               'message' => 'An error occurred while deleting the extra service',
               'error' => $e->getMessage()
            ],500);
        }
    }

    public function toggleActive(ExtraService $extraService)
    {
        try{
           Gate::authorize('update', $extraService);

           $extraService->update([
            'is_active' => !$extraService->is_active
           ]);

           return response()->json([
              'message' => 'Extra service status updated successfully',
              'extra_service' => $extraService->fresh()
            ],200);
        }catch (AuthorizationException $e) {
           return response()->json([
             'message' => 'Unauthorized to update this extra service',
             'error' => $e->getMessage()
            ],403);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while updating the extra service status',
              'error' => $e->getMessage()
            ],500);
        } 
    }

    public function getActive()
    {
        try{
           Gate::authorize('show', ExtraService::class);

           $extraService = ExtraService::where('is_active', true)->orderBy('serviceName')->get();

           return response()->json([
              'message' => 'Active services retrieved successfully',
              'extra_services' => $extraService,
           ]);
        }catch (AuthorizationException $e){
           return response()->json([
              'message' => 'Unauthorized to view extra services',
              'error' => $e->getMessage()
            ],403);
        }catch (Exception $e) {
           return response()->json([
              'message' => 'An error occurred while retrieving active extra services',
              'error' => $e->getMessage()
            ],500);
        }
    }
}
