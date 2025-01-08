<?php

use Package\Entity\Property;
use Package\Factory\DateRangeFactoryInterface;
use Package\UseCase\Property\ShowPropertyUseCase;
use Package\ValueObject\DateRange;
use Tests\Traits\Repository\PropertyRepositoryInterfaceTrait;

uses(PropertyRepositoryInterfaceTrait::class);

test('should return the property', function () {
    $dateRangeFactoryMock = Mockery::mock(DateRangeFactoryInterface::class);
    $dateRangeFactoryMock
        ->shouldReceive('create')
        ->andReturn(Mockery::mock(DateRange::class, [new DateTime('2020-01-01'), new DateTime('2020-01-02')]));

    $useCase = new ShowPropertyUseCase(
        propertyRepository: $this
            ->findPropertyRepositoryInterface()
            ->getMockPropertyRepositoryInterface(),
        dateRangeFactory: $dateRangeFactoryMock,
    );

    $result = $useCase->handle('fulano');

    expect($result)->toBeInstanceOf(Property::class);
});