<?php

namespace Package\Repository;

use Package\Entity\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
}