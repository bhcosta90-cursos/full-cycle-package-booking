<?php

use Package\Entity\Booking;
use Package\Entity\Property;
use Package\Entity\User;
use Package\Enum\PaymentMethod;
use Package\Enum\PaymentType;
use Package\ValueObject\DateRange;
use Package\ValueObject\Payment;

beforeEach(function () {
    $this->user = new User(id: '1', name: 'Fulano', email: 'test@example.com');

    $this->dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-05'),
    );

    $this->property = new Property(
        id: '1',
        title: 'Casa de praia',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 130.32,
    );

    $this->booking = new Booking(
        id: '1',
        property: $this->property,
        user: $this->user,
        dateRange: $this->dateRange,
        guestCount: 2,
        daysCanceled: 7,
    );
});

test('deve criar uma instância de propriedade com todos os atributos', function () {
    expect($this->booking)
        ->id->toBe('1')
        ->property->title->toBe('Casa de praia')
        ->user->name->toBe('Fulano')
        ->dateRange->toBeInstanceOf(DateRange::class)
        ->guestCount
        ->toBe(2)
        ->isConfirmed()->toBeTrue();
});

test('deve ter o valor de entrada para confirmar o aluguel dessa propriedade', function () {
    $property = new Property(
        id: '1',
        title: 'Casa de praia com valor de entrada',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 130.32,
        percentagePriceConfirmation: 10,
    );

    $booking = new Booking(
        id: '1',
        property: $property,
        user: $this->user,
        dateRange: $this->dateRange,
        guestCount: 2,
        daysCanceled: 7,
    );


    expect($booking)
        ->id->toBe('1')
        ->property->title->toBe('Casa de praia com valor de entrada')
        ->user->name->toBe('Fulano')
        ->dateRange->toBeInstanceOf(DateRange::class)
        ->guestCount
        ->toBe(2)
        ->isConfirmed()->toBeFalse();

    $booking->addPayment(
        new Payment(
            type: PaymentType::CheckinValue,
            method: PaymentMethod::Pix,
            amount: 10,
        ),
    );

    expect($booking)
        ->isConfirmed()->toBeFalse()
        ->getTotalPayments()->toBe(10.0);

    $booking->addPayment(
        new Payment(
            type: PaymentType::CheckinValue,
            method: PaymentMethod::Pix,
            amount: 50,
        ),
    );

    expect($booking)
        ->isConfirmed()->toBeTrue()
        ->getTotalPayments()->toBe(60.0);
});

test('estou dando o valor mínimo de entrada para poder confirmar o agendamento', function () {
    $property = new Property(
        id: '1',
        title: 'Casa de praia com valor de entrada 02',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 130.32,
        percentagePriceConfirmation: 10,
    );

    $booking = new Booking(
        id: '1',
        property: $property,
        user: $this->user,
        dateRange: $this->dateRange,
        guestCount: 2,
        daysCanceled: 7,
    );


    expect($booking)
        ->id->toBe('1')
        ->property->title->toBe('Casa de praia com valor de entrada 02')
        ->user->name->toBe('Fulano')
        ->dateRange->toBeInstanceOf(DateRange::class)
        ->guestCount
        ->toBe(2)
        ->isConfirmed()->toBeFalse();

    $booking->addPayment(
        new Payment(
            type: PaymentType::CheckinValue,
            method: PaymentMethod::Pix,
            amount: 52.12,
        ),
    );

    expect($booking)
        ->isConfirmed()->toBeTrue()
        ->getTotalPayments()->toBe(52.12);
});


test('deve lançar o erro se o número de hospedes for menor que zero', function () {
    new Booking(
        id: '1',
        property: $this->property,
        user: $this->user,
        dateRange: $this->dateRange,
        guestCount: 0,
        daysCanceled: 7,
    );
})->throws('O número de hospedes deve ser maior que zero');


test('deve lançar o erro se o número de hospedes exceder', function () {
    new Booking(
        id: '1',
        property: $this->property,
        user: $this->user,
        dateRange: $this->dateRange,
        guestCount: $this->property->maxGuests + 1,
        daysCanceled: 7,
    );
})->throws('Número máximo de hóspedes excedido. O limite é de 4.');

test('deve calcular o preço total com desconto', function () {
    $dateRange = new DateRange(
        start: new DateTime('2020-01-06'),
        end: new DateTime('2020-01-15'),
    );

    $booking = new Booking(
        id: '1',
        property: $this->property,
        user: $this->user,
        dateRange: $dateRange,
        guestCount: $this->property->maxGuests,
        daysCanceled: 7,
    );

    expect($booking->getTotalPrice())->toBe(1055.59); // 130.32 * 9 * 0.9 = 1058.64
});

test('não deve realizar o agendamento quando uma propriedade se não estiver disponível', function () {
    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-10'),
    );

    $dateRange2 = new DateRange(
        start: new DateTime('2020-01-05'),
        end: new DateTime('2020-01-15'),
    );

    new Booking(
        id: '1',
        property: $this->property,
        user: $this->user,
        dateRange: $dateRange,
        guestCount: $this->property->maxGuests,
        daysCanceled: 7,
    );

    new Booking(
        id: '2',
        property: $this->property,
        user: $this->user,
        dateRange: $dateRange2,
        guestCount: $this->property->maxGuests,
        daysCanceled: 7,
    );
})->throws('A propriedade não está disponível para a data solicitadas.');

it('deve cancelar uma reserva quando falta menos de 1 dia para a entrada', function () {
    $property = new Property(
        id: '1',
        title: 'Casa de praia',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 100,
    );

    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-04'),
    );

    $booking = new Booking(
        id: '1',
        property: $property,
        user: $this->user,
        dateRange: $dateRange,
        guestCount: $property->maxGuests,
        daysCanceled: 7,
    );

    $booking->cancel(new DateTime('2020-01-01'));

    expect($booking)
        ->isCanceled()->toBeTrue()
        ->getTotalPrice()->toBe(0.0);
});

it('deve cancelar uma reserva com o reembolso total quando a data for superior a 7 dias antes da entrada', function () {
    $property = new Property(
        id: '1',
        title: 'Casa de praia',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 100,
    );

    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-04'),
    );

    $booking = new Booking(
        id: '1',
        property: $property,
        user: $this->user,
        dateRange: $dateRange,
        guestCount: $property->maxGuests,
        daysCanceled: 7,
    );

    $booking->cancel(new DateTime('2019-12-24'));

    expect($booking)
        ->isCanceled()->toBeTrue()
        ->getTotalPrice()->toBe(300.0);
});

it(
    'deve cancelar uma reserva com o reembolso parcial quando a data for inferior a 7 dias antes da entrada',
    function () {
        $property = new Property(
            id: '1',
            title: 'Casa de praia',
            description: 'Casa de praia com 3 quartos',
            maxGuests: 4,
            basePriceByNight: 100,
        );

        $dateRange = new DateRange(
            start: new DateTime('2020-01-01'),
            end: new DateTime('2020-01-04'),
        );

        $booking = new Booking(
            id: '1',
            property: $property,
            user: $this->user,
            dateRange: $dateRange,
            guestCount: $property->maxGuests,
            daysCanceled: 7,
        );

        $booking->cancel(new DateTime('2019-12-31'));

        expect($booking)
            ->isCanceled()->toBeTrue()
            ->getTotalPrice()->toBe(150.0);
    },
);

it('não pode cancelar a mesma reserva', function () {
    $property = new Property(
        id: '1',
        title: 'Casa de praia',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 100,
    );

    $dateRange = new DateRange(
        start: new DateTime('2020-01-01'),
        end: new DateTime('2020-01-04'),
    );

    $booking = new Booking(
        id: '1',
        property: $property,
        user: $this->user,
        dateRange: $dateRange,
        guestCount: $property->maxGuests,
        daysCanceled: 7,
    );

    $booking->cancel(new DateTime('2019-12-31'));
    $booking->cancel(new DateTime('2019-12-31'));
})->throws('A reserva já foi cancelada.');
