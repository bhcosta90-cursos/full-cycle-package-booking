<?php

namespace Package\Cancellation;

class FullRefund implements RefundRule
{

    public function calculateRefund(int $total): int
    {
        return $total;
    }
}