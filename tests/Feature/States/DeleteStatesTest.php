<?php

namespace Tests\Feature\States;

use App\Models\Countries;
use App\Models\States;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Tests\TestCase;

class DeleteStatesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected $endPoint = "api/states";

    /** @test */
    /** public function should_return_bad_request_if_param_is_invalid()
    {
        $invalidParam = "b";
        $expectedJson  = [
            "message" => "There was a problem to delete the state",
            "errors" => [
                "title" => "Bad request",
                "status" => 400,
                "details" => "The param provided is invalid expected a number given $invalidParam"
            ]
        ];

        $response = $this->delete($this->endPoint . "/" . $invalidParam);

        $response->assertBadRequest()->assertJson($expectedJson);
    }
     */

    /** @test */
    public function should_return_not_found()
    {

        $countries = Countries::factory()->count(5)->has(States::factory()->count(5))->create();
        $testId = 0;
        $expectedJson = [
            "message" => "Was a problem to delete the state",
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
    public function should_delete_a_state()
    {

        $states = States::factory()->count(5)->create();
        $state = $states->first();
        $testId = $state->id;
        $expectedJson = [
            "message" => "country deleted correctly"
        ];

        $response = $this->delete($this->endPoint . "/" . $testId);

        $response->assertOk()->assertJson($expectedJson);
        $this->assertModelMissing($state);
    }
}
