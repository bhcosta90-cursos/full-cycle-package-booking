<?php

use Package\Core\ValueObject\DateRange;

test('deve criar uma instância de date range com a data de inicio e final', function () {
    $dateRange = new DateRange(
        start: $start = new DateTime('2020-01-01'),
        end: $end = new DateTime('2020-01-05'),
    );

    expect($dateRange)
        ->start->toBeInstanceOf(DateTime::class)
        ->start->toBe($start)
        ->end->toBeInstanceOf(DateTime::class)
        ->end->toBe($end);
});

test('deve lançar um erro se a data de termino for antes da data de inicio', fn() => new DateRange(
    start: new DateTime('2020-01-05'),
    end: new DateTime('2019-01-01'),
))->throws('A data de término deve ser posterior à data de início');

test('deve calcular o total de noites corretamente', function () {
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

test('verificar se dois intervalos de data se sobrepõe', function () {
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

test('deve lançar erro se a data de inicio for igual a data final', fn() => new DateRange(
    start: new DateTime('2020-01-05'),
    end: new DateTime('2020-01-05'),
))->throws('A data de término não pode ser igual à data de início');
