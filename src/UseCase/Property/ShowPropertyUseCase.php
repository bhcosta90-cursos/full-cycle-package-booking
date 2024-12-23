<?php

namespace Package\UseCase\Property;

use DateTime;
use Package\Entity\Property;
use Package\Factory\DateRangeFactoryInterface;
use Package\Repository\PropertyRepositoryInterface;

class ShowPropertyUseCase
{
    public function __construct(
        protected PropertyRepositoryInterface $propertyRepository,
        protected DateRangeFactoryInterface $dateRangeFactory,
    ) {}

    public function handle(string $id): ?Property
    {
        return $this->propertyRepository->findById($id, $this->dateRangeFactory->create(
            start: new DateTime()->format('Y-m-d'),
            end: new DateTime()->format('Y-m-d'),
        ));
    }
}