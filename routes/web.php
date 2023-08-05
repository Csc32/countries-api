<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/countries', function (CountriesController $controller) {
    // Call the method to retrieve countries data
    $countries = $controller->index();
    return $countries;
})->middleware("api");
Route::post('/countries/store', function (CountriesController $controller) {
    // Call the method to retrieve countries data
    $country = [];
    $countries = $controller->store($country);
    return $countries;
})->middleware("api");
