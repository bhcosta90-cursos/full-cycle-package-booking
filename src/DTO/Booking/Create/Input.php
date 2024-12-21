<?php

namespace Package\DTO\Booking\Create;

use DateTime;

readonly class Input
{
    public function __construct(
        public string $propertyId,
        public string $guestId,
        public DateTime $start,
        public DateTime $end,
        public int $guest,
    ) {}
}