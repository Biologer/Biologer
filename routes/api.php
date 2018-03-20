<?php

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

Route::middleware('auth:api')->group(function () {
    // Uploads
    Route::post('uploads/photos', 'PhotoUploadsController@store')
        ->name('api.photo-uploads.store');

    Route::delete('uploads/photos', 'PhotoUploadsController@destroy')
        ->name('api.photo-uploads.destroy');

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
    Route::post('approved-field-observations/batch', 'ApprovedFieldObservationsBatchController@store')
        ->name('api.approved-field-observations-batch.store');

    // Unidentifiable field observations
    Route::post('unidentifiable-field-observations/batch', 'UnidentifiableFieldObservationsBatchController@store')
        ->name('api.unidentifiable-field-observations-batch.store');

    // Unidentifiable field observations
    Route::post('pending-field-observations/batch', 'PendingFieldObservationsBatchController@store')
        ->name('api.pending-field-observations-batch.store');

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
    });

    Route::prefix('curator')->namespace('Curator')->group(function () {
        Route::get('pending-observations', 'PendingObservationsController@index')
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.pending-observations.index');

        Route::get('approved-observations', 'ApprovedObservationsController@index')
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.approved-observations.index');

        Route::get('unidentifiable-observations', 'UnidentifiableObservationsController@index')
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.unidentifiable-observations.index');
    });
});
