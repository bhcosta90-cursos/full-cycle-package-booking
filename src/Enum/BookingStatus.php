<?php

namespace Package\Enum;

enum BookingStatus: int
{
    case Confirmed = 1;
    case Pending = 2;
    case Canceled = 3;
    case Completed = 4;
}