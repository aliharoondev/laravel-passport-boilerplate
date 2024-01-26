<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token as PassportToken;

trait Token{

    private $client;
    public function __construct()
    {
        $this->client = DB::table('oauth_clients')->where('password_client', '=', 1)->first();
    }
    public function generateToken($request)
    {
        $http = new Client([ 'verify' => false ]);
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'username' => $request->email,
                'password' => $request->password,
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'scope' => '',
            ],
        ]);

        return json_decode($response->getBody()->getContents());
    }
    public function refreshToken($request)
    {
        try {
            $http = new Client(['verify' => false]);
            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => $this->client->id,
                    'client_secret' => $this->client->secret,
                    'scope' => '',
                ],
            ]);

            $token = json_decode($response->getBody()->getContents());

            $user = $this->getUserFromAccessToken($token->access_token);

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }catch (ClientException $error){
            $errorMessage = json_decode($error->getResponse()->getBody()->getContents());
            return response()->json([
                'success' => false,
                'message' => $errorMessage->error_description,
                'data' => [],
                'errors' => [
                    $errorMessage->error_description
                ]
            ], $error->getCode());
        }catch (\Exception $error){
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
