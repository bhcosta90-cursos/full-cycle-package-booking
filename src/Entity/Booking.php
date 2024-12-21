<?php

namespace Package\Entity;

use Package\Enum\BookingStatus;
use Package\Exception\EntityExpetion;
use Package\Factory\BcMathNumberFactory;
use Package\Factory\CalculateRefundFactory;
use Package\ValueObject\DateRange;
use Package\ValueObject\Payment;

class Booking
{
    protected array $payments = [];
    protected ?BookingStatus $status = null;
    protected float $total;

    public function __construct(
        readonly protected(set) string $id,
        readonly protected(set) Property $property,
        readonly protected(set) User $user,
        readonly protected(set) DateRange $dateRange,
        readonly protected(set) int $guestCount,
        readonly protected(set) int $daysCanceled = 7,
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

        match ($this->property->percentagePriceConfirmation) {
            0.0 => $this->status = BookingStatus::Confirmed,
            default => $this->status = BookingStatus::Pending,
        };

        if (!$this->property->isAvailable($this->dateRange)) {
            throw new EntityExpetion('A propriedade não está disponível para a data solicitadas.');
        }
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::Confirmed;
    }

    public function isPending(): bool
    {
        return $this->status === BookingStatus::Pending;
    }

    public function cancel(\DateTime $dateCancel): void
    {
        if ($this->isCanceled()) {
            throw new EntityExpetion('A reserva já foi cancelada.');
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


        $checkinValue = BcMathNumberFactory::create($this->getTotalPrice())
            ->mul(100)
            ->div($this->property->percentagePriceConfirmation)
            ->div(100)
            ->toFloat();

        if ($totalPayments >= $checkinValue) {
            $this->status = BookingStatus::Confirmed;
        }
    }

    public function getTotalPayments(): float
    {
        return array_sum(array_map(fn($payment) => $payment->amount, $this->payments));
    }

    public function getTotalPrice(): float
    {
        return $this->total;
    }
}