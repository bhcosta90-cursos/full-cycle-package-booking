<?php

namespace Package\Cancellation;

class FullRefund implements RefundRule
{

    public function calculateRefund(float $total): float
    {
        return $total;
    }
}