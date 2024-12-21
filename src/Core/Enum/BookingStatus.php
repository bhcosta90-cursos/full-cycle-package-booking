<?php

namespace Package\Core\Enum;

enum BookingStatus: int
{
    case CONFIRMED = 1;
    case CANCELED = 2;
    case COMPLETED = 3;
}