<?php

use Package\Service\UserService;
use Tests\Traits\Repository\UserRepositoryInterfaceTrait;

uses(UserRepositoryInterfaceTrait::class);

beforeEach(function () {
    $this->service = new UserService(
        userRepository: $this->findUserRepositoryInterface(),
    );
});

test('deve retornar nulo quando um id invalido for passado', function () {
    $response = $this->service->findUserById("fake");

    expect($response)->toBeNull();
});

test('deve retornar usuário quando um id for valido for passado', function () {
    $response = $this->service->findUserById("fulano");

    expect($response)
        ->not->toBeNull()
        ->id->toBe("fulano")
        ->name->toBe('Fulano');
});