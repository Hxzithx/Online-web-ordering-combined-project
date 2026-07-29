<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
   public function register(Request $request)
   {
   try{
    $validateData = $request->validate([
    'first_name' => 'required|string|max:255',
    'last_name'  => 'required|string|max:255',
    'phone'      => 'required|unique:customers,phone',
    'address'    => 'required|string|max:500',
    'email'      => 'required|email|unique:customers',
    'password'   => 'required|string|min:8|confirmed',
    ]);
    $validateData['password'] = Hash::make($validateData['password']);
    $validateData['role'] = 'user';
    $validateData['status'] = 'active';

    $customer = Customer::create($validateData);
    $token = $customer->createToken('customer_token')->plainTextToken;

    return response()->json(
      [
      'user' => $customer,  
      'token' => $token,
      ],201);

   }catch(ValidationException $e){
      return response()->json([
        'message' => 'Validation Failed',
        'errors' => $e->errors(),
      ],422);
   }catch(\Exception $e){
      return response()->json([
         'message' => 'An error occured during registration',
         'error' => $e->getMessage(),
      ],500);
   }
   
   }
   public function login(Request $request)
   {
      try{
      $credentials = $request->validate([
         'email' => 'required|email|exists:customers,email',
         'password' => 'required'
      ]);

         $customer = Customer::where('email', $credentials['email'])->first();

          if (!$customer || !Hash::check($credentials['password'], $customer->password)) {
            return response()->json([
               'message' => 'Invalid credentials'
               ], 401);
       }  
          if($customer->status !== 'active'){
              return response()->json([
                 'message' => 'Account is inactive',
              ], 401);
          }
          $token = $customer->createToken('customer_token')->plainTextToken;

            return response()->json([
                  'user' => $customer,
                  'token' => $token
            ], 200);
      }catch(ValidationException $e){
         return response()->json([
            'message' => 'Validation failed',
            'error' => $e->errors(),
         ],422);
      }catch(\Exception $e){
         return response()->json([
            'message' => 'An error occured during login',
            'error' => $e->getMessage(),
         ],500);
            
      }
   }
   public function logout(Request $request)
   {
      try{
      $request->user()->tokens()->delete();
      return response()->json(['message' => 'Logged out successfully'], 200);
      }catch(\Exception $e){
         return response()->json([
           'message' => 'An error occured during logout',
           'error' => $e->getMessage(),
         ],500);
      }
   }

   public function updateStatus(Request $request, $id)
   {
      try{
         $request->validate([
            'status' => 'required|string|in:active,inactive'
         ]);

         $customer = Customer::findOrFail($id);

         // if(!$user){
         //    return response()->json([
         //      'message' => 'user not found',
         //    ],404);
         // }

         $customer->status = $request->status;
         $customer->save();

         return response()->json([
             'message' => 'User status updated successfully',
             'user' => $customer, 
         ]);

      }catch(ValidationException $e){
         return response()->json([
             'message' => 'Validation failed',
             'error' => $e->errors(),
         ],422);
      }catch(\Exception $e){
         return response()->json([
             'message' => 'An error occured during updating status',
             'error' => $e->getMessage(),
         ],500);
      }
   }
}