<?php

namespace Package\Core\Cancellation;

interface RefundRule
{
    public function calculateRefund(float $total): float;
}