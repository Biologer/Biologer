<?php

use App\Http\Controllers\Api\AdminTaxonExportsController;
use App\Http\Controllers\Api\AnnouncementsController;
use App\Http\Controllers\Api\ApprovedFieldObservationsBatchController;
use App\Http\Controllers\Api\Autocomplete\PublicationsController as AutocompletePublicationsController;
use App\Http\Controllers\Api\Autocomplete\UsersController as AutocompleteUsersController;
use App\Http\Controllers\Api\CancelledImportsController;
use App\Http\Controllers\Api\Curator\ApprovedObservationExportsController;
use App\Http\Controllers\Api\Curator\ApprovedObservationsController;
use App\Http\Controllers\Api\Curator\PendingObservationExportsController;
use App\Http\Controllers\Api\Curator\PendingObservationsController;
use App\Http\Controllers\Api\Curator\UnidentifiableObservationExportsController;
use App\Http\Controllers\Api\Curator\UnidentifiableObservationsController;
use App\Http\Controllers\Api\ElevationController;
use App\Http\Controllers\Api\ExportsController;
use App\Http\Controllers\Api\FieldObservationExportsController;
use App\Http\Controllers\Api\FieldObservationImportsController;
use App\Http\Controllers\Api\FieldObservationsController;
use App\Http\Controllers\Api\GroupTaxaController;
use App\Http\Controllers\Api\LiteratureObservationExportsController;
use App\Http\Controllers\Api\LiteratureObservationImportsController;
use App\Http\Controllers\Api\LiteratureObservationsController;
use App\Http\Controllers\Api\My\FieldObservationExportsController as MyFieldObservationExportsController;
use App\Http\Controllers\Api\My\FieldObservationsController as MyFieldObservationsController;
use App\Http\Controllers\Api\My\ProfileController;
use App\Http\Controllers\Api\My\ReadNotificationsBatchController;
use App\Http\Controllers\Api\My\UnreadNotificationsController;
use App\Http\Controllers\Api\ObservationTypesController;
use App\Http\Controllers\Api\PendingFieldObservationsBatchController;
use App\Http\Controllers\Api\PhotoUploadsController;
use App\Http\Controllers\Api\PublicationAttachmentsController;
use App\Http\Controllers\Api\PublicationsController;
use App\Http\Controllers\Api\PublicFieldObservationExportsController;
use App\Http\Controllers\Api\PublicFieldObservationsController;
use App\Http\Controllers\Api\ReadAnnouncementsController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TaxaController;
use App\Http\Controllers\Api\TaxonExportsController;
use App\Http\Controllers\Api\TaxonomyController;
use App\Http\Controllers\Api\TaxonPublicPhotosController;
use App\Http\Controllers\Api\UnidentifiableFieldObservationsBatchController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ViewGroupExportsController;
use App\Http\Controllers\Api\ViewGroupsController;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [RegisterController::class, 'store']);

Route::get('groups/{group}/taxa', [GroupTaxaController::class, 'index'])
    ->name('api.groups.taxa.index');

Route::get('taxa/{taxon}/public-photos', [TaxonPublicPhotosController::class, 'index'])
    ->name('api.taxa.public-photos.index');

Route::post('taxonomy/sync', [TaxonomyController::class, 'sync'])
    ->name('api.taxonomy.sync');

Route::post('taxonomy/remove', [TaxonomyController::class, 'remove'])
    ->name('api.taxonomy.remove');

Route::post('taxonomy/deselect', [TaxonomyController::class, 'deselect'])
    ->name('api.taxonomy.deselect');

Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::post('elevation', ElevationController::class);

    // Uploads
    Route::post('uploads/photos', [PhotoUploadsController::class, 'store'])
        ->name('api.photo-uploads.store');

    Route::delete('uploads/photos/{photo}', [PhotoUploadsController::class, 'destroy'])
        ->name('api.photo-uploads.destroy');

    // Taxa
    Route::get('taxa', [TaxaController::class, 'index'])
        ->withoutMiddleware('verified')
        ->name('api.taxa.index');

    Route::post('taxa', [TaxaController::class, 'store'])
        ->name('api.taxa.store');

    Route::get('taxa/{taxon}', [TaxaController::class, 'show'])
        ->withoutMiddleware('verified')
        ->name('api.taxa.show');

    Route::put('taxa/{taxon}', [TaxaController::class, 'update'])
        ->middleware('can:update,taxon')
        ->name('api.taxa.update');

    Route::delete('taxa/{taxon}', [TaxaController::class, 'destroy'])
        ->middleware('can:delete,taxon')
        ->name('api.taxa.destroy');

    Route::get('observation-types', [ObservationTypesController::class, 'index'])
        ->withoutMiddleware('verified')
        ->name('api.observation-types.index');

    // Field observations
    Route::get('field-observations', [FieldObservationsController::class, 'index'])
        ->middleware('can:list,App\FieldObservation')
        ->name('api.field-observations.index');

    Route::post('field-observations', [FieldObservationsController::class, 'store'])
        ->name('api.field-observations.store');

    Route::get('field-observations/{fieldObservation}', [FieldObservationsController::class, 'show'])
        ->middleware('can:view,fieldObservation')
        ->name('api.field-observations.show');

    Route::put('field-observations/{fieldObservation}', [FieldObservationsController::class, 'update'])
        ->middleware('can:update,fieldObservation')
        ->name('api.field-observations.update');

    Route::delete('field-observations/{fieldObservation}', [FieldObservationsController::class, 'destroy'])
        ->middleware('can:delete,fieldObservation')
        ->name('api.field-observations.destroy');

    // Field observation exports
    Route::post('field-observation-exports', [FieldObservationExportsController::class, 'store'])
        ->name('api.field-observation-exports.store');

    // Public field observations
    Route::get('public-field-observations', [PublicFieldObservationsController::class, 'index'])
        ->name('api.public-field-observations.index');

    // Public field observation exports
    Route::post('public-field-observation-exports', [PublicFieldObservationExportsController::class, 'store'])
        ->name('api.public-field-observation-exports.store');

    Route::post('cancelled-imports', [CancelledImportsController::class, 'store'])
        ->name('api.cancelled-imports.store');

    // Field observations import
    Route::post('field-observation-imports', [FieldObservationImportsController::class, 'store'])
        ->name('api.field-observation-imports.store');

    Route::get('field-observation-imports/{import}', [FieldObservationImportsController::class, 'show'])
        ->name('api.field-observation-imports.show');

    Route::get('field-observation-imports/{import}/errors', [FieldObservationImportsController::class, 'errors'])
        ->name('api.field-observation-imports.errors');

    // Approved field observations
    Route::post('approved-field-observations/batch', [ApprovedFieldObservationsBatchController::class, 'store'])
        ->name('api.approved-field-observations-batch.store');

    // Unidentifiable field observations
    Route::post('unidentifiable-field-observations/batch', [UnidentifiableFieldObservationsBatchController::class, 'store'])
        ->name('api.unidentifiable-field-observations-batch.store');

    // Unidentifiable field observations
    Route::post('pending-field-observations/batch', [PendingFieldObservationsBatchController::class, 'store'])
        ->name('api.pending-field-observations-batch.store');

    // Users
    Route::get('users', [UsersController::class, 'index'])
        ->middleware('can:list,App\User')
        ->name('api.users.index');

    Route::get('users/{user}', [UsersController::class, 'show'])
        ->middleware('can:view,user')
        ->name('api.users.show');

    Route::put('users/{user}', [UsersController::class, 'update'])
        ->middleware('can:update,user')
        ->name('api.users.update');

    Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->middleware('can:delete,user')
        ->name('api.users.destroy');

    // View groups
    Route::get('view-groups', [ViewGroupsController::class, 'index'])
        ->withoutMiddleware('verified')
        ->name('api.view-groups.index');

    Route::post('view-groups', [ViewGroupsController::class, 'store'])
        ->middleware('can:create,App\ViewGroup')
        ->name('api.view-groups.store');

    Route::get('view-groups/{group}', [ViewGroupsController::class, 'show'])
        ->withoutMiddleware('verified')
        ->name('api.view-groups.show');

    Route::put('view-groups/{group}', [ViewGroupsController::class, 'update'])
        ->middleware('can:update,group')
        ->name('api.view-groups.update');

    Route::delete('view-groups/{group}', [ViewGroupsController::class, 'destroy'])
        ->middleware('can:delete,group')
        ->name('api.view-groups.destroy');

    // View groups export
    Route::post('group-exports', [ViewGroupExportsController::class, 'store'])
        ->name('api.group-exports.store');

    // Taxa export
    Route::post('taxon-exports', [TaxonExportsController::class, 'store'])
        ->name('api.taxon-exports.store');

    Route::post('admin-taxon-exports', [AdminTaxonExportsController::class, 'store'])
        ->name('api.admin-taxon-exports.store');

    Route::get('exports/{export}', [ExportsController::class, 'show'])
        ->name('api.exports.show');

    // Announcements
    Route::get('announcements', [AnnouncementsController::class, 'index'])
        ->withoutMiddleware('verified')
        ->name('api.announcements.index');

    Route::get('announcements/{announcement}', [AnnouncementsController::class, 'show'])
        ->withoutMiddleware('verified')
        ->name('api.announcements.show');

    Route::post('announcements', [AnnouncementsController::class, 'store'])
        ->middleware('can:create,App\Announcement')
        ->name('api.announcements.store');

    Route::put('announcements/{announcement}', [AnnouncementsController::class, 'update'])
        ->middleware('can:update,announcement')
        ->name('api.announcements.update');

    Route::delete('announcements/{announcement}', [AnnouncementsController::class, 'destroy'])
        ->middleware('can:delete,announcement')
        ->name('api.announcements.destroy');

    Route::post('read-announcements', [ReadAnnouncementsController::class, 'store'])
        ->withoutMiddleware('verified')
        ->name('api.read-announcements.store');

    // Publication
    Route::get('publications', [PublicationsController::class, 'index'])
        ->middleware('can:list,App\Publication')
        ->name('api.publications.index');

    Route::post('publications', [PublicationsController::class, 'store'])
        ->middleware('can:create,App\Publication')
        ->name('api.publications.store');

    Route::put('publications/{publication}', [PublicationsController::class, 'update'])
        ->middleware('can:update,publication')
        ->name('api.publications.update');

    Route::delete('publications/{publication}', [PublicationsController::class, 'destroy'])
        ->middleware('can:delete,publication')
        ->name('api.publications.destroy');

    Route::post('publication-attachments', [PublicationAttachmentsController::class, 'store'])
        ->middleware('can:create,App\PublicationAttachment')
        ->name('api.publication-attachments.store');

    Route::delete('publication-attachments/{publicationAttachment}', [PublicationAttachmentsController::class, 'destroy'])
        ->middleware('can:delete,publicationAttachment')
        ->name('api.publication-attachments.destroy');

    // Literature observations
    Route::get('literature-observations', [LiteratureObservationsController::class, 'index'])
        ->middleware('can:list,App\LiteratureObservation')
        ->name('api.literature-observations.index');

    Route::post('literature-observations', [LiteratureObservationsController::class, 'store'])
        ->middleware('can:create,App\LiteratureObservation')
        ->name('api.literature-observations.store');

    Route::get('literature-observations/{literatureObservation}', [LiteratureObservationsController::class, 'show'])
        ->middleware('can:view,literatureObservation')
        ->name('api.literature-observations.show');

    Route::put('literature-observations/{literatureObservation}', [LiteratureObservationsController::class, 'update'])
        ->middleware('can:update,literatureObservation')
        ->name('api.literature-observations.update');

    Route::delete('literature-observations/{literatureObservation}', [LiteratureObservationsController::class, 'destroy'])
        ->middleware('can:delete,literatureObservation')
        ->name('api.literature-observations.destroy');

    // Literature observation exports
    Route::post('literature-observation-exports', [LiteratureObservationExportsController::class, 'store'])
        ->name('api.literature-observation-exports.store');

    // Literature observations import
    Route::post('literature-observation-imports', [LiteratureObservationImportsController::class, 'store'])
        ->name('api.literature-observation-imports.store');

    Route::get('literature-observation-imports/{import}', [LiteratureObservationImportsController::class, 'show'])
        ->name('api.literature-observation-imports.show');

    Route::get('literature-observation-imports/{import}/errors', [LiteratureObservationImportsController::class, 'errors'])
        ->name('api.literature-observation-imports.errors');

    // My
    Route::prefix('my')->group(function () {
        Route::get('field-observations', [MyFieldObservationsController::class, 'index'])
            ->name('api.my.field-observations.index');

        Route::post('field-observations/export', [MyFieldObservationExportsController::class, 'store'])
            ->name('api.my.field-observation-exports.store');

        Route::get('profile', [ProfileController::class, 'show'])
            ->withoutMiddleware('verified')
            ->name('api.my.profile.show');

        Route::post('read-notifications/batch', [ReadNotificationsBatchController::class, 'store'])
            ->withoutMiddleware('verified')
            ->name('api.my.read-notifications-batch.store');

        Route::get('unread-notifications', [UnreadNotificationsController::class, 'index'])
            ->withoutMiddleware('verified')
            ->name('api.my.unread-notifications.index');
    });

    Route::prefix('curator')->group(function () {
        Route::get('pending-observations', [PendingObservationsController::class, 'index'])
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.pending-observations.index');

        Route::post('pending-observations/export', [PendingObservationExportsController::class, 'store'])
            ->name('api.curator.pending-observation-exports.store');

        Route::get('approved-observations', [ApprovedObservationsController::class, 'index'])
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.approved-observations.index');

        Route::post('approved-observations/export', [ApprovedObservationExportsController::class, 'store'])
            ->name('api.curator.approved-observation-exports.store');

        Route::get('unidentifiable-observations', [UnidentifiableObservationsController::class, 'index'])
            ->middleware('can:list,App\FieldObservation')
            ->name('api.curator.unidentifiable-observations.index');

        Route::post('unidentifiable-observations/export', [UnidentifiableObservationExportsController::class, 'store'])
            ->name('api.curator.unidentifiable-observation-exports.store');
    });

    Route::prefix('autocomplete')->group(function () {
        Route::get('users', [AutocompleteUsersController::class, 'index'])
            ->middleware('role:admin,curator')
            ->name('api.autocomplete.users.index');

        Route::get('publications', [AutocompletePublicationsController::class, 'index'])
            ->middleware('role:admin,curator')
            ->name('api.autocomplete.publications.index');
    });
});
