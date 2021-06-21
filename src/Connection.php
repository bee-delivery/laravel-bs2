<?php

namespace BeeDelivery\Bs2;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Connection
{
    use Helpers;

    protected $baseUrl;
    protected $apiKey;
    protected $apiSecret;
    protected $username;
    protected $password;
    protected $accessToken;

    public function __construct()
    {
        $this->baseUrl = config('bs2.base_url');
        $this->apiKey = config('bs2.api_key');
        $this->apiSecret = config('bs2.api_secret');
        $this->username = config('bs2.username');
        $this->password = config('bs2.password');

        $this->getAccessToken();
    }

    public function get($url, $params)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])
            ->withToken($this->accessToken)
            ->get($this->baseUrl . $url, $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\Exception $e){
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function post($url, $params)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])
            ->withToken($this->accessToken)
            ->post($this->baseUrl . $url, $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\Exception $e){
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function auth($params)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])
            ->withBasicAuth($this->apiKey, $this->apiSecret)
            ->post($this->baseUrl . '/auth/oauth/v2/token', $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\Exception $e){
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /**
     * Pega o token de acesso no banco de dados.
     *
     * @return void|json
     */
    public function getAccessToken()
    {
        $token = DB::table('bs2_oauth_access_tokens')
            ->select('access_token', 'expires_in', 'refresh_token', 'created_at', 'updated_at')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->first();

        if ($token) {
            $diffInSeconds = Carbon::parse($token->updated_at)->diffInSeconds(now());

            if ($diffInSeconds <= 240) {
                $this->accessToken = $token->access_token;
            }

            if ($diffInSeconds <= 540) {
                $params = [
                    'grant_type' => 'refresh_token',
                    'scope' => 'apibanking',
                    'refresh_token' => $token->refresh_token
                ];

                $response = $this->auth($params);

                if ($response['code'] == 200) {
                    $access_token = $response['response']['access_token'];

                    $token->access_token = $access_token;
                    $token->token_type = $response['response']['token_type'];
                    $token->expires_in = $response['response']['expires_in'];
                    $token->refresh_token = $response['response']['refresh_token'];
                    $token->scope = $response['response']['scope'];

                    $token->save();

                    $this->accessToken = $access_token;
                }
            }

            $token->delete();
        }

        $params = [
            'grant_type' => 'password',
            'scope' => 'apibanking',
            'username' => $this->username,
            'password' => $this->password
        ];

        $response = $this->auth($params);

        if ($response['code'] == 200) {
            $access_token = $response['response']['access_token'];

            DB::table('bs2_oauth_access_tokens')->insert([
                'access_token' => $access_token;
                'token_type' => $response['response']['token_type'];
                'expires_in' => $response['response']['expires_in'];
                'refresh_token' => $response['response']['refresh_token'];
                'scope' => $response['response']['scope'];
                'created_at' => now();
                'updated_at' => now();
            ]);

            $this->accessToken = $access_token;
        }

        return $response;
    }
}
