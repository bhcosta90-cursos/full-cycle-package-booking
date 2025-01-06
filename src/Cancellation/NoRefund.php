<?php

namespace Package\Cancellation;

class NoRefund implements RefundRule
{

    public function calculateRefund(int $total): int
    {
        return 0;
    }
}