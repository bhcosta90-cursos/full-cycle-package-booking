<?php

namespace Package\Factory;

use Package\Cancellation\FullRefund;
use Package\Cancellation\NoRefund;
use Package\Cancellation\PartialRefund;
use Package\Cancellation\RefundRule;

class CalculateRefundFactory
{
    public static function handle(int $totalDaysCanceled, int $days): RefundRule
    {
        return match (true) {
            $days >= $totalDaysCanceled => new FullRefund(),
            $days >= 1 && $days < $totalDaysCanceled => new PartialRefund(),
            default => new NoRefund(),
        };
    }
}