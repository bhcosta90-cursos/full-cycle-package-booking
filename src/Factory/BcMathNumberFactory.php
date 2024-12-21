<?php

namespace Package\Factory;

use Webit\Wrapper\BcMath\BcMathNumber;

class BcMathNumberFactory
{
    public static function create(float $value): BcMathNumber
    {
        BcMathNumber::setDefaultScale(2);
        return new BcMathNumber($value);
    }
}