<?php

namespace Package\Cancellation;

use Package\Factory\BcMathNumberFactory;

class PartialRefund implements RefundRule
{

    public function calculateRefund(float $total): float
    {
        return BcMathNumberFactory::create($total)->mul(0.5)->toFloat();
    }
}