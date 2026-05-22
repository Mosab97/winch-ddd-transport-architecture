<?php

namespace Src\Domain\Dispatch\Services;

class DistanceCalculatorService
{
    public function haversineKilometers(
        float $fromLatitude,
        float $fromLongitude,
        float $toLatitude,
        float $toLongitude,
    ): float {
        $earthRadiusKilometers = 6371;

        $latitudeDelta = deg2rad($toLatitude - $fromLatitude);
        $longitudeDelta = deg2rad($toLongitude - $fromLongitude);

        $fromLatitude = deg2rad($fromLatitude);
        $toLatitude = deg2rad($toLatitude);

        $a = sin($latitudeDelta / 2) ** 2
            + cos($fromLatitude) * cos($toLatitude) * sin($longitudeDelta / 2) ** 2;

        return $earthRadiusKilometers * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}
