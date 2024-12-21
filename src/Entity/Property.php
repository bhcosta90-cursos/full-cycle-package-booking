<?php

namespace Package\Entity;

use Package\Exception\EntityExpetion;
use Package\Factory\BcMathNumberFactory;
use Package\ValueObject\DateRange;

class Property
{
    const int TOTAL_NIGHTS_DISCOUNT_7 = 10;

    protected(set) array $bookings = [];

    public function __construct(
        readonly protected(set) string $id,
        readonly protected(set) string $title,
        readonly protected(set) string $description,
        readonly protected(set) int $maxGuests,
        readonly protected(set) float $basePriceByNight,
        readonly protected(set) float $basePriceByGuests = 0,
        readonly protected(set) float $percentagePriceConfirmation = 0,
    )
    {
        $this->validate();
    }

    protected function validate(): void
    {
        if (empty(trim($this->title))) {
            throw new EntityExpetion('O título da propriedade não pode ser vázio');
        }

        if (empty(trim($this->description))) {
            throw new EntityExpetion('A descrição da propriedade não pode ser vázio');
        }

        if ($this->maxGuests <= 0) {
            throw new EntityExpetion('O número de hospedes deve ser maior que zero');
        }
    }

    public function validateMaxGuests(int $maxGuests): void
    {
        if ($maxGuests > $this->maxGuests) {
            throw new EntityExpetion(
                "Número máximo de hóspedes excedido. O limite é de {$this->maxGuests}.",
            );
        }
    }

    public function calculateTotalPrice(DateRange $dateRange): float
    {
        $totalGuest = BcMathNumberFactory::create($this->basePriceByGuests)
            ->mul(100)
            ->mul($dateRange->getTotalNights());

        $totalProperty = BcMathNumberFactory::create($this->basePriceByNight)
            ->mul(100)
            ->mul($dateRange->getTotalNights())
            ->add($totalGuest);

        if ($dateRange->getTotalNights() >= 7) {
            $totalProperty = $totalProperty->sub($totalProperty->div(self::TOTAL_NIGHTS_DISCOUNT_7));
        }

        //82101.60
        return $totalProperty->div(100)->toFloat();
    }

    public function addBooking(Booking $booking): void
    {
        $this->bookings[] = $booking;
    }

    public function isAvailable(DateRange $dateRange): bool
    {

        return array_all(
            $this->bookings,
            fn(Booking $booking) => !$booking->isConfirmed() || !$booking->dateRange->overlaps($dateRange)
        );

    }
}