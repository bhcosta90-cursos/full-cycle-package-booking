<?php

namespace Package\Service;

use Package\Entity\User;
use Package\Repository\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {}

    public function findById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}