<?php

namespace Package\Entity;

use Package\Enum\PaymentMethod;
use Package\Enum\PaymentType;

class Payment
{
    public function __construct(
        protected(set) PaymentType $type,
        protected(set) PaymentMethod $method,
        protected(set) float $amount,
    )
    {
    }
}