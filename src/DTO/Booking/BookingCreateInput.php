<?php

namespace Package\DTO\Booking;

use DateTime;

readonly class BookingCreateInput
{
    public function __construct(
        public string $propertyId,
        public string $guestId,
        public DateTime $start,
        public DateTime $end,
        public int $guest,
        public int $daysCancelled,
    ) {}
}