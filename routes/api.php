<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function() {
    Route::post('uploads', 'UploadsController@store')->name('api.uploads.store');
    Route::delete('uploads', 'UploadsController@destroy')->name('api.uploads.destroy');
    Route::post('field-observations', 'FieldObservationsController@store')->name('api.field-observations.store');
});
Route::get('taxa', 'TaxaController@index')->name('api.taxa.index');
