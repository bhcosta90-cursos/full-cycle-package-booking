<?php

namespace Tests\Traits\Repository;

use DateTime;
use Mockery;
use Package\Entity\Booking;
use Package\Entity\Property;
use Package\Entity\User;
use Package\Repository\BookingRepositoryInterface;
use Package\ValueObject\DateRange;

trait BookingRepositoryInterfaceTrait
{
    use PropertyRepositoryInterfaceTrait;
    use UserRepositoryInterfaceTrait;

    protected BookingRepositoryInterface|Mockery\MockInterface|null $mockBookingRepository = null;

    public function findBookingRepositoryInterface(
        ?DateRange $dateRange = null,
        ?Property $property = null,
        ?User $user = null,
    ): self {
        if ($dateRange === null) {
            $dateRange = new DateRange(
                start: new DateTime('2020-01-01'),
                end: new DateTime('2020-01-05'),
            );
        }

        $mockBookingRepository = $this->mockBookingRepositoryInterface();
        $mockBookingRepository
            ->shouldReceive('findById')
            ->with("fulano")
            ->between(0, 1)
            ->andReturn(
                new Booking(
                    id: "fulano",
                    property: $property ?: $this->getEntityProperty(),
                    user: $user ?: $this->getEntityUser(),
                    dateRange: $dateRange,
                    guestCount: 2,
                ),
            );
        $mockBookingRepository
            ->shouldReceive('findById')
            ->with("fake")
            ->between(0, 1)
            ->andReturn(null);

        return $this;
    }

    protected function mockBookingRepositoryInterface(): BookingRepositoryInterface|Mockery\MockInterface
    {
        if ($this->mockBookingRepository === null) {
            $this->mockBookingRepository = Mockery::mock(BookingRepositoryInterface::class);
        }

        return $this->mockBookingRepository;
    }

    public function saveBookingRepositoryInterface(
        ?Property $property = null,
        ?User $user = null,
    ): self {
        $mockBookingRepository = $this->mockBookingRepositoryInterface();
        $mockBookingRepository
            ->shouldReceive('save')
            ->between(0, 1)
            ->withArgs(function (Booking $booking) {
                return $booking->property->title === 'Fulano'
                    && $booking->user->name === 'Fulano';
            })
            ->andReturn(
                new Booking(
                    id: "fulano",
                    property: $property ?: $this->getEntityProperty(),
                    user: $user ?: $this->getEntityUser(),
                    dateRange: new DateRange(
                        start: new DateTime('2020-01-01'),
                        end: new DateTime('2020-01-05'),
                    ),
                    guestCount: 2,
                ),
            );

        return $this;
    }

    public function getMockBookingRepositoryInterface(): BookingRepositoryInterface|Mockery\MockInterface
    {
        return $this->mockBookingRepository;
    }
}