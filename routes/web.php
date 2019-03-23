<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
    Route::auth(['verify' => true]);

    Route::get('/', 'HomeController@index');
    Route::get('taxa/{taxon}', 'TaxaController@show');
    Route::get('groups', 'GroupsController@index')->name('groups.index');
    Route::get('groups/{group}/species/{species}', 'GroupSpeciesController@show')->name('groups.species.show');
    Route::get('groups/{group}/species', 'GroupSpeciesController@index')->name('groups.species.index');

    // About pages
    Route::view('pages/about/about-project', 'pages.about.about-project')->name('pages.about.about-project');
    Route::view('pages/about/project-team', 'pages.about.project-team')->name('pages.about.project-team');
    Route::view('pages/about/organisations', 'pages.about.organisations')->name('pages.about.organisations');
    Route::get('pages/about/local-community', 'AboutPagesController@localCommunity')->name('pages.about.local-community');
    Route::view('pages/about/biodiversity-data', 'pages.about.biodiversity-data')->name('pages.about.biodiversity-data');
    Route::view('pages/about/development-supporters', 'pages.about.development-supporters')->name('pages.about.development-supporters');

    // Legal
    Route::view('pages/privacy-policy', 'pages.privacy-policy')->name('pages.privacy-policy');

    // Licenses
    Route::view('licenses/partially-open-license', 'licenses.partially-open-license')->name('licenses.partially-open-license');
    Route::view('licenses/closed-license', 'licenses.closed-license')->name('licenses.closed-license');
    Route::view('licenses', 'licenses.index')->name('licenses.index');

    Route::get('announcements', 'AnnouncementsController@index')->name('announcements.index');
    Route::get('announcements/{announcement}', 'AnnouncementsController@show')->name('announcements.show');

    Route::middleware(['auth', 'verified'])->group(function () {
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

            if (config('features.importing')) {
                Route::get('field-observations/import', 'FieldObservationsImportController@index')
                    ->name('field-observations-import.index');

                Route::view('field-observations/import/guide', 'contributor.field-observations-import.guide')
                    ->name('field-observations-import.guide');
            }

            Route::get('field-observations/{fieldObservation}', 'FieldObservationsController@show')
                ->middleware('can:view,fieldObservation')
                ->name('field-observations.show');

            Route::get('field-observations/{fieldObservation}/edit', 'FieldObservationsController@edit')
                ->middleware('can:update,fieldObservation')
                ->name('field-observations.edit');
        });

        Route::prefix('curator')->namespace('Curator')->name('curator.')->group(function () {
            Route::get('pending-observations', 'PendingObservationsController@index')
                ->middleware('role:curator,admin')
                ->middleware('can:list,App\FieldObservation')
                ->name('pending-observations.index');

            Route::get('pending-observations/{fieldObservation}/edit', 'PendingObservationsController@edit')
                ->middleware('role:curator,admin')
                ->name('pending-observations.edit');

            Route::get('pending-observations/{fieldObservation}', 'PendingObservationsController@show')
                ->middleware('role:curator,admin')
                ->name('pending-observations.show');

            Route::get('approved-observations', 'ApprovedObservationsController@index')
                ->middleware('role:curator,admin')
                ->middleware('can:list,App\FieldObservation')
                ->name('approved-observations.index');

            Route::get('approved-observations/{approvedObservation}/edit', 'ApprovedObservationsController@edit')
                ->middleware('role:curator,admin')
                ->name('approved-observations.edit');

            Route::get('approved-observations/{fieldObservation}', 'ApprovedObservationsController@show')
                ->middleware('role:curator,admin')
                ->name('approved-observations.show');

            Route::get('unidentifiable-observations', 'UnidentifiableObservationsController@index')
                ->middleware('role:curator,admin')
                ->middleware('can:list,App\FieldObservation')
                ->name('unidentifiable-observations.index');

            Route::get('unidentifiable-observations/{unidentifiableObservation}/edit', 'UnidentifiableObservationsController@edit')
                ->middleware('role:curator,admin')
                ->name('unidentifiable-observations.edit');

            Route::get('unidentifiable-observations/{fieldObservation}', 'UnidentifiableObservationsController@show')
                ->middleware('role:curator,admin')
                ->name('unidentifiable-observations.show');
        });

        Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
            Route::get('field-observations', 'FieldObservationsController@index')
                ->middleware('role:admin')
                ->name('field-observations.index');

            Route::get('field-observations/{fieldObservation}/edit', 'FieldObservationsController@edit')
                ->middleware('role:admin')
                ->name('field-observations.edit');

            Route::get('field-observations/{fieldObservation}', 'FieldObservationsController@show')
                ->middleware('role:admin')
                ->name('field-observations.show');

            Route::get('taxa', 'TaxaController@index')
                ->middleware('role:admin,curator')
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

            Route::get('view-groups', 'ViewGroupsController@index')
                ->middleware('role:admin')
                ->name('view-groups.index');

            Route::get('view-groups/new', 'ViewGroupsController@create')
                ->middleware('can:create,App\ViewGroup')
                ->name('view-groups.create');

            Route::get('view-groups/{group}/edit', 'ViewGroupsController@edit')
                ->middleware('can:update,group')
                ->name('view-groups.edit');

            Route::get('announcements', 'AnnouncementsController@index')
                ->middleware('can:list,App\Announcement')
                ->name('announcements.index');

            Route::get('announcements/new', 'AnnouncementsController@create')
                ->middleware('can:create,App\Announcement')
                ->name('announcements.create');

            Route::get('announcements/{announcement}/edit', 'AnnouncementsController@edit')
                ->middleware('can:update,announcement')
                ->name('announcements.edit');
        });
    });
});

Route::get('exports/{export}/download', 'ExportDownloadController')
    ->middleware(['auth', 'verified'])
    ->name('export-download');
