<?php

namespace Src\Domain\Dispatch\Contracts;

interface DistanceCalculatorContract
{
    public function haversineKilometers(
        float $fromLatitude,
        float $fromLongitude,
        float $toLatitude,
        float $toLongitude,
    ): float;
}
