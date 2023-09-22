<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeolocationService;

class DistanceController extends Controller
{
    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function calculateDistances(Request $request)
    {
        $addresses = $request->input('addresses', []);

        if (empty($addresses)) {
            return response()->json(['message' => 'No addresses provided.'], 400);
        }

        // Get the geolocation of the first address
        $firstAddress = $addresses[0];
        $firstGeolocation = $this->geolocationService->getGeolocationFromAddress($firstAddress);

        if ($firstGeolocation === null) {
            return response()->json(['message' => 'Unable to get geolocation for the first address.'], 500);
        }

        // Calculate distances for other addresses
        $distances = [];

        for ($i = 1; $i < count($addresses); $i++) {
            $currentAddress = $addresses[$i];
            $currentGeolocation = $this->geolocationService->getGeolocationFromAddress($currentAddress);

            if ($currentGeolocation === null) {
                return response()->json(['message' => "Unable to get geolocation for address $currentAddress."], 500);
            }

            // Calculate the distance between the first address and the current address
            $distance = $this->calculateDistance($firstGeolocation, $currentGeolocation);

            $distances[] = [
                'address' => $currentAddress,
                'distance' => $distance,
            ];
        }

        return response()->json(['distances' => $distances]);
    }

    private function calculateDistance($geolocation1, $geolocation2)
    {
        // Calculate the distance between the two geolocation points.
        $distance = $geolocation1->calculateDistance($geolocation2);

        return $distance;
        // Use your geolocation service's method to calculate the distance between two geolocations.
        // You can reuse the method we previously created in the GeolocationService.
        return $this->geolocationService->getGeolocationFromAddress($geolocation1, $geolocation2);
    }
}
