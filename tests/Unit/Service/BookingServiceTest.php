<?php

use Package\DTO\Booking\Create\BookingCreateInput;
use Package\Entity\Booking;
use Package\Service\BookingService;
use Tests\Traits\Repository\BookingRepositoryInterfaceTrait;

uses(BookingRepositoryInterfaceTrait::class);

it('deve criar uma reserva com sucesso', function () {
    $service = new BookingService(
        propertyRepository: $this
            ->findPropertyRepositoryInterface()
            ->getMockPropertyRepositoryInterface(),
        userRepository: $this
            ->findUserRepositoryInterface()
            ->getMockUserRepositoryInterface(),
        bookingRepository: $this
            ->findBookingRepositoryInterface()
            ->getMockBookingRepositoryInterface(),
    );

    $input = new BookingCreateInput(
        propertyId: '1',
        guestId: '1',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 2,
    );

    $result = $service->createBooking($input);

    expect($result)
        ->toBeInstanceOf(Booking::class)
        ->isConfirmed()->toBeTrue()
        ->getTotalPrice()->toBe(500);
})->skip();