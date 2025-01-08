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


test('should be able to cancel a reservation', function () {
    $result = $this->useCase->handle("fulano");

    expect($result)->toBeInstanceOf(Booking::class);
});

test("I shouldn't be able to cancel a reservation that doesn't exist", function () {
    $this->useCase->handle("fake");
})->throws('Reserva não existe');