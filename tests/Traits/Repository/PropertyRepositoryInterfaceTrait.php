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
        ?Property $property = null,
    ): self {
        $mockPropertyRepository = $this->mockPropertyRepositoryInterface();
        $mockPropertyRepository
            ->shouldReceive('findById')
            ->with("fulano")
            ->atMost()->once()
            ->andReturn(
                $property ?: $this->getEntityProperty(
                    $maxGuests,
                    $basePriceByNight,
                    $basePriceByGuests,
                    $percentagePriceConfirmation,
                ),
            );
        $mockPropertyRepository
            ->shouldReceive('findById')
            ->atMost()->once()
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
        $mock = $this->getEntityPropertyBlank(
            $maxGuests,
            $basePriceByNight,
            $basePriceByGuests,
            $percentagePriceConfirmation,
        );

        $mock->shouldReceive('validateMaxGuests');
        $mock->shouldReceive('addBooking');
        $mock->shouldReceive('calculateTotalPrice')->andReturn(1620.0);
        $mock->shouldReceive('isAvailable')->andReturnTrue();

        return $mock;
    }

    public function getEntityPropertyBlank(
        int $maxGuests = 5,
        float $basePriceByNight = 150,
        float $basePriceByGuests = 50,
        float $percentagePriceConfirmation = 0.0,
    ): Property|Mockery\MockInterface {
        return Mockery::mock(Property::class, [
            "fulano",
            'Fulano',
            'Descrição do Fulano',
            $maxGuests,
            $basePriceByNight,
            $basePriceByGuests,
            $percentagePriceConfirmation,
        ]);
    }

    public function getMockPropertyRepositoryInterface(): PropertyRepositoryInterface|Mockery\MockInterface
    {
        return $this->mockPropertyRepository;
    }
}