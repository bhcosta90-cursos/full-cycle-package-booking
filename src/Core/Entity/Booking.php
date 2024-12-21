<?php

namespace Package\Core\Entity;

use Package\Core\Enum\BookingStatus;
use Package\Core\Exception\EntityExpetion;
use Package\Core\Factory\BcMathNumberFactory;
use Package\Core\Factory\CalculateRefundFactory;
use Package\Core\ValueObject\DateRange;

class Booking
{
    protected BookingStatus $status = BookingStatus::CONFIRMED;

    protected float $total;

    public function __construct(
        readonly protected(set) string $id,
        readonly protected(set) Property $property,
        readonly protected(set) User $user,
        readonly protected(set) DateRange $dateRange,
        readonly protected(set) int $guestCount,
    )
    {
        $this->validate();
        $this->property->addBooking($this);
        $this->total = $this->property->calculateTotalPrice($this->dateRange);
    }

    protected function validate(): void
    {
        if ($this->guestCount <= 0) {
            throw new EntityExpetion('O número de hospedes deve ser maior que zero');
        }

        $this->property->validateMaxGuests($this->guestCount);

        if (!$this->property->isAvailable($this->dateRange)) {
            throw new EntityExpetion('A propriedade não está disponível para a data solicitadas.');
        }
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::CONFIRMED;
    }

    public function cancel(\DateTime $dateCancel): void
    {
        if ($this->isCanceled()) {
            throw new EntityExpetion('A reserva já foi cancelada.');
        }

        $checkingDate = $this->dateRange->start;

        $diff = $checkingDate->diff($dateCancel);

        $this->total = CalculateRefundFactory::handle($diff->days)->calculateRefund($this->total);
        $this->status = BookingStatus::CANCELED;
    }

    public function isCanceled(): bool
    {
        return $this->status === BookingStatus::CANCELED;
    }

    public function checkout(\DateTime $dateCancel): ?float
    {
        $checkingDate = $this->dateRange->end;

        if ($dateCancel > $checkingDate) {
            $dateCancel->add(new \DateInterval('P1D'));
            $valuePrice = $this->property->calculateTotalPrice(new DateRange($this->dateRange->start, $dateCancel));
            return BcMathNumberFactory::create($valuePrice)->sub($this->total)->toFloat();
        }

        $this->status = BookingStatus::COMPLETED;

        return null;
    }

    public function isCompleted(): bool
    {
        return $this->status === BookingStatus::COMPLETED;
    }

    public function getTotalPrice(): float
    {
        return $this->total;
    }
}