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
    | As duas primeiras serão enviadas no Header da requisição, 
    | como um Basic Auth.
    | As duas restantes serão enviadas no Body da requisição.
    */
    'api_key' => env('BS2_API_KEY'),
    'api_secret' => env('BS2_API_SECRET'),

    'username' => env('BS2_USERNAME'),
    'password' => env('BS2_PASSWORD'),

];
