<?php

namespace Tests\Traits\Repository;

use Mockery;
use Package\Entity\Property;
use Package\Repository\PropertyRepositoryInterface;

trait PropertyRepositoryInterfaceTrait
{
    protected PropertyRepositoryInterface|Mockery\MockInterface|null $mockPropertyRepository = null;

    public function findPropertyRepositoryInterface(): self
    {
        $mockPropertyRepository = $this->mockPropertyRepositoryInterface();
        $mockPropertyRepository
            ->shouldReceive('findById')
            ->with("fulano")
            ->andReturn(
                new Property(
                    id: "fulano",
                    title: 'Fulano',
                    description: 'Descrição do Fulano',
                    maxGuests: 5,
                    basePriceByNight: 150,
                    basePriceByGuests: 50,
                    percentagePriceConfirmation: 0.0,
                ),
            );
        $mockPropertyRepository
            ->shouldReceive('findById')
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

    public function getMockPropertyRepositoryInterface(): PropertyRepositoryInterface|Mockery\MockInterface
    {
        return $this->mockPropertyRepository;
    }
}