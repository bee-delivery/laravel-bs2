<?php

return [

    /*
    |-------------------------------------------------------------
    | BASE URL
    |-------------------------------------------------------------
    | URL base, a partir da qual será montada a URL da requisição. 
    | Podendo ser os endpoints de homologação ou produção.
    */
    'base_url' => env('BS2_BASE_URL'),

    /*
    |-------------------------------------------------------------
    | Authentication
    |-------------------------------------------------------------
    | Variáveis utilizadas para geração do token de acesso.
    | As quatro primeiras são enviadas no Header da requisição,
    | como um Basic Auth.
    | As duas restantes são enviadas no Body da requisição.
    */
    'api_banking_key' => env('BS2_BANKING_API_KEY'),
    'api_banking_secret' => env('BS2_BANKING_API_SECRET'),

    'api_pix_key' => env('BS2_PIX_API_KEY'),
    'api_pix_secret' => env('BS2_PIX_API_SECRET'),

    'username' => env('BS2_USERNAME'),
    'password' => env('BS2_PASSWORD'),

];
