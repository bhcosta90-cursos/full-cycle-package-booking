<?php

namespace Package\Enum;

enum PaymentType: int
{
    case InputValue = 1;
    case CheckoutValue = 2;
}