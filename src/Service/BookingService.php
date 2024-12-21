<?php

namespace Package\Service;

use DateTime;
use Package\DTO\Booking\Create\BookingCreateInput;
use Package\Entity\Booking;
use Package\Exception\ServiceException;
use Package\Factory\DateRangeFactoryInterface;
use Package\Repository\BookingRepositoryInterface;
use Package\Repository\PropertyRepositoryInterface;
use Package\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;

class BookingService
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository,
        protected UserRepositoryInterface $userRepository,
        protected BookingRepositoryInterface $bookingRepository,
        protected DateRangeFactoryInterface $dateRangeFactory,
    ) {}

    public function createBooking(BookingCreateInput $input): Booking
    {
        if (!$property = $this->propertyRepository->findById($input->propertyId)) {
            throw new ServiceException('Propriedade não existe');
        }

        if (!$user = $this->userRepository->findById($input->guestId)) {
            throw new ServiceException('Usuário não existe');
        }

        $dateRange = $this->dateRangeFactory->create(
            $input->start->format('Y-m-d'),
            $input->end->format('Y-m-d'),
        );

        $booking = new Booking(
            id: (string)Uuid::uuid4(),
            property: $property,
            user: $user,
            dateRange: $dateRange,
            guestCount: $input->guest,
        );

        $this->bookingRepository->save($booking);

        return $booking;
    }

    public function findById(string $id): ?Booking
    {
        return $this->bookingRepository->findById($id);
    }

    public function cancelBooking(string $id, ?DateTime $cancelDateTime = null): void
    {
        if (!$booking = $this->bookingRepository->findById($id)) {
            throw new ServiceException('Reserva não existe');
        }

        $booking->cancel($cancelDateTime ?: new DateTime());

        $this->bookingRepository->save($booking);
    }
}