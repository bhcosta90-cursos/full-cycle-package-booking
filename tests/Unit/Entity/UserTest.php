<?php

use Package\Entity\User;

test('deve criar uma instância de date range com a data de inicio e final', function () {
    $user = new User(
        id: '1',
        name: 'test',
        email: 'test@example.com',
    );

    expect($user)
        ->id->toBe('1')
        ->name->toBe('test');
});

test('deve lançar erro se o nome estiver vázio', fn() => new User(
    id: '1',
    name: ' ',
    email: 'test@example.com',
))->throws('O nome do usuário não pode ser vázio');

test('deve lançar erro se o id estiver vázio', fn() => new User(
    id: '',
    name: 'testing',
    email: 'test@example.com',
))->throws('O nome do usuário não pode ser vázio');

test('deve lançar erro se o e-mail estiver vázio', fn() => new User(
    id: '1',
    name: 'testing',
    email: ' ',
))->throws('O e-mail do usuário não pode ser vázio');