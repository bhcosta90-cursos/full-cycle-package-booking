<?php

use Package\Entity\User;

test('must create an instance of date range with the start and end date', function () {
    $user = new User(
        id: '1',
        name: 'test',
        email: 'test@example.com',
    );

    expect($user)
        ->id->toBe('1')
        ->name->toBe('test');
});

test('should throw error if name is empty', fn() => new User(
    id: '1',
    name: ' ',
    email: 'test@example.com',
))->throws('O nome do usuário não pode ser vázio');

test('should throw error if id is empty', fn() => new User(
    id: '',
    name: 'testing',
    email: 'test@example.com',
))->throws('O nome do usuário não pode ser vázio');

test('should throw error if email is empty', fn() => new User(
    id: '1',
    name: 'testing',
    email: ' ',
))->throws('O e-mail do usuário não pode ser vázio');