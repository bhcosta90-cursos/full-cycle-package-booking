<?php

use Package\Entity\Booking;
use Package\Entity\Property;
use Package\Entity\User;
use Package\ValueObject\DateRange;

beforeEach(function () {
    $this->property = new Property(
        id: '1',
        title: 'Casa de praia',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 130.32,
        basePriceByGuests: 5.0,
    );
});

test('must create a property instance with all attributes', function () {
    expect($this->property)
        ->id->toBe('1')
        ->title->toBe('Casa de praia')
        ->description->toBe('Casa de praia com 3 quartos')
        ->maxGuests->toBe(4)
        ->basePriceByNight->toBe(130.32);
});

test('should throw an error if name is empty', fn() => new Property(
    id: '1',
    title: ' ',
    description: 'Casa de praia com 3 quartos',
    maxGuests: 4,
    basePriceByNight: 150.00,
))->throws('O título da propriedade não pode ser vázio');

test('should throw an error if the description is empty', fn() => new Property(
    id: '1',
    title: 'testing',
    description: ' ',
    maxGuests: 4,
    basePriceByNight: 150.00,
))->throws('A descrição da propriedade não pode ser vázio');

test('should throw an error if the number of guests is less than or equal to zero', fn() => new Property(
    id: '1',
    title: 'testing',
    description: 'testing',
    maxGuests: 0,
    basePriceByNight: 150.00,
))->throws('O número de hospedes deve ser maior que zero');

test('must validate the maximum number of guests', function () {
    $this->property->validateMaxGuests(5);
})->throws('Número máximo de hóspedes excedido. O limite é de 4.');

test('no discount should be applied for stays of less than 7 nights', function () {
    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-05'),
    );

    $totalPrice = $this->property->calculateTotalPrice($dateRange);

    expect($totalPrice)->toBe(54128);
});

test('discount must be applied for stays of 7 nights or more', function () {
    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-08'),
    );

    $totalPrice = $this->property->calculateTotalPrice($dateRange);

    expect($totalPrice)->toBe(85251); // 7 noites * 130,32 * 100 - 10%

});

test('must check property availability', function () {
    $user = new User(id: '1', name: 'Fulano', email: 'test@example.com');

    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-05'),
    );

    $dateRange2 = new DateRange(
        start: new DateTime('2020-01-03'),
        end: new DateTime('2020-01-07'),
    );

    new Booking(
        id: '1',
        property: $this->property,
        user: $user,
        dateRange: $dateRange,
        guestCount: 2,
        daysCanceled: 7,
    );

    expect($this->property->isAvailable($dateRange))
        ->toBeFalse()
        ->and($this->property->isAvailable($dateRange2))->toBeFalse();
});
