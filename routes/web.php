<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('taxa/{taxon}', 'TaxaController@show');

Route::post('contributor/field-observations', 'Contributor\FieldObservationsController@store')->name('field-observations.store');
Route::get('contributor/field-observations/new', 'Contributor\FieldObservationsController@create')->name('field-observations.create');

Route::get('login', function () {

})->name('login');

Route::get('/', function () {
    return 'OK';
});
