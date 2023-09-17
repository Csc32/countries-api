<?php

namespace Tests\Feature\Countries;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Countries;
use App\Models\States;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;

class getCountriesTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected $endPoint = "api/countries";
    /** @test */
    public function return_404_when_not_countries(): void
    {
        Countries::factory()->count(0)->create();
        $expectedJson = [
            'message' => "There is not data in the api"
        ];

        $response = $this->getJson($this->endPoint);

        $response->assertStatus(404)->assertJson($expectedJson);
    }

    /** @test */
    public function return_array_of_all_countries(): void
    {
        //insert data in db
        $countries =  Countries::factory()->count(3)->create();

        $response = $this->getJson($this->endPoint);

        $response->assertOk();

        $response->assertJsonStructure([
            'countries' => [
                "*" => [
                    "id",
                    "name",
                    "population"
                ],
            ],
        ]);

        $response->assertJsonCount(1);

        $response->assertJsonIsArray("countries");

        foreach ($countries->all() as $country) {
            $response->assertJsonFragment([
                "id" => $country->id,
                "name" => $country->name,
                "population" => $country->getAttributeValue("population")
            ]);
        }
    }

    /** @test */
    public function param_country_id_should_be_number(): void
    {
        $expectedJson = [
            "error" => "param should be a number"
        ];

        $response = $this->getJson($this->endPoint . "/b");

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function should_count_the_states_of_country(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(24)->create();

        $country = Countries::query()->get()->first();
        $response = $this->getJson($this->endPoint . "/" . $country->id . "/countStates");

        $response->assertOk()->assertJson([
            "country" => $country->name,
            "totalStates" => 24
        ]);
    }
}
