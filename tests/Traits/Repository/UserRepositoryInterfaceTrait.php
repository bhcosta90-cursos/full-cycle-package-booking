<?php

namespace Tests\Traits\Repository;

use Mockery;
use Package\Entity\User;
use Package\Repository\UserRepositoryInterface;

trait UserRepositoryInterfaceTrait
{
    public function findUserRepositoryInterface(): UserRepositoryInterface
    {
        $mockUserRepository = Mockery::mock(UserRepositoryInterface::class);
        $mockUserRepository->shouldReceive('findUserById')
            ->with("fulano")
            ->andReturn(new User(id: "fulano", name: 'Fulano'));
        $mockUserRepository->shouldReceive('findUserById')
            ->with("fake")
            ->andReturn(null);

        return $mockUserRepository;
    }
}