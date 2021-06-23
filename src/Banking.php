<?php

namespace BeeDelivery\Bs2;

class Banking
{
    protected $http;

    public function __construct()
    {
        $this->http = new Connection();
    }
}
