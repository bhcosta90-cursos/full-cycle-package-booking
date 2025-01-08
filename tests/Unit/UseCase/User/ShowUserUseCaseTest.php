<?php

use Package\Entity\User;
use Package\UseCase\User\ShowUserUseCase;
use Tests\Traits\Repository\UserRepositoryInterfaceTrait;

uses(UserRepositoryInterfaceTrait::class);

test('must return the user', function () {
    $useCase = new ShowUserUseCase(
        userRepository: $this
            ->findUserRepositoryInterface()
            ->getMockUserRepositoryInterface(),
    );

    $result = $useCase->handle('fulano');

    expect($result)->toBeInstanceOf(User::class);
});