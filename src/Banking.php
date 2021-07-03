<?php

namespace BeeDelivery\Bs2;

use BeeDelivery\Bs2\Utils\BankingConnection;

class Banking
{
    protected $http;

    public function __construct()
    {
        $this->http = new BankingConnection();
    }

    public function getSaldo()
    {
        $saldo = $this->http->get('/pj/forintegration/banking/v1/contascorrentes/principal/saldo');

        return $saldo;
    }
}
