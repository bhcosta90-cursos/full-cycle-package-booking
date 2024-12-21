<?php

namespace Package\Enum;

enum PaymentType: int
{
    case CheckinValue = 1;
    case CheckoutValue = 2;
}