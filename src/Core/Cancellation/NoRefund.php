<?php

namespace Package\Core\Cancellation;

class NoRefund implements RefundRule
{

    public function calculateRefund(float $total): float
    {
        return 0.0;
    }
}