<?php

namespace Package\Cancellation;

interface RefundRule
{
    public function calculateRefund(float $total): float;
}