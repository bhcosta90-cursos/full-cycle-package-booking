<?php

namespace Package\UseCase\Booking;

use DateTime;
use Package\Exception\ServiceException;
use Package\Repository\BookingRepositoryInterface;

class CancelBookingService
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository,
    ) {}

    public function handle(string $id, ?DateTime $cancelDateTime = null): void
    {
        if (!$booking = $this->bookingRepository->findById($id)) {
            throw new ServiceException('Reserva não existe');
        }

        $booking->cancel($cancelDateTime ?: new DateTime());

        $this->bookingRepository->save($booking);
    }
}