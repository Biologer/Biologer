<?php

namespace Tests\Feature;

use App\User;
use App\Taxon;
use Tests\TestCase;
use Tests\ObservationFactory;
use Illuminate\Support\Facades\Storage;
use Box\Spout\Common\Helper\EncodingHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exports\FieldObservations\ContributorFieldObservationsCustomExport;

class ExportDownloadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_download_their_export()
    {
        $this->actingAs($user = factory(User::class)->create());
        $export = $this->performExportFor($user);

        $response = $this->get("exports/{$export->id}/download");

        $response->assertSuccessful();

        $this->assertEquals(
            EncodingHelper::BOM_UTF8
            ."Taxon\n"
            .'"Test taxon"'."\n",
            $response->streamedContent()
        );
    }

    /** @test */
    public function guest_gets_unauthorized_response()
    {
        $this->actingAs($user = factory(User::class)->create());
        $export = $this->performExportFor($user);

        $this->app['auth']->logOut();
        $response = $this->get("exports/{$export->id}/download");

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_cannot_download_other_users_export()
    {
        $this->actingAs($user = factory(User::class)->create());
        $export = $this->performExportFor($user);

        $this->actingAs(factory(User::class)->create());
        $response = $this->get("exports/{$export->id}/download");

        $response->assertNotFound();
    }

    private function performExportFor(User $user)
    {
        Storage::fake('local');
        ObservationFactory::createFieldObservation([
            'created_by_id' => $user,
            'taxon_id' => factory(Taxon::class)->create(['name' => 'Test taxon']),
        ]);

        return tap(ContributorFieldObservationsCustomExport::create(['taxon'], [], true))->perform();
    }
}
