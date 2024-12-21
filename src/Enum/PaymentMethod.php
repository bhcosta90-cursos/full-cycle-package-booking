<?php

namespace Package\Enum;

enum PaymentMethod: int
{
    case Cash = 1;
    case Pix = 2;
    case Money = 3;
    case CreditCard = 4;
    case DebitCard = 5;
}