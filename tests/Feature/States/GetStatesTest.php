<?php

namespace Tests\Feature\States;

use App\Models\States;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetStatesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $url = "/api/states";
    /** @test */
    public function route_should_return_404_and_message(): void
    {
        States::factory()->count(0)->create();

        $expectedJson = [
            "message" => "There was a problem to retrieve all states",
            "errors" => [
                "title" => "Not Found",
                "code" => 404,
                "details" => "There are not records in database"
            ]
        ];

        $response = $this->getJson($this->url);

        $response->assertNotFound()->assertJson($expectedJson);
    }

    /** @test */

    public function should_return_bad_request_if_there_is_invalid_param(): void
    {

        $expectedJson = [
            "message" => "There was a problem to retrieve all states",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "Param provided is invalid"
            ]
        ];

        $response = $this->getJson($this->url . "/b");

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */

    public function should_return_404_if_not_find_a_state(): void
    {
        $states = States::factory()->count(5)->create();

        $expectedJson = [
            "message" => "There was a problem to retrieve all information of the state 100",
            "errors" => [
                "title" => "Not Found",
                "code" => 404,
                "details" => "The is not information of the state with id: 100"
            ]
        ];

        $response = $this->getJson($this->url . "/100");

        $response->assertNotFound()->assertJson($expectedJson);
    }
    /** @test */

    public function should_return_all_states_and_status_200(): void
    {
        $states = States::factory()->count(5)->create();

        $expectedJson = [
            "states" => ['*' => [
                "id",
                "name",
                "population",
                "country_id"
            ]]
        ];

        $response = $this->getJson($this->url);

        $response->assertOk()->assertJsonStructure($expectedJson);
    }
    /** @test */

    public function should_return_information_if_state_exist(): void
    {
        $states = States::factory()->count(5)->create();

        $expectedJson = [
            "state" =>  [
                "id" => $states[0]->id,
                "name" =>  $states[0]->name,
                "population" =>  $states[0]->population,
                "country_id" =>  $states[0]->country_id
            ]
        ];

        $response = $this->getJson($this->url . "/" . $states[0]->id);

        $response->assertOk()->assertJson($expectedJson);
    }
}
