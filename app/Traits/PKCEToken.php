<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token as PassportToken;

trait PKCEToken {
    private $client;
    public function __construct()
    {
        $this->client = DB::table('oauth_clients')->where('password_client', '=', 1)->first();
    }
    public function generateAuthorizationUrl()
    {
        // Generate a random code verifier (recommended length: 43-128 characters)
        $codeVerifier = bin2hex(random_bytes(32));

        // Create a code challenge from the code verifier using SHA-256 hashing algorithm and base64 URL encoding
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        // Return the code verifier and authorization URL to the client
        return response()->json([
            'code_verifier' => $codeVerifier,
            'authorization_url' => config('services.oauth.authorization_url') . '?' . http_build_query([
                    'client_id' => config('services.oauth.client_id'),
                    'redirect_uri' => config('services.oauth.redirect_uri'),
                    'response_type' => 'code',
                    'scope' => '', // Add your desired scopes
                    'code_challenge_method' => 'S256', // Use SHA-256 hashing algorithm for code challenge
                    'code_challenge' => $codeChallenge, // Include the generated code challenge
                ])
        ]);
    }
    public function exchangeToken(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'code_verifier' => 'required',
        ]);

        // Exchange authorization code for access token
        $client = new Client();

        try {
            $response = $client->post(config('services.oauth.token_url'), [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('services.oauth.client_id'),
                    'client_secret' => config('services.oauth.client_secret'),
                    'redirect_uri' => config('services.oauth.redirect_uri'),
                    'code' => $request->code,
                    'code_verifier' => $request->code_verifier,
                ],
            ]);

            // Decode the response body
            $data = json_decode($response->getBody(), true);

            // Return the access token and any additional data as needed
            return response()->json($data);
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., token exchange failure)
            return response()->json(['error' => 'Token exchange failed'], 500);
        }
    }
    public function refreshToken($request)
    {
        try {
            $http = new Client(['verify' => false]);
            $response = $http->post(config('services.oauth.token_url'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => config('services.oauth.client_id'),
                    'client_secret' => config('services.oauth.client_secret'),
                    'scope' => '',
                ],
            ]);

            $token = json_decode($response->getBody()->getContents());

            $user = $this->getUserFromAccessToken($token->access_token);

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }
        catch (ClientException $error){
            $errorMessage = json_decode($error->getResponse()->getBody()->getContents());
            return response()->json([
                'success' => false,
                'message' => $errorMessage->error_description,
                'data' => [],
                'errors' => [
                    $errorMessage->error_description
                ]
            ], $error->getCode());
        }
        catch (\Exception $error){
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'data' => [],
                'errors' => [
                    $error->getMessage()
                ]
            ], $error->getCode());
        }
    }
    public function getUserFromAccessToken(string $access_token)
    {
        $token_parts = explode('.', $access_token);
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $token_id = $token_header_array['jti'];

        return PassportToken::find($token_id)->user;
    }
}
