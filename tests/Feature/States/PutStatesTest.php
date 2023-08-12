<?php

namespace Tests\Feature\States;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Countries;
use App\Models\States;
use Tests\TestCase;

class PutStatesTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    protected $url = "/api/states";

    /** @test */
    public function should_return_bad_request_if_not_parameters(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(5)->create();

        $expectedJson = [
            "message" => "There was a problem to update the state",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "There are missing parameters"
            ]
        ];
        $testJson = [
            "name" => "Caracas",
            "population" => 5000,
            "country_id" => $countries[0]->id
        ];

        $response = $this->putJson($this->url);

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function id_is_a_valid_integer(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(5)->create();

        $expectedJson = [
            "message" => "There was a problem to update the state",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "There are missing parameters"
            ]
        ];

        $response = $this->putJson($this->url . "/b");

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function id_belongs_to_a_valid_state(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(5)->create();

        $expectedJson = [
            "message" => "There was a problem to update the state",
            "errors" => [
                "title" => "Not Found",
                "code" => 404,
                "details" => "The state doesn't exist"
            ]
        ];



        $response = $this->putJson($this->url . "/20");

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function name_is_a_string(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(5)->create();
        $states = States::all();

        $expectedJson = [
            "message" => "There was a problem to update the state",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "Name should be a string"
            ]
        ];

        $testJson = [
            "name" => 1,
            "population" => 2000,
            "country_id" => 1
        ];

        $response = $this->putJson($this->url . "/" . $states[0]->id, $testJson);

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function population_is_integer(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(5)->create();
        $states = States::all();

        $expectedjson = [
            "message" => "There was a problem to update the state",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "Population should be integer"
            ]
        ];

        $testJson = [
            "name" => "Chicago",
            "population" => "1000",
            "country_id" => 1
        ];

        $response = $this->putJson($this->url . "/" . $states[0]->id, $testJson);
        $response->assertbadrequest()->assertjson($expectedjson);
    }
    /** @test */
    public function country_id_belongs_to_a_valid_country(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(5)->create();
        $states = States::all();

        $expectedjson = [
            "message" => "There was a problem to update the state",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "Not exists a country with id 1"
            ]
        ];

        $testJson = [
            "name" => "Chicago",
            "population" => 1000,
            "country_id" => 1
        ];

        $response = $this->putJson($this->url . "/" . $states[0]->id, $testJson);

        $response->assertbadrequest()->assertjson($expectedjson);
    }

    /** @test */
    public function update_a_valid_state(): void
    {
        $countries = Countries::factory()->count(5)->hasStates(5)->create();
        $states = States::all();

        $expectedjson = [
            "message" => "State updated correctly",
        ];

        $testJson = [
            "name" => "Caracas",
            "population" => 5000,
            "country_id" => $countries[0]->id
        ];

        $response = $this->putJson($this->url . "/" . $states[0]->id, $testJson);

        $response->assertOk()->assertjson($expectedjson);


        $this->assertDatabaseHas("states", [
            "id" => $states[0]->id,
            "name" => "Caracas",
            "country_id" => $countries[0]->id
        ]);
    }
}
