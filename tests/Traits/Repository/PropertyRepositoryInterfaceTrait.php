<?php

namespace Tests\Traits\Repository;

use Mockery;
use Package\Entity\Property;
use Package\Repository\PropertyRepositoryInterface;

trait PropertyRepositoryInterfaceTrait
{
    protected PropertyRepositoryInterface|Mockery\MockInterface|null $mockPropertyRepository = null;

    public function findPropertyRepositoryInterface(
        int $maxGuests = 5,
        float $basePriceByNight = 150,
        float $basePriceByGuests = 50,
        float $percentagePriceConfirmation = 0.0,
    ): self {
        $mockPropertyRepository = $this->mockPropertyRepositoryInterface();
        $mockPropertyRepository
            ->shouldReceive('findById')
            ->with("fulano")
            ->between(0, 1)
            ->andReturn(
                $this->getEntityProperty(
                    $maxGuests,
                    $basePriceByNight,
                    $basePriceByGuests,
                    $percentagePriceConfirmation,
                ),
            );
        $mockPropertyRepository
            ->shouldReceive('findById')
            ->between(0, 1)
            ->with("fake")
            ->andReturn(null);

        return $this;
    }

    protected function mockPropertyRepositoryInterface(): PropertyRepositoryInterface|Mockery\MockInterface
    {
        if ($this->mockPropertyRepository === null) {
            $this->mockPropertyRepository = Mockery::mock(PropertyRepositoryInterface::class);
        }

        return $this->mockPropertyRepository;
    }

    public function getEntityProperty(
        int $maxGuests = 5,
        float $basePriceByNight = 150,
        float $basePriceByGuests = 50,
        float $percentagePriceConfirmation = 0.0,
    ): Property {
        return new Property(
            id: "fulano",
            title: 'Fulano',
            description: 'Descrição do Fulano',
            maxGuests: $maxGuests,
            basePriceByNight: $basePriceByNight,
            basePriceByGuests: $basePriceByGuests,
            percentagePriceConfirmation: $percentagePriceConfirmation,
        );
    }

    public function getMockPropertyRepositoryInterface(): PropertyRepositoryInterface|Mockery\MockInterface
    {
        return $this->mockPropertyRepository;
    }
}