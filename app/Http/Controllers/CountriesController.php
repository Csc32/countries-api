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
    public function store(Request $country_id)
    {
        //
        $data = $country_id->input("country");
        if (empty($data) || count($data) == 0) {
            return response()->json(
                [
                    "error" => "Required parameters are missing"
                ],
                400
            );
        }
        $result =  DB::table("countries")->insert($data);
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
        if (isset($country_id) && !is_numeric($country_id)) {
            return response()->json([
                'message' => "param should be a number"
            ], 400);
        }
        //$states = DB::table("states")->join("countries", "countries.id", "=", "states.country_id")->select("states.*", "countries.id")->where("id", "=", $country)->get();
        //$states = DB::table("states")->where("country_id", $country)->get();
        //$countryData = Countries::query()->where("id", "=", $country)->first();
        $countryData = Countries::find($country_id);
        $statesOfCountry = States::query()->where("country_id", $country_id)->get(["id", "name"]);
        if ($statesOfCountry->isEmpty()) {
            return response()->json([
                'message' => "States not found in the country",
                "country" => [
                    "id" => $countryData->id,
                    "name" => $countryData->name,
                ]
            ], 404);
        }
        return response()->json([
            "country" => [
                "data" => $countryData,
                'states' => $statesOfCountry,
            ],
        ], 200);
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
    public function destroy(Countries $countries)
    {
        //
    }
}
