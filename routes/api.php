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

Route::middleware('auth:api')->group(function() {
    // Uploads
    Route::post('uploads', 'UploadsController@store')
        ->name('api.uploads.store');

    Route::delete('uploads', 'UploadsController@destroy')
        ->name('api.uploads.destroy');

    // Taxa
    Route::get('taxa', 'TaxaController@index')
        ->name('api.taxa.index');

    Route::post('taxa', 'TaxaController@store')
        ->name('api.taxa.store');

    Route::get('taxa/{taxon}', 'TaxaController@show')
        ->name('api.taxa.show');

    Route::put('taxa/{taxon}', 'TaxaController@update')
        ->middleware('can:update,taxon')
        ->name('api.taxa.update');

    Route::delete('taxa/{taxon}', 'TaxaController@destroy')
        ->middleware('can:delete,taxon')
        ->name('api.taxa.destroy');

    // Field observations
    Route::post('field-observations', 'FieldObservationsController@store')
        ->name('api.field-observations.store');

    Route::get('field-observations/{fieldObservation}', 'FieldObservationsController@show')
        ->middleware('can:view,fieldObservation')
        ->name('api.field-observations.show');

    Route::put('field-observations/{fieldObservation}', 'FieldObservationsController@update')
        ->middleware('can:update,fieldObservation')
        ->name('api.field-observations.update');

    Route::delete('field-observations/{fieldObservation}', 'FieldObservationsController@destroy')
        ->middleware('can:delete,fieldObservation')
        ->name('api.field-observations.destroy');

    // Approved field observations
    Route::post('approved-field-observations', 'ApprovedFieldObservationsController@store')
        ->name('api.approved-field-observations.store');
    Route::post('approved-field-observations/batch', 'ApprovedFieldObservationsBatchController@store')
        ->name('api.approved-field-observations-batch.store');

    // Unidentifiable field observations
    Route::post('unidentifiable-field-observations/batch', 'UnidentifiableFieldObservationsBatchController@store')
        ->name('api.unidentifiable-field-observations-batch.store');

    // Users
    Route::get('users', 'UsersController@index')
        ->middleware('can:list,App\User')
        ->name('api.users.index');

    Route::get('users/{user}', 'UsersController@show')
        ->middleware('can:view,user')
        ->name('api.users.show');

    Route::put('users/{user}', 'UsersController@update')
        ->middleware('can:update,user')
        ->name('api.users.update');

    Route::delete('users/{user}', 'UsersController@destroy')
        ->middleware('can:delete,user')
        ->name('api.users.destroy');

    // My
    Route::prefix('my')->namespace('My')->group(function () {
        Route::get('field-observations', 'FieldObservationsController@index')
            ->name('api.my.field-observations.index');

        Route::get('pending-observations', 'PendingObservationsController@index')
            ->middleware('can:list,App\FieldObservation')
            ->name('api.my.pending-observations.index');
    });
});
