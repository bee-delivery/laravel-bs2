<?php

namespace BeeDelivery\Bs2\Utils;

use Illuminate\Support\Facades\Http;

class Connection
{
    /*
     * Realiza uma solicitação get padrão utilizando
     * Bearer Authentication.
     *
     * @param string $url
     * @param array|null $params
     * @return array
     */
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
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Realiza uma solicitação post padrão utilizando
     * Bearer Authentication.
     *
     * @param string $url
     * @param array|null $params
     * @return array
     */
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
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Realiza uma solicitação put padrão utilizando
     * Bearer Authentication.
     *
     * @param string $url
     * @param array|null $params
     * @return array
     */
    public function put($url, $params = null)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])
            ->withToken($this->accessToken)
            ->put($this->baseUrl . $url, $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
            ];
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Realiza uma solicitação delete padrão utilizando
     * Bearer Authentication.
     *
     * @param string $url
     * @param array|null $params
     * @return array
     */
    public function delete($url, $params = null)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])
            ->withToken($this->accessToken)
            ->delete($this->baseUrl . $url, $params);

            return [
                'code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody(), true)
            ];
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Realiza uma solicitação post utilizando Basic Authentication
     * para gerar um token de acesso.
     *
     * @param array $params
     * @return array
     */
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
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}
