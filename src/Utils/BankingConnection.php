<?php

namespace BeeDelivery\Bs2\Utils;

use Carbon\Carbon;

class BankingConnection extends Connection
{
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
