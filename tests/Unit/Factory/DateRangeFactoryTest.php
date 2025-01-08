<?php

use Package\Factory\DateRangeFactory;
use Package\Factory\DateRangeFactoryInterface;
use Package\ValueObject\DateRange;

test('creating a new data range', function(){
    $dateRange = ($objDateRange = new DateRangeFactory())->create('2021-01-01', '2021-01-31');

    expect($objDateRange)->toBeInstanceOf(DateRangeFactoryInterface::class)
        ->and($dateRange)->toBeInstanceOf(DateRange::class);
});
