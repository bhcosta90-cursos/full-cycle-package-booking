<?php

namespace Tests\Traits\Repository;

use Mockery;
use Package\Entity\User;
use Package\Repository\UserRepositoryInterface;

trait UserRepositoryInterfaceTrait
{
    protected UserRepositoryInterface|Mockery\MockInterface|null $mockUserRepository = null;

    public function findUserRepositoryInterface(): self
    {
        $mockUserRepository = $this->mockUserRepositoryInterface();
        $mockUserRepository
            ->shouldReceive('findById')
            ->atMost()->once()
            ->with("fulano")
            ->andReturn($this->getEntityUser());

        $mockUserRepository
            ->shouldReceive('findById')
            ->atMost()->once()
            ->with("fake")
            ->andReturn(null);

        return $this;
    }

    protected function mockUserRepositoryInterface(): UserRepositoryInterface|Mockery\MockInterface
    {
        if ($this->mockUserRepository === null) {
            $this->mockUserRepository = Mockery::mock(UserRepositoryInterface::class);
        }

        return $this->mockUserRepository;
    }

    public function getEntityUser(): User
    {
        return new User(id: "fulano", name: 'Fulano', email: "test@example.com");
    }

    public function getMockUserRepositoryInterface(): UserRepositoryInterface|Mockery\MockInterface
    {
        return $this->mockUserRepository;
    }
}