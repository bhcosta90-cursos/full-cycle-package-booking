<?php

namespace Package\Factory;

use DateTime;
use Package\ValueObject\DateRange;

class DateRangeFactory implements DateRangeFactoryInterface
{
    public function create(string $start, string $end): DateRange
    {
        return new DateRange(
            new DateTime($start),
            new DateTime($end),
        );
    }
}