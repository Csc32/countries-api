<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountriesController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware("api")->apiResource("/countries", CountriesController::class);
Route::post("/countries/store", [CountriesController::class, "store"]);
Route::get("/countries/getStates/{country_id}", [CountriesController::class, "getStates"]);

/*"countries/getStates/{country}" => CountriesController::class
]);*/
