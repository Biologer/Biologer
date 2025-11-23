<?php

namespace Tests\Feature;

use App\Exports\FieldObservations\ContributorFieldObservationsCustomExport;
use App\Models\Taxon;
use App\Models\User;
use Box\Spout\Common\Helper\EncodingHelper;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\ObservationFactory;
use Tests\TestCase;

class ExportDownloadTest extends TestCase
{
    #[Test]
    public function authenticated_user_can_download_their_export()
    {
        $this->actingAs($user = User::factory()->create());
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

    #[Test]
    public function guest_gets_unauthorized_response()
    {
        $this->actingAs($user = User::factory()->create());
        $export = $this->performExportFor($user);

        $this->app['auth']->logOut();
        $response = $this->get("exports/{$export->id}/download");

        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_user_cannot_download_other_users_export()
    {
        $this->actingAs($user = User::factory()->create());
        $export = $this->performExportFor($user);

        $this->actingAs(User::factory()->create());
        $response = $this->get("exports/{$export->id}/download");

        $response->assertNotFound();
    }

    private function performExportFor(User $user)
    {
        Storage::fake('local');
        ObservationFactory::createFieldObservation([
            'created_by_id' => $user,
            'taxon_id' => Taxon::factory()->create(['name' => 'Test taxon']),
        ]);

        return tap(ContributorFieldObservationsCustomExport::create(['taxon'], [], true))->perform();
    }
}
