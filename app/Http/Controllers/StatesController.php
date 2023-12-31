<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\States;
use Symfony\Component\VarDumper\VarDumper;

class StatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $states = States::all();
        $errorResponse = [
            "message" => "There was a problem to retrieve all states",
            "errors" => [
                "title" => "Not Found",
                "code" => 404,
                "details" => "There are not records in database"
            ]
        ];

        $response = [
            "states" => $states
        ];
        if ($states->count() == 0) {
            return response()->json($errorResponse, 404);
        }


        return response()->json($response, 200);
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
    public function store(Request $request): JsonResponse
    {
        $errorResponse = [
            "message" => "There was a problem to insert the state",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "There are missing parameters"
            ]
        ];
        $typeOfDetail = [
            "id" => "ID provided, please delete it",
            "name" => "Name should be a string",
            "population" =>  "Population should be integer",
            "country_id" =>  "Not exists a country with id " . $request->country_id
        ];

        $response = [
            "message" => "State inserted correctly"
        ];
        if ($request->input() == null) {
            return response()->json($errorResponse, 400);
        }
        foreach ($request->input() as $key => $value) {
            if (isset($key) && $key == "id" || $key == "name" && is_numeric($value) || $key == 'population' && is_string($value)) {
                $errorResponse["errors"]['details'] = $typeOfDetail[$key];
                return response()->json(
                    $errorResponse,
                    400
                );
            }
        }
        $country = Countries::find($request->country_id);
        if (!isset($country)) {
            $errorResponse["errors"]['details'] = $typeOfDetail['country_id'];
            return response()->json(
                $errorResponse,
                400
            );
        }

        $state = new States([
            "name" => $request->name,
            "population" => $request->population
        ]);

        $country->states()->save($state);

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($state_id)
    {
        $errorResponse = [
            "message" => "There was a problem to retrieve all states",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "Param provided is invalid"
            ]
        ];
        if (!is_numeric($state_id)) {
            return response()->json($errorResponse, 400);
        }

        $foundState = States::find($state_id);
        $response = [
            "state" => $foundState
        ];

        if (!isset($foundState)) {
            $errorResponse['message'] = "There was a problem to retrieve all information of the state $state_id";
            $errorResponse['errors']['title'] = "Not Found";
            $errorResponse['errors']['code'] = 404;
            $errorResponse['errors']['details'] = "The is not information of the state with id: $state_id";

            return response()->json($errorResponse, 404);
        }

        return  response()->json($response, 200);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id): JsonResponse
    {
        //
        $errorResponse = [
            "message" => "There was a problem to update the state",
            "errors" => [
                "title" => "Bad Request",
                "code" => 400,
                "details" => "There are missing parameters"
            ]
        ];
        $typeOfDetail = [
            "id" => "The parameter should be a number",
            "name" => "Name should be a string",
            "population" =>  "Population should be integer",
            "country_id" =>  "Not exists a country with id " . $request->country_id
        ];

        $response = [
            "message" => "State updated correctly"
        ];

        if (!is_numeric($id)) {
            $errorResponse['errors']['details'] = $typeOfDetail['id'];
            return response()->json($errorResponse, 400);
        }
        $foundState = States::query()->find($id);

        if (!$foundState) {
            $errorResponse['errors']['title'] = "Not Found";
            $errorResponse['errors']['details'] = "The state doesn't exist";
            $errorResponse['errors']['code'] = 404;
            return response()->json($errorResponse, 404);
            # code...
        }

        foreach ($request->input() as $key => $value) {
            if (isset($key) && $key == "id" || $key == "name" && is_numeric($value) || $key == 'population' && is_string($value)) {
                $errorResponse["errors"]['details'] = $typeOfDetail[$key];
                return response()->json(
                    $errorResponse,
                    400
                );
            }
        }

        $country = Countries::query()->find($request->country_id);
        if (!isset($country)) {
            $errorResponse["errors"]['details'] = $typeOfDetail['country_id'];
            return response()->json(
                $errorResponse,
                400
            );
        }

        if ($foundState->update($request->input())) {
            return response()->json($response, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $state = States::find($id);
        if (!isset($state)) {
            return response()->json(
                [
                    "message" => "Was a problem to delete the state",
                    "errors" => [
                        "title" => "Not Found",
                        "status" => 404,
                        "details" => "Not exist a country with id $id"
                    ]
                ],
                400
            );
        }
        $state->delete();
        return response()->json([
            "message" => "country deleted correctly"
        ], 200);
    }
}
