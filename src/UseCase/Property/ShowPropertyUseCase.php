<?php

namespace Package\UseCase\Property;

use Package\Entity\Property;
use Package\Repository\PropertyRepositoryInterface;

class ShowPropertyUseCase
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository,
    ) {}

    public function handle(string $id): ?Property
    {
        return $this->propertyRepository->findById($id);
    }
}