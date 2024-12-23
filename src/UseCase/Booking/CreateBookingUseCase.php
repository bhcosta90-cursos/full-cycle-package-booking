<?php

namespace Package\UseCase\Booking;

use Package\DTO\Booking\BookingCreateInput;
use Package\Entity\Booking;
use Package\Exception\ServiceException;
use Package\Factory\DateRangeFactoryInterface;
use Package\Repository\BookingRepositoryInterface;
use Package\Repository\PropertyRepositoryInterface;
use Package\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;

class CreateBookingUseCase
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository,
        protected UserRepositoryInterface $userRepository,
        protected BookingRepositoryInterface $bookingRepository,
        protected DateRangeFactoryInterface $dateRangeFactory,
    ) {}

    public function handle(BookingCreateInput $input): Booking
    {
        $dateRange = $this->dateRangeFactory->create(
            $input->start->format('Y-m-d'),
            $input->end->format('Y-m-d'),
        );

        if (!$property = $this->propertyRepository->findById($input->propertyId, $dateRange)) {
            throw new ServiceException('Propriedade não existe');
        }

        if (!$user = $this->userRepository->findById($input->guestId)) {
            throw new ServiceException('Usuário não existe');
        }

        $booking = new Booking(
            id: (string) Uuid::uuid4(),
            property: $property,
            user: $user,
            dateRange: $dateRange,
            guestCount: $input->guest,
            daysCanceled: $input->daysCancelled,
        );

        $this->bookingRepository->save($booking);

        return $booking;
    }
}