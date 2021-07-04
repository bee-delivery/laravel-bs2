<?php

namespace BeeDelivery\Bs2;

use BeeDelivery\Bs2\Utils\PixConnection;

class Pix
{
    protected $http;

    public function __construct()
    {
        $this->http = new PixConnection();
    }

    /*
     * Pagamento - Iniciar pagamento por chave e confirmar.
     *
     * @param array $key
     * @param int $value
     * @return array
     */
    public function payment($key, $value)
    {
        try {
            $response = $this->http->post('/pix/direto/forintegration/v1/pagamentos/chave', json_encode($key));

            if ($response['code'] == 201) {
                $params = [
                    "recebedor" =>  [
                        "ispb" =>  $response['recebedor']['ispb'],
                        "conta" =>  [
                            "banco" =>  $response['recebedor']['ispb'],
                            "bancoNome" =>  $response['recebedor']['conta']['banco'],
                            "agencia" =>  $response['recebedor']['conta']['agencia'],
                            "numero" =>  $response['recebedor']['conta']['numero'],
                            "tipo" =>  $response['recebedor']['conta']['tipo']
                        ],
                        "pessoa" =>  [
                            "documento" =>  $response['recebedor']['pessoa']['documento'],
                            "tipoDocumento" =>  $response['recebedor']['pessoa']['tipoDocumento'],
                            "nome" =>  $response['recebedor']['pessoa']['nome'],
                            "nomeFantasia" =>  $response['recebedor']['pessoa']['nomeFantasia']
                        ]
                    ],
                    "valor" =>  $value
                ];

                $response = $this->http->post('/pix/direto/forintegration/v1/pagamentos/' . $response['pagamentoId'] . '/confirmacao', json_encode($params));

                return [
                    'code' => $response->getStatusCode(),
                    'response' => json_decode($response->getBody(), true)
                ];
            }
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}
