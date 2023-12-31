<?php

namespace App\Http\Controllers;

use App\Address;
use League\Csv\Writer;
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
        $firstAddress = new Address($addresses[0]);
        $firstGeolocation = $this->geolocationService->getGeolocationFromAddress($firstAddress);

        if ($firstGeolocation === null) {
            return response()->json(['message' => 'Unable to get geolocation for the first address.'], 500);
        }

        // Calculate distances for other addresses
        $distances = [];

        for ($i = 1; $i < count($addresses); $i++) {
            $currentAddress = new Address($addresses[$i]);
            $currentGeolocation = $this->geolocationService->getGeolocationFromAddress($currentAddress);

            if ($currentGeolocation === null) {
                return response()->json(['message' => "Unable to get geolocation for ".$currentAddress->getName()."."], 500);
            }

            // Calculate the distance between the first address and the current address
            $distance = $firstGeolocation->calculateDistance($currentGeolocation);

            $distances[] = [
                'name' => $currentAddress->getName(),
                'address' => $currentAddress->getAddress(),
                'distance' => $distance,
            ];
        }
        $distances = collect($distances)->sortBy('distance');

        //save the output as csv file
        $writer = Writer::createFromPath(storage_path("app/distance.csv"), 'w+');
        $writer->insertAll($distances->toArray());

        return response()->json(['distances' => $distances, 'file' => storage_path("app/distance.csv")]);
    }
}
