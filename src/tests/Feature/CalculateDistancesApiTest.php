<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateDistancesApiTest extends TestCase
{
    /**
     * Test a successful request to calculate distances.
     *
     * @return void
     */
    public function testCalculateDistances()
    {
        $requestData = [
            'addresses' => [
                "Eastern Enterprise B.V. - Deldenerstraat 70, 7551AH Hengelo, The Netherlands",
                "Eastern Enterprise - 46/1 Office no 1 Ground Floor , Dada House , Inside dada silk mills compound, Udhana Main Rd, near Chhaydo Hospital, Surat, 394210, India",
                "Adchieve Rotterdam - Weena 505, 3013 AL Rotterdam, The Netherlands",
            ],
        ];

        $response = $this->json('POST', '/api/geolocation/distances', $requestData);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'distances',
                 ]);
    }

    /**
     * Test an invalid request with missing addresses.
     *
     * @return void
     */
    public function testInvalidRequestMissingAddresses()
    {
        $requestData = [];

        $response = $this->json('POST', '/api/geolocation/distances', $requestData);

        $response->assertStatus(400);
    }

}
