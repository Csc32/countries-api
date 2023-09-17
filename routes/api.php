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
    $prefixes = ["countries", "states", "municipalities", "parishes", "zone"];
    Route::prefix($prefixes[0])->group(function () {
        Route::controller(CountriesController::class)->group(function () {
            Route::get("/", [CountriesController::class, "index"]);
            Route::get("/{country_id}", [CountriesController::class, "show"]);
            Route::get("/{country_id}/countStates", [CountriesController::class, "countStates"]);
            Route::get("/getStates/{country_id}", [CountriesController::class, "getStates"]);
            Route::post("/", [CountriesController::class, "store"]);
            Route::put("/{country_id}", [CountriesController::class, "update"]);
            Route::delete("/{country_id}", [CountriesController::class, "destroy"]);
        });
    });
    Route::prefix($prefixes[1])->group(function () {
        Route::controller(StatesController::class)->group(function () {
            Route::get("/", [statesController::class, "index"]);
            Route::get("/{state_id}", [statesController::class, "show"]);
            Route::post("/", [statesController::class, "store"]);
            Route::put("/{state_id}", [statesController::class, "update"]);
            Route::delete("/{state_id}", [statesController::class, "destroy"]);
        });
    });
});

// Route::apiResources($apiRoutes);
