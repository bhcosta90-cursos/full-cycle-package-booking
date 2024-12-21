<?php

namespace Package\Factory;

use Package\ValueObject\DateRange;

interface DateRangeFactoryInterface
{
    public function create(string $start, string $end): DateRange;
}