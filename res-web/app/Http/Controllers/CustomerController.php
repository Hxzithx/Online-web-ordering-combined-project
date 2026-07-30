<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\SyncsData;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class CustomerController extends Controller implements HasMiddleware
{
    use SyncsData;
    
        protected $model = Customer::class;

        public static function middleware()
        {
           return [
              new Middleware('auth:sanctum')
           ];
        }
    
        public function index()
        {   
            try{
              Gate::authorize('viewAny', Customer::class);

              $customers = Customer::all();

              return response()->json([
                 'message' => 'Customers retrieved successfully',
                 'Customers' => $customers,
              ],200);
            }catch (AuthorizationException $e){
              return response()->json([
                  'message' => 'Unauthorized to view customers',
                  'error' => $e->getMessage(),
              ],403);
            }catch (Exception $e){
                return response()->json([
                  'message' => 'An error occured while retrieving customers',
                  'error' => $e->getMessage(),
                ],500);
            }
        }

        public function store(Request $request)
        {
            try{            
            Gate::authorize('create', Customer::class);

            $fields = $request->validate([
               'first_name'  => 'required|string|max:255',
               'last_name'   => 'required|string|max:255',
               'email'       => 'nullable|string|max:255|unique:customers,email',
               'phone'       => 'nullable|string|max:10',
               'address'     => 'nullable|string|max:255',
               'city'        => 'nullable|string|max:100',
               'state'       => 'nullable|string|max:100',
               'postal_code' => 'nullable|string|max:20',
               'country'     => 'nullable|string|max:50',
               'avatar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
               'password' => Hash::make(Str::random(16)),
            ]);

            $customer = Customer::create($fields);

            return response()->json([
               'message' => 'Customer created successfully',
               'customer' => $customer,
            ],201);
            }catch (AuthorizationException $e){
              return response()->json([
                'message' => 'Unauthorized to create customer',
                'error' => $e->getMessage(),
              ],403);
            }catch (ValidationException $e){
                return response()->json([
                   'message' => 'Validation failed',
                   'error' => $e->errors()
                ],422);
            }catch (Exception $e){
                return response()->json([
                   'message' => 'An error occured while creating customer',
                   'error' => $e->getMessage(),
                ],500);
            }
        }

        public function show(Customer $customer)
        {
           try{

           Gate::authorize('view', $customer);

             return response()->json([
               'message' => 'Customer retrieved succefully',
               'customers' => $customer,
             ], 200);

           }catch (AuthorizationException $e){
              return response()->json([
               'message' => 'Unauthorize to view customer',
               'error' => $e->getMessage(),
              ],403);
           }catch (ModelNotFoundException $e){
              return response()->json([
                'message' => 'Customer not found',
                'error' => $e->getMessage(),
              ],404);
           }catch (Exception $e){
              return response()->json([
                'message' => 'An error ocurred while retrieving the customer',
                'error' => $e->getMessage() 
               ],500);
           }
        }

        public function update(Request $request)
        {
           try{
 
              $customer = Customer::where('email', $request->user()->email)->first();

              if (!$customer) {
               return response()->json([
                  'message' => 'Customer not found', 
               ],404);
              }

              $fields = $request->validate([
               'first_name'  => 'sometimes|required|string|max:255',
               'last_name'   => 'sometimes|required|string|max:255',
               'phone'       => 'sometimes|nullable|string|max:10',
               'address'     => 'sometimes|nullable|string|max:255',
               'city'        => 'sometimes|nullable|string|max:100',
               'state'       => 'sometimes|nullable|string|max:100',
               'postal_code' => 'sometimes|nullable|string|max:20',
               'country'     => 'sometimes|nullable|string|max:50',
               'avatar'      => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
              ]);

              if ($request->hasFile('avatar')) {
                 $path = $request->file('avatar')->store('avatars', 'public');
                 $appUrl = env('APP_URL') ?: 'http://127.0.0.1:8001';
                 $baseUrl = rtrim($appUrl, '/');
                 $fields['avatar'] = $baseUrl . '/storage/' . $path;
              } else {
                 unset($fields['avatar']);
              }

              $customer->update($fields);

              return response()->json([
                 'message' => 'Successfully updated customer',
                 'customer' => $customer->fresh(),
              ],200);
           }catch (AuthorizationException $e){
              return response()->json([
                 'message' => 'Unauthorize to update this customer',
                 'error' => $e->getMessage(),
              ],403);
           }catch (ValidationException $e){
              return response()->json([
                 'message' => 'Validation failed',
                 'error' => $e->errors(),
            ],422);
           }catch (ModelNotFoundException $e){
              return response()->json([
                 'message' => 'Customer not found',
                 'error' => $e->getMessage(),
             ],404);
           }catch (Exception $e){
              return response()->json([
                 'message' => 'An error occured while updating customer',
                 'error' => $e->getMessage(),
              ],500);
           }
        }
        
        public function destroy( Customer $customer)
        {
           try{
             Gate::authorize('delete', $customer);

             $customer->delete();

             return response()->json([
               'message' => 'Customer deleted successfully',
             ],200);
           }catch (AuthorizationException $e){
             return response()->json([
               'message' => 'Unauthorize to delete customer',
               'error' => $e->getMessage(),
             ],403);
           }catch (ModelNotFoundException $e){
            return response()->json([
              'message' => 'Customer not found',
              'error' => $e->getMessage(),
            ],404);
           }catch (Exception $e){
              if(
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
              ){
                return response()->json([
                   'message' => 'Can not delete customer as it is currently in use',
                   'error' => 'This customer is assigned to one or more records',
                ],409);
              }
              return response()->json([
                'message' => 'An error occured while deleting customer',
                'error' => $e->getMessage(),
              ],500);
           }
        }

        public function profile(Request $request)
        {
          try{
             $customer = Customer::find($request->user()->id);

             if(!$customer) {
                return response()->json([
                   'message' => 'Customer not found',
                ],404);
             }

             return response()->json([
                'message' => 'Profile retrieved successfully',
                'customer' => $customer,
             ],200);
          }catch (Exception $e){
             return response()->json([
                 'message' => 'An error occured while retrieving customer',
                 'error' => $e->getMessage(),
             ],500);
          }
        }
}

