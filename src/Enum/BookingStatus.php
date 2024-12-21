<?php

namespace Package\Enum;

enum BookingStatus: int
{
    case CONFIRMED = 1;
    case CANCELED = 2;
    case COMPLETED = 3;
}