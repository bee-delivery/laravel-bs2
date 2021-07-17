<?php

namespace BeeDelivery\Bs2;

use BeeDelivery\Bs2\Utils\BankingConnection;

class Banking
{
    protected $http;

    /*
     * Cria uma nova instância de BankingConnection.
     *
     * @return void
     */
    public function __construct()
    {
        $this->http = new BankingConnection();
    }

    /*
     * Consulta o saldo do cliente Bs2.
     *
     * @return array
     */
    public function getSaldo()
    {
        $saldo = $this->http->get('/pj/forintegration/banking/v1/contascorrentes/principal/saldo');

        return $saldo;
    }
}
