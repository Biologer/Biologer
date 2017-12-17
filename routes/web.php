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

Route::get('/', 'HomeController@index');
Route::get('taxa/{taxon}', 'TaxaController@show');

Route::middleware('guest')->group(function () {
    Route::get('verify/{email}', 'Auth\VerificationController@show')->name('auth.verify.show');
    Route::get('verify/token/{verificationToken}', 'Auth\VerificationController@verify')->name('auth.verify.verify');
    Route::post('verify/resend', 'Auth\VerificationController@resend')->name('auth.verify.resend');
});

Route::middleware('auth')->group(function () {
    Route::get(
        'contributor', 'Contributor\DashboardController@index'
    )->name('contributor.index');

    Route::get(
        'contributor/field-observations', 'Contributor\FieldObservationsController@index'
    )->name('contributor.field-observations.index');
    Route::get(
        'contributor/field-observations/new', 'Contributor\FieldObservationsController@create'
    )->name('contributor.field-observations.create');
    Route::get(
        'contributor/field-observations/{id}/edit', 'Contributor\FieldObservationsController@edit'
    )->name('contributor.field-observations.edit');

    Route::get(
        'admin/taxa', 'Admin\TaxaController@index'
    )->name('admin.taxa.index');
    Route::get(
        'admin/taxa/{taxon}/edit', 'Admin\TaxaController@edit'
    )->name('admin.taxa.edit');
    Route::get(
        'admin/taxa/new', 'Admin\TaxaController@create'
    )->name('admin.taxa.create');

    Route::get(
        'admin/pending-observations', 'Admin\PendingObservationsController@index'
    )->name('admin.pending-observations.index');
    Route::get(
        'admin/pending-observations/{id}/edit', 'Admin\PendingObservationsController@edit'
    )->name('admin.pending-observations.edit');
});
