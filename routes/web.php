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

Route::auth();

Route::view('/', 'home');
Route::get('taxa/{taxon}', 'TaxaController@show');

Route::middleware('auth')->group(function () {
    Route::get('contributor/field-observations/{id}/edit', 'Contributor\FieldObservationsController@edit')->name('contributor.field-observations.edit');
    Route::get('contributor/field-observations/new', 'Contributor\FieldObservationsController@create')->name('contributor.field-observations.create');
    Route::get('contributor/field-observations', 'Contributor\FieldObservationsController@index')->name('contributor.field-observations.index');
    Route::post('contributor/field-observations', 'Contributor\FieldObservationsController@store')->name('contributor.field-observations.store');
    Route::put('contributor/field-observations/{id}', 'Contributor\FieldObservationsController@update')->name('contributor.field-observations.update');

    Route::get('contributor', 'Contributor\DashboardController@index')->name('contributor.index');
});
