<?php

namespace BeeDelivery\Bs2;

use BeeDelivery\Bs2\Utils\PixConnection;

class Pix
{
    protected $http;

    /*
     * Create a new Pix instance.
     *
     * @return void
     */
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
                $data = json_decode($response->getBody(), true);

                $params = [
                    "recebedor" =>  [
                        "ispb" =>  $data['recebedor']['ispb'],
                        "conta" =>  [
                            "banco" =>  $data['recebedor']['ispb'],
                            "bancoNome" =>  $data['recebedor']['conta']['banco'],
                            "agencia" =>  $data['recebedor']['conta']['agencia'],
                            "numero" =>  $data['recebedor']['conta']['numero'],
                            "tipo" =>  $data['recebedor']['conta']['tipo']
                        ],
                        "pessoa" =>  [
                            "documento" =>  $data['recebedor']['pessoa']['documento'],
                            "tipoDocumento" =>  $data['recebedor']['pessoa']['tipoDocumento'],
                            "nome" =>  $data['recebedor']['pessoa']['nome'],
                            "nomeFantasia" =>  $data['recebedor']['pessoa']['nomeFantasia']
                        ]
                    ],
                    "valor" =>  $value
                ];

                $response = $this->http->post('/pix/direto/forintegration/v1/pagamentos/' . $data['pagamentoId'] . '/confirmacao', json_encode($params));

                if ($response->getStatusCode() == 202) {
                    return [
                        'code' => $response->getStatusCode(),
                        'response' => $data
                    ];
                }

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

    /*
     * Pagamento - Consultar por PagamentoId.
     *
     * @param int $pagamentoId
     * @return array
     */
    public function paymentDetails($pagamentoId)
    {
        try {
            $response = $this->http->get('/pix/direto/forintegration/v1/pagamentos/' . $pagamentoId);

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
     * Cobrança dinâmica - Criar.
     * @param array $params
     * @return array
     */
    public function dynamicCharge($params)
    {
        try {
            $response = $this->http->post('/pix/direto/forintegration/v1/qrcodes/dinamico', json_encode($params));

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
