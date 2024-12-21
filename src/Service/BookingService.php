<?php

namespace Package\Service;

use Package\Entity\Booking;
use Package\Repository\BookingRepositoryInterface;
use Package\Repository\PropertyRepositoryInterface;
use Package\Repository\UserRepositoryInterface;

class BookingService
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository,
        protected UserRepositoryInterface $userRepository,
        protected BookingRepositoryInterface $bookingRepository,
    ) {}

    public function findById(string $id): ?Booking
    {
        return $this->bookingRepository->findById($id);
    }
}