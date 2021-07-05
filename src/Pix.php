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
            $response = $this->http->post('/pix/direto/forintegration/v1/pagamentos/chave', $key);

            if ($response['code'] == 201) {
                $data = $response['response'];

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

                $response = $this->http->post('/pix/direto/forintegration/v1/pagamentos/' . $data['pagamentoId'] . '/confirmacao', $params);

                if ($response['code'] == 202) {
                    return [
                        'code' => $response['code'],
                        'response' => $data
                    ];
                }

                return $response;
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
    public function paymentDetailsByPagamentoId($pagamentoId)
    {
        try {
            $response = $this->http->get('/pix/direto/forintegration/v1/pagamentos/' . $pagamentoId);

            return $response;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Cobrança dinâmica - Criar.
     *
     * @param array $params
     * @return array
     */
    public function dynamicCharge($params)
    {
        try {
            $response = $this->http->post('/pix/direto/forintegration/v1/qrcodes/dinamico', $params);

            return $response;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Cobrança - Consultar.
     *
     * @param array $params
     * @return array
     */
    public function chargeDetails($params)
    {
        try {
            $response = $this->http->get('/pix/direto/forintegration/v1/cob', $params);

            return $response;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Cobrança - Consultar por TxId.
     *
     * @param int $txId
     * @return array
     */
    public function chargeDetailsByTxId($txId)
    {
        try {
            $response = $this->http->get('/pix/direto/forintegration/v1/cob/' . $txId);

            return $response;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Recebimento - Consultar.
     *
     * @param array $params
     * @return array
     */
    public function receiptDetails($params)
    {
        try {
            $response = $this->http->get('/pix/direto/forintegration/v1/recebimentos', $params);

            return $response;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }

    /*
     * Recebimento - Consultar por RecebimentoId.
     *
     * @param int $recebimentoId
     * @return array
     */
    public function receiptDetailsByRecebimentoId($recebimentoId)
    {
        try {
            $response = $this->http->get('/pix/direto/forintegration/v1/recebimentos/' . $recebimentoId);

            return $response;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'response' => $e->getMessage()
            ];
        }
    }
}
