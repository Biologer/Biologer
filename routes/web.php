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

Route::prefix(LaravelLocalization::setLocale())->middleware([
    'localeCookieRedirect', 'localizationRedirect', 'localeViewPath',
])->group(function () {
    Route::auth();

    Route::get('/', 'HomeController@index');
    Route::get('taxa/{taxon}', 'TaxaController@show');

    Route::view('pages/sponsors', 'pages.sponsors')->name('pages.sponsors');

    Route::middleware('guest')->group(function () {
        Route::get('verify/{email}', 'Auth\VerificationController@show')->name('auth.verify.show');
        Route::get('verify/token/{verificationToken}', 'Auth\VerificationController@verify')->name('auth.verify.verify');
        Route::post('verify/resend', 'Auth\VerificationController@resend')->name('auth.verify.resend');
    });

    Route::middleware('auth')->group(function () {
        Route::prefix('contributor')->namespace('Contributor')->name('contributor.')->group(function () {
            Route::get('/', 'DashboardController@index')
                ->name('index');

            Route::get('preferences', 'PreferencesController@index')
                ->name('preferences.index');

            Route::patch('preferences', 'PreferencesController@update')
                ->name('preferences.update');

            Route::get('field-observations', 'FieldObservationsController@index')
                ->name('field-observations.index');

            Route::get('field-observations/new', 'FieldObservationsController@create')
                ->name('field-observations.create');

            Route::get('field-observations/{fieldObservation}/edit', 'FieldObservationsController@edit')
                ->middleware('can:update,fieldObservation')
                ->name('field-observations.edit');
        });

        Route::prefix('curator')->namespace('Curator')->name('curator.')->group(function () {
            Route::get('pending-observations', 'PendingObservationsController@index')
                ->middleware('can:list,App\FieldObservation')
                ->name('pending-observations.index');

            Route::get('pending-observations/{pendingObservation}/edit', 'PendingObservationsController@edit')
                ->name('pending-observations.edit');
        });

        Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
            Route::get('taxa', 'TaxaController@index')
                ->name('taxa.index');

            Route::get('taxa/{taxon}/edit', 'TaxaController@edit')
                ->middleware('can:update,taxon')
                ->name('taxa.edit');

            Route::get('taxa/new', 'TaxaController@create')
                ->middleware('role:admin,curator')
                ->name('taxa.create');

            Route::get('users', 'UsersController@index')
                ->middleware('can:list,App\User')
                ->name('users.index');

            Route::get('users/{user}/edit', 'UsersController@edit')
                ->middleware('can:update,user')
                ->name('users.edit');

            Route::put('users/{user}', 'UsersController@update')
                ->middleware('can:update,user')
                ->name('users.update');
        });
    });
});
