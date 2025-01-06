<?php

namespace Package\Cancellation;

interface RefundRule
{
    public function calculateRefund(int $total): int;
}