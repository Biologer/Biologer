<?php

use App\Http\Controllers\AboutPagesController;
use App\Http\Controllers\Admin\AnnouncementsController as AdminAnnouncementsController;
use App\Http\Controllers\Admin\FieldObservationsController as AdminFieldObservationsController;
use App\Http\Controllers\Admin\LiteratureObservationsController;
use App\Http\Controllers\Admin\LiteratureObservationsImportController;
use App\Http\Controllers\Admin\PublicationsController;
use App\Http\Controllers\Admin\TaxaController as AdminTaxaController;
use App\Http\Controllers\Admin\TaxonomyController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ViewGroupsController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CitationController;
use App\Http\Controllers\Contributor\DashboardController;
use App\Http\Controllers\Contributor\FieldObservationsController;
use App\Http\Controllers\Contributor\FieldObservationsImportController;
use App\Http\Controllers\Contributor\PublicFieldObservationsController;
use App\Http\Controllers\Contributor\PublicLiteratureObservationsController;
use App\Http\Controllers\Contributor\TimedCountObservationsController;
use App\Http\Controllers\Curator\ApprovedObservationsController;
use App\Http\Controllers\Curator\PendingObservationsController;
use App\Http\Controllers\Curator\UnidentifiableObservationsController;
use App\Http\Controllers\ExportDownloadController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\GroupSpeciesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotosController;
use App\Http\Controllers\Preferences\AccountPreferencesController;
use App\Http\Controllers\Preferences\DataEntryPreferencesController;
use App\Http\Controllers\Preferences\GeneralPreferencesController;
use App\Http\Controllers\Preferences\LicensePreferencesController;
use App\Http\Controllers\Preferences\NotificationsPreferencesController;
use App\Http\Controllers\Preferences\TokenPreferencesController;
use App\Http\Controllers\TaxaController;
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

Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::get('exports/{export}/download', ExportDownloadController::class)
    ->middleware(['auth', 'verified'])
    ->name('export-download');

Route::get('photos/{photo}/file', [PhotosController::class, 'file'])
    ->middleware('auth:web,api')
    ->name('photos.file');
Route::get('photos/{photo}/public', [PhotosController::class, 'public'])
    ->middleware('signed')
    ->name('photos.public');

Route::prefix(LaravelLocalization::setLocale())->middleware([
    'localeCookieRedirect',
    'localizationRedirect',
    'localeViewPath',
    'localizationPreferenceUpdate',
])->group(function () {
    Route::auth(['verify' => false, 'confirm' => false]);
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('taxa/{taxon}', [TaxaController::class, 'show'])->name('taxa.show');
    Route::get('groups', [GroupsController::class, 'index'])->name('groups.index');
    Route::get('groups/{group}/species/{species}', [GroupSpeciesController::class, 'show'])->name('groups.species.show');
    Route::get('groups/{group}/species', [GroupSpeciesController::class, 'index'])->name('groups.species.index');

    // About pages
    Route::view('pages/about/about-project', 'pages.about.about-project')->name('pages.about.about-project');
    Route::get('pages/about/local-community', [AboutPagesController::class, 'localCommunity'])->name('pages.about.local-community');
    Route::get('pages/about/stats', [AboutPagesController::class, 'stats'])->name('pages.about.stats');
    Route::get('pages/about/citation', [CitationController::class, 'index'])->name('pages.about.citation');

    // Taxa for citations
    Route::get('taxa/{taxon}/descendants-curators', [TaxaController::class, 'descendantsCurators'])->name('taxa.descendants.curators');

    // Legal
    Route::get('pages/privacy-policy', [AboutPagesController::class, 'privacyPolicy'])->name('pages.privacy-policy');

    // Licenses
    Route::view('licenses', 'licenses.index')->name('licenses.index');
    Route::view('licenses/partially-open-data-license', 'licenses.partially-open-data-license')->name('licenses.partially-open-data-license');
    Route::view('licenses/temporarily-closed-data-license', 'licenses.temporarily-closed-data-license')->name('licenses.temporarily-closed-data-license');
    Route::view('licenses/closed-data-license', 'licenses.closed-data-license')->name('licenses.closed-data-license');
    Route::view('licenses/partially-open-image-license', 'licenses.partially-open-image-license')->name('licenses.partially-open-image-license');
    Route::view('licenses/closed-image-license', 'licenses.closed-image-license')->name('licenses.closed-image-license');

    Route::get('announcements', [AnnouncementsController::class, 'index'])->name('announcements.index');
    Route::get('announcements/{announcement}', [AnnouncementsController::class, 'show'])->name('announcements.show');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::redirect('/preferences', '/preferences/general')->name('preferences.index');

        Route::prefix('preferences')->name('preferences.')->group(function () {
            Route::get('general', [GeneralPreferencesController::class, 'index'])->name('general');
            Route::patch('general', [GeneralPreferencesController::class, 'update'])->name('general.update');

            Route::get('account', [AccountPreferencesController::class, 'index'])->name('account');
            Route::patch('account/password', [AccountPreferencesController::class, 'changePassword'])->name('account.password');
            Route::patch('account/email', [AccountPreferencesController::class, 'changeEmail'])->name('account.email');
            Route::delete('account', [AccountPreferencesController::class, 'destroy'])
                ->name('account.delete');

            Route::get('license', [LicensePreferencesController::class, 'index'])->name('license');
            Route::patch('license', [LicensePreferencesController::class, 'update'])->name('license.update');

            Route::get('notifications', [NotificationsPreferencesController::class, 'index'])->name('notifications');
            Route::patch('notifications', [NotificationsPreferencesController::class, 'update'])->name('notifications.update');

            Route::patch('data-entry', [DataEntryPreferencesController::class, 'update'])->name('data-entry.update');

            Route::get('token', [TokenPreferencesController::class, 'index'])->name('token');
            Route::post('token/generate', [TokenPreferencesController::class, 'generate'])->name('token.generate');
            Route::post('token/revoke', [TokenPreferencesController::class, 'revoke'])->name('token.revoke');
        });

        Route::prefix('contributor')->name('contributor.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])
                ->name('index');

            Route::get('field-observations', [FieldObservationsController::class, 'index'])
                ->name('field-observations.index');

            Route::get('field-observations/new', [FieldObservationsController::class, 'create'])
                ->name('field-observations.create');

            Route::get('field-observations/import', [FieldObservationsImportController::class, 'index'])
                ->name('field-observations-import.index');

            Route::view('field-observations/import/guide', 'contributor.field-observations-import.guide')
                ->name('field-observations-import.guide');

            Route::get('field-observations/{fieldObservation}', [FieldObservationsController::class, 'show'])
                ->middleware('can:view,fieldObservation')
                ->name('field-observations.show');

            Route::get('field-observations/{fieldObservation}/edit', [FieldObservationsController::class, 'edit'])
                ->middleware('can:update,fieldObservation')
                ->name('field-observations.edit');

            Route::get('public-field-observations', [PublicFieldObservationsController::class, 'index'])
                ->name('public-field-observations.index');

            Route::get('public-field-observations/{fieldObservation}', [PublicFieldObservationsController::class, 'show'])
                ->name('public-field-observations.show');

            Route::get('public-literature-observations', [PublicLiteratureObservationsController::class, 'index'])
                ->name('public-literature-observations.index');

            Route::get('public-literature-observations/{literatureObservation}', [PublicLiteratureObservationsController::class, 'show'])
                ->name('public-literature-observations.show');

            Route::get('timed-count-observations', [TimedCountObservationsController::class, 'index'])
                ->name('timed-count-observations.index');

            Route::get('timed-count-observations/{timedCountObservation}', [TimedCountObservationsController::class, 'show'])
                ->name('timed-count-observations.show');
        });

        Route::prefix('curator')->name('curator.')->group(function () {
            Route::get('pending-observations', [PendingObservationsController::class, 'index'])
                ->middleware('role:curator,admin')
                ->middleware('can:list,App\FieldObservation')
                ->name('pending-observations.index');

            Route::get('pending-observations/{fieldObservation}/edit', [PendingObservationsController::class, 'edit'])
                ->middleware('role:curator,admin')
                ->name('pending-observations.edit');

            Route::get('pending-observations/{fieldObservation}', [PendingObservationsController::class, 'show'])
                ->middleware('role:curator,admin')
                ->name('pending-observations.show');

            Route::get('approved-observations', [ApprovedObservationsController::class, 'index'])
                ->middleware('role:curator,admin')
                ->middleware('can:list,App\FieldObservation')
                ->name('approved-observations.index');

            Route::get('approved-observations/{approvedObservation}/edit', [ApprovedObservationsController::class, 'edit'])
                ->middleware('role:curator,admin')
                ->name('approved-observations.edit');

            Route::get('approved-observations/{fieldObservation}', [ApprovedObservationsController::class, 'show'])
                ->middleware('role:curator,admin')
                ->name('approved-observations.show');

            Route::get('unidentifiable-observations', [UnidentifiableObservationsController::class, 'index'])
                ->middleware('role:curator,admin')
                ->middleware('can:list,App\FieldObservation')
                ->name('unidentifiable-observations.index');

            Route::get('unidentifiable-observations/{unidentifiableObservation}/edit', [UnidentifiableObservationsController::class, 'edit'])
                ->middleware('role:curator,admin')
                ->name('unidentifiable-observations.edit');

            Route::get('unidentifiable-observations/{fieldObservation}', [UnidentifiableObservationsController::class, 'show'])
                ->middleware('role:curator,admin')
                ->name('unidentifiable-observations.show');
        });

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('field-observations', [AdminFieldObservationsController::class, 'index'])
                ->middleware('role:admin')
                ->name('field-observations.index');

            Route::get('field-observations/{fieldObservation}/edit', [AdminFieldObservationsController::class, 'edit'])
                ->middleware('role:admin')
                ->name('field-observations.edit');

            Route::get('field-observations/{fieldObservation}', [AdminFieldObservationsController::class, 'show'])
                ->middleware('role:admin')
                ->name('field-observations.show');

            Route::get('taxa', [AdminTaxaController::class, 'index'])
                ->middleware('role:admin,curator')
                ->name('taxa.index');

            Route::get('taxa/{taxon}/edit', [AdminTaxaController::class, 'edit'])
                ->middleware('can:update,taxon')
                ->name('taxa.edit');

            Route::get('taxa/new', [AdminTaxaController::class, 'create'])
                ->middleware('role:admin,curator')
                ->name('taxa.create');

            Route::get('users', [UsersController::class, 'index'])
                ->middleware('can:list,App\User')
                ->name('users.index');

            Route::get('users/{user}/edit', [UsersController::class, 'edit'])
                ->middleware('can:update,user')
                ->name('users.edit');

            Route::put('users/{user}', [UsersController::class, 'update'])
                ->middleware('can:update,user')
                ->name('users.update');

            Route::get('view-groups', [ViewGroupsController::class, 'index'])
                ->middleware('role:admin')
                ->name('view-groups.index');

            Route::get('view-groups/new', [ViewGroupsController::class, 'create'])
                ->middleware('can:create,App\ViewGroup')
                ->name('view-groups.create');

            Route::get('view-groups/{group}/edit', [ViewGroupsController::class, 'edit'])
                ->middleware('can:update,group')
                ->name('view-groups.edit');

            Route::get('announcements', [AdminAnnouncementsController::class, 'index'])
                ->middleware('can:list,App\Announcement')
                ->name('announcements.index');

            Route::get('announcements/new', [AdminAnnouncementsController::class, 'create'])
                ->middleware('can:create,App\Announcement')
                ->name('announcements.create');

            Route::get('announcements/{announcement}/edit', [AdminAnnouncementsController::class, 'edit'])
                ->middleware('can:update,announcement')
                ->name('announcements.edit');

            Route::get('literature-observations/import', [LiteratureObservationsImportController::class, 'index'])
                ->name('literature-observations-import.index');

            Route::get('literature-observations', [LiteratureObservationsController::class, 'index'])
                ->name('literature-observations.index');

            Route::get('literature-observations/new', [LiteratureObservationsController::class, 'create'])
                ->name('literature-observations.create');

            Route::get('literature-observations/{literatureObservation}/edit', [LiteratureObservationsController::class, 'edit'])
                ->name('literature-observations.edit');

            Route::get('literature-observations/{literatureObservation}', [LiteratureObservationsController::class, 'show'])
                ->name('literature-observations.show');

            Route::get('publications', [PublicationsController::class, 'index'])
                ->middleware('can:list,App\Publication')
                ->name('publications.index');

            Route::get('publications/new', [PublicationsController::class, 'create'])
                ->middleware('can:create,App\Publication')
                ->name('publications.create');

            Route::get('publications/{publication}/edit', [PublicationsController::class, 'edit'])
                ->middleware('can:update,publication')
                ->name('publications.edit');

            Route::get('taxonomy', [TaxonomyController::class, 'index'])
                ->name('taxonomy.index');

            Route::get('taxonomy/check', [TaxonomyController::class, 'check'])
                ->name('taxonomy.check');

            Route::get('taxonomy/connect', [TaxonomyController::class, 'connect'])
                ->name('taxonomy.connect');

            Route::get('taxonomy/disconnect', [TaxonomyController::class, 'disconnect'])
                ->name('taxonomy.disconnect');

            Route::get('taxonomy/sync', [TaxonomyController::class, 'syncTaxon'])
                ->name('taxonomy.sync');
        });
    });
});
