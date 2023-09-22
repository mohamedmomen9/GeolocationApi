<?php

namespace App\Console\Commands;

use App\Address;
use League\Csv\Writer;
use Illuminate\Console\Command;
use App\Services\GeolocationService;

class CalculateDistancesCommand extends Command
{
    protected $signature = 'calculate:distances';
    protected $description = 'Calculate distances between multiple addresses';

    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        parent::__construct();
        $this->geolocationService = $geolocationService;
    }

    public function handle()
    {
        $addresses = [];
        $input = $this->ask('Enter the first address:');
        while($input !== 'q') {
            $addresses[] = $input;
            $input = $this->ask('Enter the next address or q to finish:');
            if ($input === 'q') {
                break;
            }
        }

        $firstGeolocation = $this->geolocationService->getGeolocationFromAddress(new Address($addresses[0]));

        if (!$firstGeolocation) {
            $this->error('Invalid first address');
            return;
        }

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
        $distanceCollection = collect($distances)->sortBy('distance');

        $this->table(['Name', 'Address', 'Distance (km)'], $distanceCollection->toArray());

        //save the output as csv file
        $writer = Writer::createFromPath(storage_path("app/distance.csv"), 'w+');
        $writer->insertAll($distanceCollection->toArray());

        $this->info('Distances saved to distances.csv');

    }
}
