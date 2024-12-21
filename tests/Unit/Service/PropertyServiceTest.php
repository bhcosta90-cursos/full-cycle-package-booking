<?php

use Package\Service\PropertyService;
use Tests\Traits\Repository\PropertyRepositoryInterfaceTrait;

uses(PropertyRepositoryInterfaceTrait::class);

beforeEach(function () {
    $this->service = new PropertyService(
        propertyRepository: $this
            ->findPropertyRepositoryInterface()
            ->getMockPropertyRepositoryInterface(),
    );
});

test('deve retornar nulo quando um id invalido for passado', function () {
    $response = $this->service->findById("fake");

    expect($response)->toBeNull();
});

test('deve retornar usuário quando um id for valido for passado', function () {
    $response = $this->service->findById("fulano");

    expect($response)
        ->not->toBeNull()
        ->id->toBe("fulano")
        ->title->toBe('Fulano');
});
