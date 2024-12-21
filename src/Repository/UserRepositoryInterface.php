<?php

namespace Package\Repository;

use Package\Entity\User;

interface UserRepositoryInterface
{
    public function findUserById(string $id): ?User;
}