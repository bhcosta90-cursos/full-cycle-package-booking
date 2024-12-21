<?php

use Package\Service\UserService;

beforeEach(function () {
    $this->service = new UserService();
});

test('deve retornar nulo quando um id invalido for passado', function () {
    $response = $this->service->findUserById("123");

    expect($response)->toBeNull();
});

test('deve retornar usuário quando um id for valido for passado', function () {
    $response = $this->service->findUserById("123");

    expect($response)
        ->not->toBeNull()
        ->id->toBe(1)
        ->name->toBe('Fulano');
})->todo();