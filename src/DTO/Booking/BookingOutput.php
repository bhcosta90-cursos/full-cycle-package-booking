<?php

namespace Package\DTO\Booking;

use DateTime;

readonly class BookingOutput
{
    public function __construct(
        public string $bookingId,
        public string $propertyId,
        public string $guestId,
        public DateTime $start,
        public DateTime $end,
        public int $guest,
    ) {}
}