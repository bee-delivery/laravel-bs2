<?php

namespace BeeDelivery\Bs2\Utils;

use Carbon\Carbon;

class PixConnection extends Connection
{
    protected $baseUrl;
    protected $apiKey;
    protected $apiSecret;
    protected $username;
    protected $password;
    protected $accessToken;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->baseUrl = config('bs2.base_url');
        $this->apiKey = config('bs2.api_pix_key');
        $this->apiSecret = config('bs2.api_pix_secret');
        $this->username = config('bs2.username');
        $this->password = config('bs2.password');

        $this->getAccessToken();
    }

    public function getAccessToken()
    {
        if (isset($_SESSION["pixToken"])) {
            $token = $_SESSION["pixToken"];

            $diffInSeconds = Carbon::parse($token['updated_at'])->diffInSeconds(now());

            if ($diffInSeconds <= 240) {
                $this->accessToken = $token['access_token'];

                return;
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

            $_SESSION["pixToken"] = $token;

            $this->accessToken = $token['access_token'];

            return;
        }

        return $response;
    }
}
