<?php

namespace Tests\Feature\Countries;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use App\Models\Countries;
use App\Models\States;
use Tests\TestCase;

class postCountriesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected $endPoint = "api/countries";
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    //post test
    public function return_bad_request_if_there_is_not_data_of_the_country(): void
    {
        $country = Countries::factory()->create();
        $expectedJson = [
            "error" => "Required parameters are missing"
        ];
        $response = $this->postJson(
            $this->endPoint,
            ["country" => []]
        );
        $response->assertStatus(400)->assertJson($expectedJson);
    }

    /** @test */
    //post test
    /**
     * This test was desactivated because change use case
    public function return_bad_request_if_there_is_a_country_with_id_provided(): void
    {
        $country = Countries::factory()->create();

        $countryJson = [
            "country" => [
                "id" => $country->id,
                "name" => "Chile",
                "population" => 2000
            ]
        ];

        $expectedJson = [
            "error" => "A country with id provided already exist",
            "country" => [
                "id" => $country->id,
                "name" => $country->name,
                "population" => $country->population
            ]
        ];


        $response = $this->postJson(
            $this->endPoint . "/store",
            $countryJson
        );

        $response->assertStatus(400);

        $response->assertJson($expectedJson);
    }
     */
    /** @test */
    //post test
    public function param_not_have_a_id(): void
    {
        $country = Countries::factory()->create();

        $expectedJson = [
            "message" => "Invalid params, please check",
            "errors" => [
                "title" => "Bad request",
                "status" => 400,
                "details" => "ID provided, please delete it",
            ]
        ];

        $testJson = [
            "country" => [
                "id" => "a",
                "name" => "Chile",
                "population" => 2000
            ]
        ];

        $response = $this->postJson($this->endPoint, $testJson);

        $response->assertBadRequest()->assertJson($expectedJson);
    }
    /** @test */
    //post test
    public function return_bad_request_when_there_is_invalid_name_for_insert(): void
    {
        $country = Countries::factory()->create();

        $expectedJson = [
            "message" => "Invalid params, please check",
            "errors" => [
                "title" => "Bad request",
                "status" => 400,
                "details" => "Name should be a string",
            ]
        ];

        $testJson = [
            "country" => [
                "name" => 2000,
                "population" => 2000
            ]
        ];

        $response = $this->postJson($this->endPoint, $testJson);
        $response->assertBadRequest()->assertJsonStructure([
            "message",
            "errors" => [
                "title",
                "status",
                "details"
            ]
        ])->assertJson([
            "message" => $expectedJson['message'],
            "errors" => [
                "title" => $expectedJson['errors']['title'],
                "status" =>  $expectedJson['errors']['status'],
            ]
        ])->assertJsonFragment(
            [
                "details" =>  $expectedJson['errors']['details']
            ]
        );
    }
    /** @test */
    //post test
    public function return_bad_request_when_there_is_invalid_population_for_insert(): void
    {
        $country = Countries::factory()->create();

        $expectedJson = [
            "message" => "Invalid params, please check",
            "errors" => [
                "title" => "Bad request",
                "status" => 400,
                "details" => "Population should be a number",
            ]
        ];

        $testJson = [
            "country" => [
                "name" => "Chile",
                "population" => "2000"
            ]
        ];

        $response = $this->postJson($this->endPoint, $testJson);

        $response->assertBadRequest()->assertJsonStructure([
            "message",
            "errors" => [
                "title",
                "status",
                "details"
            ]
        ])->assertJson([
            "message" => $expectedJson['message'],
            "errors" => [
                "title" => $expectedJson['errors']['title'],
                "status" =>  $expectedJson['errors']['status'],
            ]
        ])->assertJsonFragment(
            [
                "details" =>  $expectedJson['errors']['details']
            ]
        );
    }
    /** @test */

    //post test
    public function insert_country_if_there_is_valid_params(): void
    {
        $country = Countries::factory()->create();

        $inputJson =  ["country" => [
            'name' => 'example Contry',
            'population' => rand(2000, 3000),
        ]];

        $expectedJsonStructure = ['message'];

        $expectedJson = [
            "message" => "country inserted correctly"
        ];
        $response = $this->postJson(
            $this->endPoint,
            $inputJson
        );
        // response with the same stucture
        $response->assertJsonStructure($expectedJsonStructure);

        // response with the same content
        $response->assertJson($expectedJson);
    }
}
