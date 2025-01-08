<?php

use Package\Entity\Booking;
use Package\UseCase\Booking\ShowBookingUseCase;
use Tests\Traits\Repository\BookingRepositoryInterfaceTrait;

uses(BookingRepositoryInterfaceTrait::class);

test('must return the reservation', function () {
    $useCase = new ShowBookingUseCase(
        bookingRepository: $this
            ->findBookingRepositoryInterface()
            ->getMockBookingRepositoryInterface(),
    );

    $result = $useCase->handle('fulano');

    expect($result)->toBeInstanceOf(Booking::class);
});