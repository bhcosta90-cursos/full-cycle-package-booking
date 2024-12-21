<?php

namespace Package\Core\Factory;

use Package\Core\Cancellation\FullRefund;
use Package\Core\Cancellation\NoRefund;
use Package\Core\Cancellation\PartialRefund;
use Package\Core\Cancellation\RefundRule;

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