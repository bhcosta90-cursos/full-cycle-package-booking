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

test('must create a property instance with all attributes', function () {
    expect($this->booking)
        ->id->toBe('1')
        ->property->title->toBe('Casa de praia')
        ->user->name->toBe('Fulano')
        ->dateRange->toBeInstanceOf(DateRange::class)
        ->guestCount
        ->toBe(2)
        ->isConfirmed()->toBeTrue();
});

test('must have the down payment to confirm the rental of this property', function () {
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
            amount: 1000,
        ),
    );

    expect($booking)
        ->isConfirmed()->toBeFalse()
        ->getTotalPayments()->toBe(1000);

    $booking->addPayment(
        new Payment(
            type: PaymentType::CheckinValue,
            method: PaymentMethod::Pix,
            amount: 5000,
        ),
    );

    expect($booking)
        ->isConfirmed()->toBeTrue()
        ->getTotalPayments()->toBe(6000);
});

test('should give the minimum entry value to confirm the appointment', function () {
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
            amount: 5212,
        ),
    );

    expect($booking)
        ->isConfirmed()->toBeTrue()
        ->getTotalPayments()->toBe(5212);
});


test('should throw the error if the number of guests is less than zero', function () {
    new Booking(
        id: '1',
        property: $this->property,
        user: $this->user,
        dateRange: $this->dateRange,
        guestCount: 0,
        daysCanceled: 7,
    );
})->throws('O número de hospedes deve ser maior que zero');


test('should throw the error if the number of guests exceeds', function () {
    new Booking(
        id: '1',
        property: $this->property,
        user: $this->user,
        dateRange: $this->dateRange,
        guestCount: $this->property->maxGuests + 1,
        daysCanceled: 7,
    );
})->throws('Número máximo de hóspedes excedido. O limite é de 4.');

test('must calculate the total discounted price', function () {
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

    expect($booking->getTotalPrice())->toBe(105559); // 130.32 * 9 * 0.9 = 1058.64
});

test('should not make an appointment when a property is not available', function () {
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
})->throws('The property is not available for the requested date.');

test('must cancel a reservation when there is less than 1 day left until entry', function () {
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
        ->getTotalPrice()->toBe(0);
});

test('must cancel a reservation with a full refund when the date is more than 7 days before arrival',
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

        $booking->cancel(new DateTime('2019-12-24'));

        expect($booking)
            ->isCanceled()->toBeTrue()
            ->getTotalPrice()->toBe(30000);
    });

test('must cancel a reservation with partial refund when the date is less than 7 days before arrival', function () {
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
            ->getTotalPrice()->toBe(15000);
    },
);

test('it cannot cancel the same reservation', function () {
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

test('must calculate the total value and entry value for the reservation', function () {
    $property = new Property(
        id: '1',
        title: 'Casa de praia',
        description: 'Casa de praia com 3 quartos',
        maxGuests: 4,
        basePriceByNight: 412.04,
        basePriceByGuests: 656.21,
        percentagePriceConfirmation: 15,
    );

    $dateRange = new DateRange(
        start: new DateTime('2020-01-05'),
        end: new DateTime('2020-01-10'),
    );

    $booking = new Booking(
        id: '1',
        property: $property,
        user: $this->user,
        dateRange: $dateRange,
        guestCount: $property->maxGuests,
        daysCanceled: 7,
    );

    expect($booking)
        ->getTotalPrice()->toBe(534125)
        ->getTotalCheckinValue()->toBe(80118);
});