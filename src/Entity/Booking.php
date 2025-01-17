<?php

namespace Package\Entity;

use DateTime;
use Package\Enum\BookingStatus;
use Package\Exception\EntityException;
use Package\Factory\BcMathNumberFactory;
use Package\Factory\CalculateRefundFactory;
use Package\ValueObject\DateRange;
use Package\ValueObject\Payment;

class Booking
{
    protected array $payments = [];
    protected float $total;

    public function __construct(
        readonly protected(set) string $id,
        readonly protected(set) Property $property,
        readonly protected(set) User $user,
        readonly protected(set) DateRange $dateRange,
        readonly protected(set) int $guestCount,
        readonly protected(set) int $daysCanceled,
        protected(set) ?BookingStatus $status = null,
    ) {
        $this->validate();
        $this->property->addBooking($this);
        $this->total = $this->property->calculateTotalPrice($this->dateRange);
    }

    protected function validate(): void
    {
        if ($this->guestCount <= 0) {
            throw new EntityException('O número de hospedes deve ser maior que zero');
        }

        $this->property->validateMaxGuests($this->guestCount);

        if ($this->status !== BookingStatus::Canceled) {
            match ($this->property->percentagePriceConfirmation) {
                0.0 => $this->status = BookingStatus::Confirmed,
                default => $this->status = BookingStatus::Pending,
            };
        }

        if (!$this->property->isAvailable($this->dateRange)) {
            throw new EntityException('The property is not available for the requested date.');
        }
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::Confirmed;
    }

    public function cancel(DateTime $dateCancel): void
    {
        if ($this->isCanceled()) {
            throw new EntityException('A reserva já foi cancelada.');
        }

        $checkingDate = $this->dateRange->start;

        $diff = $checkingDate->diff($dateCancel);

        $this->total = CalculateRefundFactory::handle($this->daysCanceled, $diff->days)->calculateRefund($this->total);
        $this->status = BookingStatus::Canceled;
    }

    public function isCanceled(): bool
    {
        return $this->status === BookingStatus::Canceled;
    }

    public function addPayment(Payment $payment): void
    {
        $this->payments[] = $payment;

        $totalPayments = $this->getTotalPayments();

        $checkinValue = $this->getTotalCheckinValue();

        if ($totalPayments >= $checkinValue) {
            $this->status = BookingStatus::Confirmed;
        }
    }

    public function getTotalPayments(): int
    {
        return array_sum(array_map(fn($payment) => $payment->amount, $this->payments));
    }

    public function getTotalCheckinValue(): int
    {
        return (int) BcMathNumberFactory::create($this->property->percentagePriceConfirmation)
            ->div(100)
            ->mul($this->total)
            ->getValue();
    }

    public function getTotalPrice(): int
    {
        return $this->total;
    }
}
