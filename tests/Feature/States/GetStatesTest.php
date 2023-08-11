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

    public function should_return_all_states_and_status_200(): void
    {
        $states = States::factory()->count(5)->create();

        $expectedJson = [
            "States" => ['*' => [
                "id",
                "name",
                "population",
                "country_id"
            ]]
        ];

        $response = $this->getJson($this->url);

        $response->assertOk()->assertJsonStructure($expectedJson)->assertJson([
            "States"
        ]);
    }
}
