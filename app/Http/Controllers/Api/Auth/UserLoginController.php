<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{

    public function login(UserLoginRequest $request)
    {
        if (filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $request->email_or_phone,
                'password' => $request->password
            ];
        } elseif (preg_match('/^[0-9\s+\-()]+$/', $request->email_or_phone)) {
            $credentials = [
                'phone' => $request->email_or_phone,
                'password' => $request->password
            ];
        }

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $this->isEmailVerified($user);
                $token = $user->createToken($user->email)->accessToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'accessToken' => $token,
                    'user' => $user
                ], 200);
            }

            return response()->json([
                'message' => 'Invalid credentials.',
                'data' => []
            ], 400);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Your email is not verified.',
                'data' => []
            ], 409);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function isEmailVerified($user)
    {
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            throw new AuthorizationException('Your email is not verified.');
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        return response()->json([
            'message' => 'Logged out successfully',
            'data' => []
        ]);
    }

    public function refreshToken()
    {

    }

    /**
     * Exchange the authorization code for an access token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function exchangeCodeForToken(Request $request):JsonResponse
    {
        $request->validate([
            'code' => 'required',
            'state' => 'required',
        ]);

        // Validate state to prevent CSRF attacks
        if ($request->session()->pull('state') !== $request->input('state')) {
            return response()->json(['error' => 'Invalid state parameter'], 400);
        }

        // Validate code verifier
        $codeVerifier = $request->session()->pull('code_verifier');
        if (!$codeVerifier) {
            return response()->json(['error' => 'Code verifier not found in session'], 400);
        }

        // Exchange authorization code for access token
        $response = (new Client)->post(config('services.oauth.token_url'), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.oauth.client_id'),
                'client_secret' => config('services.oauth.client_secret'),
                'redirect_uri' => config('services.oauth.redirect_uri'),
                'code' => $request->input('code'),
                'code_verifier' => $codeVerifier,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function redirectToAuthorization(Request $request)
    {
        $state = Str::random(40);
        $codeVerifier = Str::random(128);
        $codeChallenge = base64_encode(hash('sha256', $codeVerifier, true));

        // Store the code verifier and state in session for later validation
        $request->session()->put('code_verifier', $codeVerifier);
        $request->session()->put('state', $state);

        // Redirect the user to the OAuth authorization endpoint with PKCE parameters
        $redirectUrl = config('services.oauth.authorization_url') . '?' . http_build_query([
                'client_id' => config('services.oauth.client_id'),
                'redirect_uri' => config('services.oauth.redirect_uri'),
                'response_type' => 'code',
                'scope' => '', // Add your desired scopes
                'state' => $state,
                'code_challenge_method' => 'S256',
                'code_challenge' => $codeChallenge,
            ]);

        return redirect()->away($redirectUrl);
    }
}
