<?php

namespace BeeDelivery\Bs2;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Connection
{
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

    public function getAccessToken()
    {
        $token = session('token');

        if ($token) {
            $diffInSeconds = Carbon::parse($token['updated_at'])->diffInSeconds(now());

            if ($diffInSeconds <= 240) {
                $this->accessToken = $token['accessToken'];
                return;
            }

            if ($diffInSeconds <= 540) {
                $params = [
                    'grant_type' => 'refresh_token',
                    'scope' => 'apibanking',
                    'refresh_token' => $token['refreshToken']
                ];

                $response = $this->auth($params);

                if ($response['code'] == 200) {
                    $accessToken = $response['response']['access_token'];

                    $token['access_token'] = $accessToken;
                    $token['token_type'] = $response['response']['token_type'];
                    $token['expires_in'] = $response['response']['expires_in'];
                    $token['refresh_token'] = $response['response']['refresh_token'];
                    $token['scope'] = $response['response']['scope'];
                    $token['created_at'] = now();
                    $token['updated_at'] = now();

                    session(['token' => $token]);

                    $this->accessToken = $accessToken;

                    return;
                }
            }
        }

        $params = [
            'grant_type' => 'password',
            'scope' => 'apibanking',
            'username' => $this->username,
            'password' => $this->password
        ];

        $response = $this->auth($params);

        if ($response['code'] == 200) {
            $accessToken = $response['response']['access_token'];

            $token['access_token'] = $accessToken;
            $token['token_type'] = $response['response']['token_type'];
            $token['expires_in'] = $response['response']['expires_in'];
            $token['refresh_token'] = $response['response']['refresh_token'];
            $token['scope'] = $response['response']['scope'];
            $token['created_at'] = now();
            $token['updated_at'] = now();

            session(['token' => $token]);

            $this->accessToken = $accessToken;

            return;
        }

        return $response;
    }
}
