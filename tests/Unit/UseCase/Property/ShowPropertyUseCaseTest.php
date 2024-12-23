<?php

use Package\Entity\Property;
use Package\UseCase\Property\ShowPropertyUseCase;
use Tests\Traits\Repository\PropertyRepositoryInterfaceTrait;

uses(PropertyRepositoryInterfaceTrait::class);

test('deve retornar a propriedade', function () {
    $useCase = new ShowPropertyUseCase(
        propertyRepository: $this
            ->findPropertyRepositoryInterface()
            ->getMockPropertyRepositoryInterface(),
    );

    $result = $useCase->handle('fulano');

    expect($result)->toBeInstanceOf(Property::class);
});