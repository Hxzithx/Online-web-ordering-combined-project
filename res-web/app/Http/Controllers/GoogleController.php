<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect customer to Google's official OAuth authorization window.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle callback redirect from Google's official OAuth window.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $email    = $googleUser->getEmail();
            $googleId = $googleUser->getId();
            $name     = $googleUser->getName() ?? 'Google User';
            $avatar   = $googleUser->getAvatar();

            $nameParts = explode(' ', trim($name), 2);
            $firstName = $nameParts[0] ?? 'Google';
            $lastName  = $nameParts[1] ?? 'User';

            // Find existing user or create a new Customer
            $customer = Customer::where('email', $email)
                        ->orWhere('google_id', $googleId)
                        ->first();

            if (!$customer) {
                $customer = Customer::create([
                    'google_id'  => $googleId,
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'phone'      => null,
                    'address'    => null,
                    'role'       => 'user',
                    'password'   => Hash::make(Str::random(24)),
                    'status'     => 'active',
                    'avatar'     => $avatar,
                ]);
            } else {
                $customer->update([
                    'google_id' => $customer->google_id ?? $googleId,
                    'avatar'    => $customer->avatar ?? $avatar,
                ]);
            }

            // Revoke old tokens & create new token
            $customer->tokens()->delete();
            $token = $customer->createToken('google_token')->plainTextToken;

            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            $userJson    = urlencode(json_encode($customer));

            return redirect("{$frontendUrl}/login?token={$token}&user={$userJson}");
        } catch (\Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return redirect("{$frontendUrl}/login?error=" . urlencode($e->getMessage()));
        }
    }

    /**
     * API endpoint to handle direct JSON POST payloads from Google GIS client.
     */
    public function handleGoogleLogin(Request $request)
    {
        try {
            $request->validate([
                'google_id' => 'required|string',
                'name'      => 'nullable|string',
                'email'     => 'required|email',
                'avatar'    => 'nullable|string',
            ]);

            $name      = !empty($request->name) ? trim($request->name) : 'Google User';
            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0] ?? 'Google';
            $lastName  = $nameParts[1] ?? 'User';

            $customer = Customer::where('email', $request->email)
                        ->orWhere('google_id', $request->google_id)
                        ->first();

            if (!$customer) {
                $customer = Customer::create([
                    'google_id'  => $request->google_id,
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $request->email,
                    'phone'      => null,
                    'address'    => null,
                    'role'       => 'user',
                    'password'   => Hash::make(Str::random(24)),
                    'status'     => 'active',
                    'avatar'     => $request->avatar,
                ]);
            } else {
                $customer->update([
                    'google_id' => $customer->google_id ?? $request->google_id,
                    'avatar'    => $customer->avatar ?? $request->avatar,
                ]);
            }

            $customer->tokens()->delete();
            $token = $customer->createToken('google_token')->plainTextToken;

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
