<?php

namespace App;

class Geolocation
{
    protected $latitude;
    protected $longitude;

    public function __construct($latitude, $longitude)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    public function setLatitude($latitude)
    {
        // You can add validation here if needed.
        $this->latitude = $latitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLongitude($longitude)
    {
        // You can add validation here if needed.
        $this->longitude = $longitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Calculate the distance between two Geolocation objects using the Haversine formula.
     *
     * @param Geolocation $destination
     * @param string $unit
     * @return float
     */
    public function calculateDistance(Geolocation $destination)
    {
        $earthRadius = 6371;

        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($destination->getLatitude());
        $lon2 = deg2rad($destination->getLongitude());

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
