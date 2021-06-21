<?php

class Banking
{
    use Helpers;

    protected $http;

    public function __construct()
    {
        $this->http = new Connection();
    }
}
