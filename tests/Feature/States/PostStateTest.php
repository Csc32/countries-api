<?php

namespace Tests\Feature\States;

use App\Models\Countries;
use App\Models\States;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostStateTest extends TestCase
{
    use RefreshDatabase;

    protected $url = "/api/states";

    /** @test */
    public function should_return_bad_request_if_not_parameters(): void
    {
        States::factory()->create();

        $expectedJson = [
            "message" => "There was a problem to insert the State",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "There are missing parameters"
            ]
        ];

        $response = $this->postJson($this->url);

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function params_should_not_have_id(): void
    {
        States::factory()->create();

        $expectedJson = [
            "message" => "There was a problem to insert the State",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "ID provided, please delete it",
            ]
        ];

        $testJson = [
            "id" => 1,
            "name" => "Chicago",
            "population" => 2000,
            "country_id" => 1
        ];

        $response = $this->postJson($this->url, $testJson);

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function name_is_a_string(): void
    {
        States::factory()->create();

        $expectedJson = [
            "message" => "There was a problem to insert the State",
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

        $response = $this->postJson($this->url, $testJson);

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    public function population_is_integer(): void
    {
        states::factory()->create();

        $expectedjson = [
            "message" => "there was a problem to insert the state",
            "errors" => [
                "title" => "bad request",
                "code" => 400,
                "details" => "Population should be integer"
            ]
        ];

        $testJson = [
            "name" => 1,
            "population" => "1000",
            "country_id" => 1
        ];

        $response = $this->postJson($this->url, $testJson);
        $response->assertbadrequest()->assertjson($expectedjson);
    }
    /** @test */
    public function country_id_belongs_to_a_valid_country(): void
    {
        states::factory()->create();

        $expectedjson = [
            "message" => "there was a problem to insert the state",
            "errors" => [
                "title" => "bad request",
                "code" => 400,
                "details" => "Not exists a country with id 1"
            ]
        ];

        $testJson = [
            "name" => 1,
            "population" => "1000",
            "country_id" => 1
        ];

        $response = $this->postJson($this->url, $testJson);

        $response->assertbadrequest()->assertjson($expectedjson);
    }

    /** @test */
    public function insert_a_valid_state(): void
    {
        $country = Countries::factory()->count(3)->create();
        $states = states::factory()->create();

        $expectedjson = [
            "message" => "State inserted correctly",
        ];

        $testJson = [
            "name" => "Caracas",
            "population" => 5000,
            "country_id" => $country[0]->id
        ];

        $response = $this->postJson($this->url, $testJson);

        $response->assertOk()->assertjson($expectedjson);
    }
}
