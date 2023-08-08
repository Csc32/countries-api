<?php

namespace App\Http\Controllers;

use App\Http\Controllers\States as ControllersStates;
use App\Models\Countries;
use App\Models\States;
use Database\Factories\CountriesFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        //
        $countries = DB::table('countries')->get();
        if ($countries->isEmpty()) {
            return response()->json([
                'message' => "There is not data in the api"
            ], 404);
        }
        return response()->json([
            "countries" =>
            $countries->all()

        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $country_id): JsonResponse
    {
        //
        $data = $country_id->input("country");

        //variables for return json

        $invalidData = [
            "error" => "Required parameters are missing"
        ];

        $errorResponse = [
            "message" => 'Invalid params, please check',
            "errors" => [
                "title" => "Bad request",
                "status" => 400,
                "details" => ""
            ]
        ];
        $typeOfDetail = [
            "id" => "ID provided, please delete it",
            "name" => "Name should be a string",
            "population" =>  "Population should be a number"
        ];

        $successResponse = [
            "message" => "country inserted correctly"
        ];
        if (empty($data) || count($data) == 0) {
            return response()->json(
                $invalidData,
                400
            );
        }
        foreach ($data as $key => $value) {
            if (isset($key) && $key == "id" || $key == "name" && is_numeric($value) || $key == 'population' && is_string($value)) {
                $errorResponse["errors"]['details'] = $typeOfDetail[$key];
                return response()->json(
                    $errorResponse,
                    400
                );
            }
        }
        $result =  Countries::query()->create($data);
        if ($result) {
            return response()->json(
                [
                    "message" => "country inserted correctly"
                ],
                200
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($country_id = null)
    {
        if (isset($country_id) && !is_numeric($country_id)) {
            return response()->json([
                'error' => "param should be a number"
            ], 400);
        }
        $country = Countries::where("id", $country_id)->get();
        if ($country->isEmpty()) {
            return response()->json([
                'message' => "There is not data in the api"
            ], 404);
        }
        return response()->json([
            "country" => $country
        ], 200);
    }

    public function getStates($country_id = null)
    {
        $invalidCountryMessage = [
            'message' => "param should be a number"
        ];
        if (isset($country_id) && !is_numeric($country_id)) {
            return response()->json($invalidCountryMessage, 400);
        }
        $countryData = Countries::query()->find($country_id, ['id', 'name']);
        $statesOfCountry = States::query()->where("country_id", $country_id)->get(["id", "name"]);
        $countryNotFoundMessage = [
            'message' => "States not found in the country",
            "country" => [
                "id" => $countryData->id,
                "name" => $countryData->name,
            ]
        ];
        if ($statesOfCountry->isEmpty()) {
            return response()->json(
                $countryNotFoundMessage,
                404
            );
        }
        $countryName = $countryData->name;
        $sucessResponse = [
            "$countryName" => [
                'states' => $statesOfCountry,
            ],
        ];
        return response()->json($sucessResponse, 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Countries $countries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Countries $countries, $country_id = null)
    {
        //
        $data = $request->input('country_data');
        if (!is_numeric($country_id)) {
            return response()->json([
                "error" => "Invalid country_id given, please check and try again"
            ], 400);
        }
        if (!$country_id || $country_id < 0 || !$data) {
            return response()->json([
                "error" => "The country_id $country_id doesn't exists"
            ], 400);
        }
        if (is_numeric($data['name']) || !is_numeric($data['population'])) {
            return response()->json([
                "error" => "Invalid params, please check and try again"
            ], 400);
        }
        $result = $countries::query()->update($data);

        if ($result) {
            $country = $countries::query()->get()->where("id", "=", $country_id);
            return response()->json([
                "updated_country" => [
                    "name" => $country[0]->name,
                    "population" => $country[0]->population
                ],
                "message" => "data updated successfully"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($country_id, Countries $countries)
    {
        $errorMessage = [
            "message" => "Was a problem to delete the country",
            "errors" => [
                'title' => 'Bad request',
                'status' => 400,
                'details' => "The param provided is invalid expected a number given $country_id",
            ]
        ];

        $successMessage = [
            "message" => "country deleted correctly"
        ];

        if (empty($country_id) || !is_numeric($country_id)) {
            return response()->json($errorMessage, 400);
        }
        $result = $countries->query()->find($country_id);
        if (!isset($result)) {

            $errorMessage['errors']['title'] = "Not Found";

            $errorMessage['errors']['status'] = 404;

            $errorMessage['errors']['details'] = "Not exist a country with id $country_id";

            return response()->json($errorMessage, 400);
        }

        $result->delete();

        return response()->json($successMessage, 200);
        //
    }
}
