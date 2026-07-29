<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
   public function handleGoogleLogin(Request $request)
{
    try {
        $request->validate([
            'google_id' => 'required|string',
            'name'      => 'nullable|string',
            'email'     => 'required|email',
            'avatar'    => 'nullable|string',
        ]);

        // Split name into first and last safely
        $name      = !empty($request->name) ? trim($request->name) : 'Google User';
        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0] ?? 'Google';
        $lastName  = $nameParts[1] ?? 'User';

        // Find existing user or create new one
        $customer = Customer::where('email', $request->email)
                    ->orWhere('google_id', $request->google_id)
                    ->first();

        if (!$customer) {
            // New user - create account
            $customer = Customer::create([
                'google_id'  => $request->google_id,
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $request->email,
                'phone'      => null,
                'address'    => 'N/A',
                'role'       => 'user',
                'password'   => Hash::make(Str::random(24)),
                'status'     => 'active',
                'avatar'     => $request->avatar,
            ]);
        } else {
            // Existing user - update google_id and avatar
            $customer->update([
                'google_id' => $request->google_id,
                'avatar'    => $request->avatar,
            ]);
        }

        // Revoke previous tokens
        $customer->tokens()->delete();

        // Generate new token
        $token = $customer->createToken('google-token')->plainTextToken;

        return response()->json([
            'message' => 'Google login successful',
            'token'   => $token,
            'user'    => $customer,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Google login failed',
            'error'   => $e->getMessage(),
        ], 500);
    }
}
}
