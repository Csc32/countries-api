<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Countries;
use App\Models\States;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CountriesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
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
    //this test have been desactivated

    /** @test */
    //this test was repeated
    /*    public function countries_enpoint_return_sucessfull_response(): void
    {
        $countries = Countries::factory()->create();
        $response = $this->get('api/countries');

        $response->assertStatus(200)->assertJsonCount(1, "countries");
}*/
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
    public function should_return_specifyc_country_by_id(): void
    {
        //insert data in db
        $countries = Countries::factory()->create();

        $expectedJson = [
            "id" => $countries->id,
            "name" => $countries->getAttributeValue("name"),
            "population" => $countries->getAttributeValue("population"),
        ];

        $expectedJsonStructure = [
            'country' => [
                "*" => [
                    "id",
                    "name",
                    "population"
                ],
            ],
        ];
        //get response
        $response = $this->getJson($this->endPoint . "/$countries->id");
        // validate expected results
        $response->assertStatus(200);

        $response->assertJsonStructure($expectedJsonStructure);

        $response->assertJsonCount(1, "country");

        $response->assertJsonIsArray("country");

        $response->assertJsonFragment($expectedJson);
    }

    /** @test */
    // this test was desactivated because the next test done the requirement
    /*public function countries_get_states_enpoint_return_successfull_response(): void
    {
        $country = Countries::factory()->hasStates(5)->create();
        //get response
        $response = $this->getJson($this->endPoint . "/getStates/" . $country->id);
        $response->assertOk()->assertJson([
            "statusCode" => 200
        ], true);
    }*/

    /** @test */
    public function should_return_bad_request_if_states_not_exists(): void
    {
        //prepare variables
        $country = Countries::factory()->create();

        $expectedJson = [
            "message" => "States not found in the country",
            'country' => ["id" => $country->id, "name" => $country->name],
        ];
        $expectedStructure = [
            "message",
            'country' => ["id", "name"],
        ];
        $expectedJsonFragment = [
            "id" => $country->value("id"),
            "name" => $country->value("name")
        ];
        //get response
        $response = $this->getJson($this->endPoint . "/getStates/" . $country->id);
        //check if the response status if 404
        $response->assertStatus(404)->assertJsonStructure($expectedStructure);
        // validate expected json results
        $response->assertJsonCount(2)->assertJson($expectedJson);

        // validate expected json data
        $response->assertJsonIsObject()->assertJsonFragment($expectedJsonFragment);
    }
    /** @test */
    public function should_return_all_state_of_a_country(): void
    {
        $country = Countries::factory()->count(5)->hasStates(5)->create();
        $statesOfCountry = States::query()->where("country_id", "=", $country[1]->id)->get(['id', 'name'])->toArray();
        $expectedJsonStructure = [
            $country[1]->name => [
                "states" => ["*" => ["id", "name"]]
            ]
        ];

        $expectedJson = [
            $country[1]->name => [
                //"states" => $statesOfCountry, -> this need to modify to improve the test
            ]
        ];

        $expectedStates = [
            "states" => $statesOfCountry
        ];
        //get response
        $response = $this->getJson($this->endPoint . "/getStates/" . $country[1]->id);
        // validate expected json results
        $response->assertJsonStructure($expectedJsonStructure)
            ->assertJsonCount(1)
            ->assertJson($expectedJson);

        $this->assertSame(json_encode($expectedStates), json_encode($response->collect()->first()));
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
            $this->endPoint . "/store",
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

        $response = $this->postJson($this->endPoint . "/store", $testJson);

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

        $response = $this->postJson($this->endPoint . "/store", $testJson);
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

        $response = $this->postJson($this->endPoint . "/store", $testJson);

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
            $this->endPoint . "/store",
            $inputJson
        );
        // response with the same stucture
        $response->assertJsonStructure($expectedJsonStructure);

        // response with the same content
        $response->assertJson($expectedJson);
    }
    /** @test */
    public function return_bad_request_if_there_is_not_data_for_update_country(): void
    {
        $country = Countries::factory()->count(10)->create();

        $expectedJson = [
            'error' => "The country_id 0 doesn't exists"
        ];
        $response = $this->putJson($this->endPoint . "/" . 0);

        $response->assertBadRequest();

        $response->assertJson($expectedJson);
    }
    /** @test */
    public function return_error_message_if_not_a_valid_param_country_id(): void
    {
        $country = Countries::factory()->count(10)->create();

        $expectedJson = [
            'error' => "Invalid country_id given, please check and try again"
        ];
        $response = $this->putJson($this->endPoint . "/b");

        $response->assertBadRequest();

        $response->assertJson($expectedJson);
    }
    /** @test */
    public function return_an_error_if_data_are_invalid_for_update(): void
    {
        $country = Countries::factory()->count(10)->create();

        $inputJson = [
            "country_data" => [
                'name' => rand(100, 1000),
                'population' => fake()->country,
            ]
        ];

        $expectedJson = [
            "error" => "Invalid params, please check and try again",
        ];

        $response = $this->putJson($this->endPoint . "/" . $country[0]->id, $inputJson);
        $response->assertBadRequest();
        // save json in variable

        $response->assertJson($expectedJson);
    }
    /** @test */
    public function return_a_message_when_a_country_has_been_updated(): void
    {
        $country = Countries::factory()->count(10)->create();

        $testJson = [
            'name' => fake()->country,
            'population' => rand(1000, 6000)
        ];
        $response = $this->putJson($this->endPoint . "/" . $country[0]->id, ["country_data" => $testJson]);
        $response->assertOk();
        // save json in variable
        $response->assertJsonStructure([
            "updated_country" => [
                "name",
                'population'
            ],
            "message",
        ]);

        $response->assertJson([
            "updated_country" =>
            $testJson,
            "message" => "data updated successfully",
        ]);
        // verify is the country was updated successfully
        $dbData = DB::table("countries")->where("id", "=", $country[0]->id)->get(["name", "population"]);
        $this->assertSame(json_encode($testJson), json_encode($dbData[0]));
    }

    /** @test */
    // delete end point
    public function return_bad_request_if_the_id_is_invalid(): void
    {
        $country = Countries::factory()->count(2)->create();

        $invalidParam = "b";

        $expectedJson = [
            "message" => "Was a problem to delete the country",
            "errors" => [
                "title" => "Bad request",
                "status" => 400,
                "details" => "The param provided is invalid expected a number given $invalidParam"
            ]
        ];
        $response = $this->delete($this->endPoint . "/" . $invalidParam);

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    // delete end point
    public function return_not_found_if_country_not_exist(): void
    {
        $country = Countries::factory()->count(2)->create();

        $testId = 10;
        $expectedJson = [
            "message" => "Was a problem to delete the country",
            "errors" => [
                "title" => "Not Found",
                "status" => 404,
                "details" => "Not exist a country with id $testId"
            ]
        ];
        $response = $this->delete($this->endPoint . "/" . $testId);

        $response->assertBadRequest()->assertJson($expectedJson);
    }

    /** @test */
    // delete end point
    public function should_delete_a_country(): void
    {
        $country = Countries::factory()->create();

        $testId = $country->id;
        $expectedJson = [
            "message" => "country deleted correctly"
        ];

        $response = $this->delete($this->endPoint . "/" . $testId);

        $response->assertOk()->assertJson($expectedJson);

        $this->assertModelMissing($country);
    }
}
