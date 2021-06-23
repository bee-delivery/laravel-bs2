<?php

namespace BeeDelivery\Bs2;

class Banking
{
    protected $http;

    public function __construct()
    {
        $this->http = new Connection();
    }

    public function getSaldo()
    {
        $saldo = $this->http->get('/pj/forintegration/banking/v1/contascorrentes/principal/saldo');

        return $saldo;
    }
}
