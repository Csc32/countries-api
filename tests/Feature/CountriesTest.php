<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Countries;
use App\Models\States;
use Database\Factories\CountriesFactory;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;
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
        $countries = Countries::factory()->count(0)->create();
        $response = $this->getJson($this->endPoint);
        // response with the same status code
        $response->assertStatus(404);
        // response with the same content
        $response->assertJson([
            'message' => "There is not data in the api"
        ]);
        $response->assertJsonIsObject();
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
        $countries =  Countries::factory()->count(10)->create();

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
        //$statesOfCountry = DB::table("states")->where("country_id", "=", $country[1]->id)->get(["id", "name"]);
        $expectedJsonStructure = [
            'country' => [
                "data" => ["id", "name", "population"],
                "states" => ["*" => ["id", "name"]]
            ]
        ];

        $expectedJson = [
            'country' => [
                "data" => ["id" => $country[1]->id, "name" => $country[1]->name, "population" => $country[1]->population],
                //"states" => $statesOfCountry, -> this need to modify to improve the test
            ]
        ];
        //get response
        $response = $this->getJson($this->endPoint . "/getStates/" . $country[1]->id);
        // validate expected json results
        $response->assertJsonStructure($expectedJsonStructure);

        $response->assertJson($expectedJson);

        $response->assertJsonCount(2, "country");

        $response->assertJsonIsObject("country");

        $response->assertJsonCount(5, "country.states")->assertJsonIsArray("country.states");
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
    public function insert_country_if_there_is_valid_params_insert(): void
    {
        $country = Countries::factory()->create();

        $inputJson =  ["country" => [
            'id' => rand(200, 300),
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
}
