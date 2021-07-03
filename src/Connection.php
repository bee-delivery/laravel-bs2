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
        session_start();

        $this->baseUrl = config('bs2.base_url');
        $this->apiKey = config('bs2.api_key');
        $this->apiSecret = config('bs2.api_secret');
        $this->username = config('bs2.username');
        $this->password = config('bs2.password');

        $this->getAccessToken();
    }

    public function get($url, $params = null)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])
            ->withToken($this->accessToken)
            ->get($this->baseUrl . $url, $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
            ];
        } catch (\Exception $e){
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    public function post($url, $params = null)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])
            ->withToken($this->accessToken)
            ->post($this->baseUrl . $url, $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
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
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])
            ->asForm()
            ->withBasicAuth($this->apiKey, $this->apiSecret)
            ->post($this->baseUrl . '/auth/oauth/v2/token', $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
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
        if (isset($_SESSION["token"])) {
            $token = $_SESSION["token"];

            $diffInSeconds = Carbon::parse($token['updated_at'])->diffInSeconds(now());

            if ($diffInSeconds <= 240) {
                $this->accessToken = $token['access_token'];

                return;
            }

            if ($diffInSeconds <= 540) {
                $params = [
                    'grant_type' => 'refresh_token',
                    'scope' => 'forintegration',
                    'refresh_token' => $token['refresh_token']
                ];

                $response = $this->auth($params);

                if ($response['code'] == 200) {
                    $this->accessToken = $response['response']['access_token'];

                    $token['access_token'] = $this->accessToken;
                    $token['token_type'] = $response['response']['token_type'];
                    $token['expires_in'] = $response['response']['expires_in'];
                    $token['refresh_token'] = $response['response']['refresh_token'];
                    $token['scope'] = $response['response']['scope'];
                    $token['created_at'] = now();
                    $token['updated_at'] = now();

                    $_SESSION["token"] = $token;

                    $this->accessToken = $token['access_token'];

                    return;
                }
            }
        }

        $params = [
            'grant_type' => 'password',
            'scope' => 'forintegration',
            'username' => $this->username,
            'password' => $this->password
        ];

        $response = $this->auth($params);

        if ($response['code'] == 200) {
            $token['access_token'] = $response['response']['access_token'];
            $token['token_type'] = $response['response']['token_type'];
            $token['expires_in'] = $response['response']['expires_in'];
            $token['refresh_token'] = $response['response']['refresh_token'];
            $token['scope'] = $response['response']['scope'];
            $token['created_at'] = now();
            $token['updated_at'] = now();

            $_SESSION["token"] = $token;

            $this->accessToken = $token['access_token'];

            return;
        }

        return $response;
    }
}
