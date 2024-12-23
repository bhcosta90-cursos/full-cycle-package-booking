<?php

use Package\DTO\Booking\BookingCreateInput;
use Package\Entity\Booking;
use Package\Factory\DateRangeFactoryInterface;
use Package\UseCase\Booking\CreateBookingUseCase;
use Package\ValueObject\DateRange;
use Tests\Traits\Repository\BookingRepositoryInterfaceTrait;

uses(BookingRepositoryInterfaceTrait::class);

beforeEach(function () {
    ($this->mockDateRange = Mockery::mock(DateRangeFactoryInterface::class))
        ->shouldReceive('create')
        ->with('2021-10-01', '2021-10-10')
        ->atMost()->once()
        ->andReturn(
            new DateRange(
                start: new DateTime('2021-10-01'),
                end: new DateTime('2021-10-10'),
            ),
        );
});

it('deve criar uma reserva com sucesso', function () {
    $useCase = new CreateBookingUseCase(
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
        dateRangeFactory: $this->mockDateRange,
    );

    $input = new BookingCreateInput(
        propertyId: 'fulano',
        guestId: 'fulano',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
        daysCancelled: 7,
    );

    $result = $useCase->handle($input);

    expect($result)
        ->toBeInstanceOf(Booking::class)
        ->isConfirmed()->toBeTrue()
        ->getTotalPrice()->toBe(1620.0);
});

it('deve lançar um erro se acaso a propriedade não existe', function () {
    $useCase = new CreateBookingUseCase(
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
        dateRangeFactory: $this->mockDateRange,
    );

    $input = new BookingCreateInput(
        propertyId: 'fake',
        guestId: 'fulano',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
        daysCancelled: 7,
    );

    $result = $useCase->handle($input);

    expect($result)
        ->toBeInstanceOf(Booking::class)
        ->isConfirmed()->toBeTrue()
        ->getTotalPrice()->toBe(1620.0);
})->throws('Propriedade não existe');

it('deve lançar um erro se acaso o usuário não existe', function () {
    $useCase = new CreateBookingUseCase(
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
        dateRangeFactory: $this->mockDateRange,
    );

    $input = new BookingCreateInput(
        propertyId: 'fulano',
        guestId: 'fake',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
        daysCancelled: 7,
    );

    $result = $useCase->handle($input);

    expect($result)
        ->toBeInstanceOf(Booking::class)
        ->isConfirmed()->toBeTrue()
        ->getTotalPrice()->toBe(1620.0);
})->throws('Usuário não existe');

it('deve lançar erro ao tentar criar um reserva com o período já reservado', function () {
    $property = $this->getEntityPropertyBlank();
    $property->shouldReceive('addBooking');
    $property->shouldReceive('validateMaxGuests');
    $property->shouldReceive('isAvailable')->andReturnFalse();
    $property->shouldReceive('calculateTotalPrice')->andReturn(1620.0);

    $useCase = new CreateBookingUseCase(
        propertyRepository: $this
            ->findPropertyRepositoryInterface(property: $property)
            ->getMockPropertyRepositoryInterface(),
        userRepository: $this
            ->findUserRepositoryInterface()
            ->getMockUserRepositoryInterface(),
        bookingRepository: $this
            ->findBookingRepositoryInterface()
            ->saveBookingRepositoryInterface()
            ->getMockBookingRepositoryInterface(),
        dateRangeFactory: $this->mockDateRange,
    );

    $input = new BookingCreateInput(
        propertyId: 'fulano',
        guestId: 'fulano',
        start: new DateTime('2021-10-01'),
        end: new DateTime('2021-10-10'),
        guest: 1,
        daysCancelled: 7,
    );

    $useCase->handle($input);
})->throws('A propriedade não está disponível para a data solicitadas.');