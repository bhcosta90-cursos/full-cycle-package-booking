<?php

namespace Package\Repository;

use Package\Entity\Property;

interface PropertyRepositoryInterface
{
    public function findById(string $id): ?Property;
}