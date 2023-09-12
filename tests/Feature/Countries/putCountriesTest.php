<?php

namespace Tests\Feature\Countries;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Countries;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class putCountriesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected $endPoint = "api/countries";

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
