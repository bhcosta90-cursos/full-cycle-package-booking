<?php

use Package\UseCase\Booking\CancelBookingService;
use Tests\Traits\Repository\BookingRepositoryInterfaceTrait;

uses(BookingRepositoryInterfaceTrait::class);

beforeEach(function () {
    $this->useCase = new CancelBookingService(
        bookingRepository: $this
            ->findBookingRepositoryInterface()
            ->saveBookingRepositoryInterface()
            ->getMockBookingRepositoryInterface(),
    );
});


test('deve conseguir cancelar uma reserva', function () {
    $this->useCase->handle("fulano");
});

test('não devo conseguir cancelar uma reserva que não existe', function () {
    $this->useCase->handle("fake");
})->throws('Reserva não existe');