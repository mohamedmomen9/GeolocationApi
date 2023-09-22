<?php

namespace App\Services;

use App\Address;
use GuzzleHttp\Client;

class GeolocationService
{
    protected $apiKey;
    protected $client;
    protected $url;

    public function __construct($apiKey)
    {
        $this->apiKey = env('POSITIONSTACK_API_KEY', false);
        // $this->apiKey = $apiKey;
        $this->client = new Client();
        $this->url = env('POSITIONSTACK_URL', false);
    }

    public function getGeolocationFromAddress(Address $address)
    {

        $params = [
            'access_key' => $this->apiKey,
            'query' => $address->getAddress(),
        ];

        try {
            $response = $this->client->get($this->url, ['query' => $params]);
            $data = json_decode($response->getBody(), true);

            // Extract latitude and longitude from the response data.
            $latitude = $data['data'][0]['latitude'];
            $longitude = $data['data'][0]['longitude'];

            return new \App\Geolocation($latitude, $longitude);
        } catch (\Exception $e) {
            // ToDo: add custom exception
            return null;
        }
    }
}
