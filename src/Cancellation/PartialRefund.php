<?php

namespace Package\Cancellation;

use Package\Factory\BcMathNumberFactory;

class PartialRefund implements RefundRule
{

    public function calculateRefund(int $total): int
    {
        return (int) BcMathNumberFactory::create($total)->mul(0.5)->getValue();
    }
}