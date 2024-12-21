<?php

namespace Package\Service;

use Package\Entity\User;
use Package\Repository\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    public function findUserById(string $id): ?User
    {
        return $this->userRepository->findUserById($id);
    }
}