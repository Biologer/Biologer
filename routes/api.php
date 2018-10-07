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

Route::get('groups/{group}/taxa', 'GroupTaxaController@index')
    ->name('api.groups.taxa.index');

Route::middleware('auth:api')->group(function () {
    // Uploads
    Route::post('uploads/photos', 'PhotoUploadsController@store')
        ->name('api.photo-uploads.store');

    Route::delete('uploads/photos/{photo}', 'PhotoUploadsController@destroy')
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
    Route::get('field-observations', 'FieldObservationsController@index')
        ->middleware('role:admin')
        ->middleware('can:list,App\FieldObservation')
        ->name('api.field-observations.index');

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

    // Field observation exports
    Route::post('field-observation-exports', 'FieldObservationExportsController@store')
        ->name('api.field-observation-exports.store');

    if (config('features.importing')) {
        // Field observations import
        Route::post('field-observation-imports', 'FieldObservationImportsController@store')
            ->name('api.field-observation-imports.store');

        Route::get('field-observation-imports/{import}', 'FieldObservationImportsController@show')
            ->name('api.field-observation-imports.show');

        Route::get('field-observation-imports/{import}/errors', 'FieldObservationImportsController@errors')
            ->name('api.field-observation-imports.errors');
    }

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

    // Taxa
    Route::get('view-groups', 'ViewGroupsController@index')
        ->name('api.view-groups.index');

    Route::post('view-groups', 'ViewGroupsController@store')
        ->middleware('can:create,App\ViewGroup')
        ->name('api.view-groups.store');

    Route::get('view-groups/{group}', 'ViewGroupsController@show')
        ->name('api.view-groups.show');

    Route::put('view-groups/{group}', 'ViewGroupsController@update')
        ->middleware('can:update,group')
        ->name('api.view-groups.update');

    Route::delete('view-groups/{group}', 'ViewGroupsController@destroy')
        ->middleware('can:delete,group')
        ->name('api.view-groups.destroy');

    Route::get('exports/{export}', 'ExportsController@show')
        ->name('api.exports.show');

    // Announcements
    Route::get('announcements', 'AnnouncementsController@index')
        ->name('api.announcements.index');

    Route::get('announcements/{announcement}', 'AnnouncementsController@show')
        ->name('api.announcements.show');

    Route::post('announcements', 'AnnouncementsController@store')
        ->middleware('can:create,App\Announcement')
        ->name('api.announcements.store');

    Route::put('announcements/{announcement}', 'AnnouncementsController@update')
        ->middleware('can:update,announcement')
        ->name('api.announcements.update');

    Route::delete('announcements/{announcement}', 'AnnouncementsController@destroy')
        ->middleware('can:delete,announcement')
        ->name('api.announcements.destroy');

    Route::post('read-announcements', 'ReadAnnouncementsController@store')
        ->name('api.read-announcements.store');

    // My
    Route::prefix('my')->namespace('My')->group(function () {
        Route::get('field-observations', 'FieldObservationsController@index')
            ->name('api.my.field-observations.index');

        Route::post('field-observations/export', 'FieldObservationExportsController@store')
            ->name('api.my.field-observation-exports.store');

        Route::get('profile', 'ProfileController@show')
            ->name('api.my.profile.show');
    });

    Route::prefix('curator')->namespace('Curator')->group(function () {
        Route::get('pending-observations', 'PendingObservationsController@index')
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.pending-observations.index');

        Route::post('pending-observations/export', 'PendingObservationExportsController@store')
            ->name('api.curator.pending-observation-exports.store');

        Route::get('approved-observations', 'ApprovedObservationsController@index')
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.approved-observations.index');

        Route::post('approved-observations/export', 'ApprovedObservationExportsController@store')
            ->name('api.curator.approved-observation-exports.store');

        Route::get('unidentifiable-observations', 'UnidentifiableObservationsController@index')
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.unidentifiable-observations.index');

        Route::post('unidentifiable-observations/export', 'UnidentifiableObservationExportsController@store')
            ->name('api.curator.unidentifiable-observation-exports.store');
    });

    Route::prefix('autocomplete')->namespace('Autocomplete')->group(function () {
        Route::get('users', 'UsersController@index')
            ->middleware('role:admin,curator')
            ->name('api.autocomplete.users.index');
    });
});
