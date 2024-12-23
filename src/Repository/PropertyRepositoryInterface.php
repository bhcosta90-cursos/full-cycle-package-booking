<?php

namespace Package\Repository;

use Package\Entity\Property;
use Package\ValueObject\DateRange;

interface PropertyRepositoryInterface
{
    public function findById(string $id, DateRange $dateRange): ?Property;
}