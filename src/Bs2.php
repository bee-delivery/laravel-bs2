<?php

namespace BeeDelivery\Bs2;

use BeeDelivery\Bs2\Banking;
use BeeDelivery\Bs2\Pix;

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
