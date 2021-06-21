<?php

namespace BeeDelivery\Bs2;

class Bs2
{
    public function banking()
    {
        return new Banking();
    }

    public function pix()
    {
        return new Pix();
    }
}
