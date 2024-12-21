<?php

namespace Package\Service;

use Package\Entity\Property;
use Package\Repository\PropertyRepositoryInterface;

class PropertyService
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository,
    ) {}

    public function findById(string $id): ?Property
    {
        return $this->propertyRepository->findById($id);
    }
}