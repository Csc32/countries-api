<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\StatesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// $apiRoutes = [
//     "countries" => CountriesController::class,
//     "states" => StatesController::class
// ];

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware("api")->group(function () {
    Route::controller(CountriesController::class)->group(function () {
        Route::get("countries", [CountriesController::class, "index"]);
        Route::get("countries/{country_id}", [CountriesController::class, "show"]);
        Route::get("countries/getStates/{country_id}", [CountriesController::class, "getStates"]);
        Route::post("countries", [CountriesController::class, "store"]);
        Route::put("countries/{country_id}", [CountriesController::class, "update"]);
        Route::delete("countries/{country_id}", [CountriesController::class, "destroy"]);
    });

    Route::controller(StatesController::class)->group(function () {
        Route::get("states", [statesController::class, "index"]);
        Route::get("states/{state_id}", [statesController::class, "show"]);
        Route::post("states", [statesController::class, "store"]);
        Route::put("states/{state_id}", [statesController::class, "update"]);
        Route::delete("states/{state_id}", [statesController::class, "destroy"]);
    });
});

// Route::apiResources($apiRoutes);
