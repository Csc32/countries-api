<?php

namespace Tests\Feature\Countries;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Countries;
use Tests\TestCase;

class deleteCountriesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected $endPoint = "api/countries";

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
