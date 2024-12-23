<?php

namespace Package\UseCase\Booking;

use Package\Entity\Booking;
use Package\Repository\BookingRepositoryInterface;

class ShowBookingUseCase
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository,
    ) {}

    public function handle(string $id): ?Booking
    {
        return $this->bookingRepository->findById($id);
    }
}