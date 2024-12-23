<?php

namespace Package\UseCase\User;

use Package\Entity\User;
use Package\Repository\UserRepositoryInterface;

class ShowUserUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {}

    public function handle(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}