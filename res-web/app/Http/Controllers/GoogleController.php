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
    public function redirectToGoogle(Request $request)
    {
        $frontendUrl = $this->resolveFrontendUrl($request->query('frontend_url'));
        $state       = base64_encode(json_encode(['frontend' => $frontendUrl]));

        return Socialite::driver('google')
            ->stateless()
            ->with(['state' => $state])
            ->redirect();
    }

    /**
     * Handle callback redirect from Google's official OAuth window.
     */
    public function handleGoogleCallback(Request $request)
    {
        $frontendUrl = $this->resolveFrontendUrlFromState($request->query('state'));

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

            $userJson = urlencode(json_encode($customer->toArray()));

            return redirect("{$frontendUrl}/login?token={$token}&user={$userJson}");
        } catch (\Exception $e) {
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

    /**
     * Resolve a safe frontend URL for OAuth redirects.
     */
    private function resolveFrontendUrl(?string $requestedUrl = null): string
    {
        $defaultUrl = rtrim((string) config('app.frontend_url', config('app.url')), '/');

        if (empty($requestedUrl)) {
            return $defaultUrl;
        }

        $requestedUrl = rtrim($requestedUrl, '/');

        if ($this->isAllowedFrontendUrl($requestedUrl)) {
            return $requestedUrl;
        }

        return $defaultUrl;
    }

    /**
     * Extract the frontend URL encoded in the OAuth state parameter.
     */
    private function resolveFrontendUrlFromState(?string $state): string
    {
        $defaultUrl = rtrim((string) config('app.frontend_url', config('app.url')), '/');

        if (empty($state)) {
            return $defaultUrl;
        }

        try {
            $decoded = json_decode(base64_decode($state), true);

            if (!empty($decoded['frontend']) && $this->isAllowedFrontendUrl($decoded['frontend'])) {
                return rtrim($decoded['frontend'], '/');
            }
        } catch (\Exception $e) {
            // Fall back to configured frontend URL.
        }

        return $defaultUrl;
    }

    /**
     * Prevent open redirects by only allowing known frontend origins.
     */
    private function isAllowedFrontendUrl(string $url): bool
    {
        $url = rtrim($url, '/');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $allowedUrls = array_filter([
            config('app.frontend_url'),
            config('app.url'),
        ]);

        foreach ($allowedUrls as $allowedUrl) {
            if ($url === rtrim((string) $allowedUrl, '/')) {
                return true;
            }
        }

        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $reqHost = parse_url($url, PHP_URL_HOST);

        if ($appHost && $reqHost === $appHost) {
            return true;
        }

        if (app()->environment('local')) {
            return in_array($reqHost, ['localhost', '127.0.0.1'], true);
        }

        return false;
    }
}
