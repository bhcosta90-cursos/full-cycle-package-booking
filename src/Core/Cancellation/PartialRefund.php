<?php

namespace Package\Core\Cancellation;

use Package\Core\Factory\BcMathNumberFactory;

class PartialRefund implements RefundRule
{

    public function calculateRefund(float $total): float
    {
        return BcMathNumberFactory::create($total)->mul(0.5)->toFloat();
    }
}