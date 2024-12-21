<?php

namespace Package\Repository;

use Package\Entity\Booking;

interface BookingRepositoryInterface
{
    public function findById(string $id): ?Booking;

    public function save(Booking $booking): void;
}