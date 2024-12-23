<?php

use Package\Entity\Booking;
use Package\UseCase\Booking\ShowBookingUseCase;
use Tests\Traits\Repository\BookingRepositoryInterfaceTrait;

uses(BookingRepositoryInterfaceTrait::class);

test('deve retornar a reserva', function () {
    $useCase = new ShowBookingUseCase(
        bookingRepository: $this
            ->findBookingRepositoryInterface()
            ->getMockBookingRepositoryInterface(),
    );

    $result = $useCase->handle('fulano');

    expect($result)->toBeInstanceOf(Booking::class);
});