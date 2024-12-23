<?php

namespace Package\Repository;

use DateTime;
use Package\Entity\Property;

interface PropertyRepositoryInterface
{
    public function findById(string $id, DateTime $bookingsDateTime): ?Property;
}