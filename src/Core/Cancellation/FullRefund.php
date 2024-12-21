<?php

namespace Package\Core\Cancellation;

class FullRefund implements RefundRule
{

    public function calculateRefund(float $total): float
    {
        return $total;
    }
}