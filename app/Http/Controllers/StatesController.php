<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\States;

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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
