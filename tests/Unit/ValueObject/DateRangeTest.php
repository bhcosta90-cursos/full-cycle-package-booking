<?php

use Package\ValueObject\DateRange;

test('you must create an instance of date range with the start and end date', function () {
    $dateRange = new DateRange(
        start: $start = new DateTime('2020-01-01'),
        end: $end = new DateTime('2020-01-05'),
    );

    expect($dateRange)
        ->start->toBeInstanceOf(DateTime::class)
        ->end->toBeInstanceOf(DateTime::class);
});

test('should throw an error if the end date is before the start date', fn() => new DateRange(
    start: new DateTime('2020-01-05'),
    end: new DateTime('2019-01-01'),
))->throws('The end date must be later than the start date');

test('must calculate the total nights correctly', function () {
    $dateRange = new DateRange(
        start: new DateTime('2020-01-05'),
        end: new DateTime('2020-01-10'),
    );

    expect($dateRange->getTotalNights())->toBe(5);

    $dateRange = new DateRange(
        start: new DateTime('2020-01-10'),
        end: new DateTime('2020-01-25'),
    );

    expect($dateRange->getTotalNights())->toBe(15);
});

test('check if two date ranges overlap', function () {
    $dateRange01 = new DateRange(
        start: new DateTime('2020-01-05'),
        end: new DateTime('2020-01-10'),
    );

    $dateRange02 = new DateRange(
        start: new DateTime('2020-01-07'),
        end: new DateTime('2020-01-12'),
    );

    $overlaps = $dateRange01->overlaps($dateRange02);

    expect($overlaps)->toBeTrue();
});

test('should throw an error if the start date is equal to the end date', fn() => new DateRange(
    start: new DateTime('2020-01-05'),
    end: new DateTime('2020-01-05'),
))->throws('The end date cannot be the same as the start date');
