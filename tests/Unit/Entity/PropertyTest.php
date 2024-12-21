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

test('deve criar uma instância de propriedade com todos os atributos', function () {
    expect($this->property)
        ->id->toBe('1')
        ->title->toBe('Casa de praia')
        ->description->toBe('Casa de praia com 3 quartos')
        ->maxGuests->toBe(4)
        ->basePriceByNight->toBe(130.32);
});

test('deve lançar um erro se o nome for vázio', fn() => new Property(
    id: '1',
    title: ' ',
    description: 'Casa de praia com 3 quartos',
    maxGuests: 4,
    basePriceByNight: 150.00,
))->throws('O título da propriedade não pode ser vázio');

test('deve lançar um erro se a descrição for vázio', fn() => new Property(
    id: '1',
    title: 'testing',
    description: ' ',
    maxGuests: 4,
    basePriceByNight: 150.00,
))->throws('A descrição da propriedade não pode ser vázio');

test('deve lançar um erro se o numero de hospedes for menor ou igual a zero', fn() => new Property(
    id: '1',
    title: 'testing',
    description: 'testing',
    maxGuests: 0,
    basePriceByNight: 150.00,
))->throws('O número de hospedes deve ser maior que zero');

test('deve validar o numero máximo de hospedes', function () {
    $this->property->validateMaxGuests(5);
})->throws('Número máximo de hóspedes excedido. O limite é de 4.');

test('não deve aplicar desconto para estadia menor de 7 noites', function () {
    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-05'),
    );

    $totalPrice = $this->property->calculateTotalPrice($dateRange);

    expect($totalPrice)->toBe(541.28);
});

test('deve aplicar desconto para estadia 7 noites ou mais', function () {
    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-08'),
    );

    $totalPrice = $this->property->calculateTotalPrice($dateRange);

    expect($totalPrice)->toBe(852.51); // 7 noites * 130,32 * 100 - 10%

});

test('deve verificar disponibilidade da propriedade', function () {
    $user = new User(id: '1', name: 'Fulano');

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
    );

    expect($this->property->isAvailable($dateRange))->toBeFalse()
        ->and($this->property->isAvailable($dateRange2))->toBeFalse();
});
