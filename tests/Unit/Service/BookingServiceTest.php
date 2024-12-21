<?php

use Package\DTO\Booking\Create\BookingCreateInput;
use Package\Entity\Booking;
use Package\Factory\DateRangeFactoryInterface;
use Package\Service\BookingService;
use Package\ValueObject\DateRange;
use Tests\Traits\Repository\BookingRepositoryInterfaceTrait;

uses(BookingRepositoryInterfaceTrait::class);

beforeEach(function () {
    ($mockDateRange = Mockery::mock(DateRangeFactoryInterface::class))
        ->shouldReceive('create')
        ->with('2021-10-01', '2021-10-10')
        ->between(0, 1)
        ->andReturn(
            new DateRange(
                start: new DateTime('2021-10-01'),
                end: new DateTime('2021-10-10'),
            ),
        );

    $this->service = new BookingService(
        propertyRepository: $this
            ->findPropertyRepositoryInterface()
            ->getMockPropertyRepositoryInterface(),
        userRepository: $this
            ->findUserRepositoryInterface()
            ->getMockUserRepositoryInterface(),
        bookingRepository: $this
            ->findBookingRepositoryInterface()
            ->saveBookingRepositoryInterface()
            ->getMockBookingRepositoryInterface(),
        dateRangeFactory: $mockDateRange,
    );
});

it('deve criar uma reserva com sucesso', function () {
    $input = new BookingCreateInput(
        propertyId: 'fulano',
        guestId: 'fulano',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
    );

    $result = $this->service->createBooking($input);

    expect($result)
        ->toBeInstanceOf(Booking::class)
        ->isConfirmed()->toBeTrue()
        ->getTotalPrice()->toBe(1620.0);
});

it('deve lançar um erro se acaso a propriedade não existe', function () {
    $input = new BookingCreateInput(
        propertyId: 'fake',
        guestId: 'fulano',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
    );

    $result = $this->service->createBooking($input);

    expect($result)
        ->toBeInstanceOf(Booking::class)
        ->isConfirmed()->toBeTrue()
        ->getTotalPrice()->toBe(1620.0);
})->throws('Propriedade não existe');

it('deve lançar um erro se acaso o usuário não existe', function () {
    $input = new BookingCreateInput(
        propertyId: 'fulano',
        guestId: 'fake',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
    );

    $result = $this->service->createBooking($input);

    expect($result)
        ->toBeInstanceOf(Booking::class)
        ->isConfirmed()->toBeTrue()
        ->getTotalPrice()->toBe(1620.0);
})->throws('Usuário não existe');

it('deve lançar erro ao tentar criar um reserva com o período já reservado', function () {
    $input = new BookingCreateInput(
        propertyId: 'fulano',
        guestId: 'fake',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
    );

    $this->service->createBooking($input);
})->todo('Implementar a validação de período reservado');

it('deve conseguir cancelar uma reserva', function () {
    $this->service->cancelBooking("fulano");
});

it('não devo conseguir cancelar uma reserva que não existe', function () {
    $this->service->cancelBooking("fake");
})->throws('Reserva não existe');