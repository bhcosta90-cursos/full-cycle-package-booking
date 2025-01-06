<?php

namespace Package\UseCase\Booking;

use DateTime;
use Package\Entity\Booking;
use Package\Exception\UseCaseException;
use Package\Repository\BookingRepositoryInterface;

class CancelBookingUseCase
{
    public function __construct(
        protected BookingRepositoryInterface $bookingRepository,
    ) {}

    public function handle(string $id, ?DateTime $cancelDateTime = null): Booking
    {
        if (!$booking = $this->bookingRepository->findById($id)) {
            throw new UseCaseException('Reserva não existe');
        }

        $booking->cancel($cancelDateTime ?: new DateTime());

        $this->bookingRepository->save($booking);

        return $booking;
    }
}