<?php

namespace Package\ValueObject;

use DateTime;
use InvalidArgumentException;

class DateRange
{
    public function __construct(
        private(set) DateTime $start,
        private(set) DateTime $end,
    ) {
        $this->start = new DateTime($start->format('Y-m-d') . ' 00:00:00');
        $this->end = new DateTime($end->format('Y-m-d') . '14:00:59');

        $this->validate($start, $end);
    }

    private function validate(DateTime $start, DateTime $end): void
    {
        if ($end->format('Ymd') === $start->format('Ymd')) {
            throw new InvalidArgumentException('The end date cannot be the same as the start date');
        }

        if ($end <= $start) {
            throw new InvalidArgumentException('The end date must be later than the start date');
        }
    }

    public function getTotalNights(): int
    {
        return $this->start->diff($this->end)->days;
    }

    public function overlaps(self $date): bool
    {
        return $this->start < $date->end && $date->start < $this->end;
    }
}