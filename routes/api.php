<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/**
 * Route fixed with help of: https://stackoverflow.com/questions/25082154/how-to-create-multilingual-translated-routes-in-laravel
 */
$all_langs = config('app.all_langs');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('countries/{countryCode}/states', 'Backend\WorldController@countryStates');
Route::get('countries/{countryCode}/states/{stateCode}/cities', 'Backend\WorldController@stateCities');
Route::get('events'                     , 'Backend\EventController@datatable')->name('api.events.datable');
Route::get('events/{event}/packages/'   , 'Backend\EventController@packages')->name('api.events.packages');

Route::get('additionals', 'Backend\AdditionalController@list')->name('api.additionals.list');
Route::get('/', 'Frontend\HomeController@index')->name('api.index');