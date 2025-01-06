<?php

use Package\Entity\Booking;
use Package\UseCase\Booking\CancelBookingUseCase;
use Tests\Traits\Repository\BookingRepositoryInterfaceTrait;

uses(BookingRepositoryInterfaceTrait::class);

beforeEach(function () {
    $this->useCase = new CancelBookingUseCase(
        bookingRepository: $this
            ->findBookingRepositoryInterface()
            ->saveBookingRepositoryInterface()
            ->getMockBookingRepositoryInterface(),
    );
});


test('deve conseguir cancelar uma reserva', function () {
    $result = $this->useCase->handle("fulano");

    expect($result)->toBeInstanceOf(Booking::class);
});

test('não devo conseguir cancelar uma reserva que não existe', function () {
    $this->useCase->handle("fake");
})->throws('Reserva não existe');