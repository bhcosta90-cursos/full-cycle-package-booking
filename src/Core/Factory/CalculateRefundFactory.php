<?php

namespace Package\Core\Factory;

use Package\Core\Cancellation\FullRefund;
use Package\Core\Cancellation\NoRefund;
use Package\Core\Cancellation\PartialRefund;
use Package\Core\Cancellation\RefundRule;

class CalculateRefundFactory
{
    const int TOTAL_FULL_REFUND_DAYS = 7;

    public static function handle(int $days): RefundRule
    {
        return match (true) {
            $days >= self::TOTAL_FULL_REFUND_DAYS => new FullRefund(),
            $days >= 1 && $days < self::TOTAL_FULL_REFUND_DAYS => new PartialRefund(),
            default => new NoRefund(),
        };
    }
}