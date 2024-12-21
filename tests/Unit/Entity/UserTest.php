<?php

use Package\Entity\User;

test('deve criar uma instância de date range com a data de inicio e final', function () {
    $user = new User(
        id: '1',
        name: 'test',
    );

    expect($user)
        ->id->toBe('1')
        ->name->toBe('test');
});

test('deve lançar erro se o nome estiver vázio', fn() => new User(
    id: '1',
    name: ' ',
))->throws('O nome do usuário não pode ser vázio');

test('deve lançar erro se o id estiver vázio', fn() => new User(
    id: '',
    name: 'testing',
))->throws('O nome do usuário não pode ser vázio');