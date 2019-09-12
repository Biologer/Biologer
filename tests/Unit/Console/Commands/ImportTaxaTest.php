<?php

namespace Tests\Unit\Console\Commands;

use App\ConservationLegislation;
use App\RedList;
use App\Taxon;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ImportTaxaTest extends TestCase
{
    /** @test */
    public function can_import_taxa_from_a_csv_file()
    {
        $redList = factory(RedList::class)->create();
        $conservationLegislation = factory(ConservationLegislation::class)->create();

        $path = $this->createTempFile(
            "kingdom,species,allochthonous,invasive,restricted,fe_id,fe_old_id,name_en,red_list_{$redList->slug},conservation_legislation_{$conservationLegislation->slug}\n".
            'Animalia,Cerambyx cerdo,X,X,,feId,123,great capricorn beetle,EX,X'
        );

        $this->artisan("import:taxa {$path}");

        $this->assertDatabaseHas('taxa', ['name' => 'Animalia', 'rank' => 'kingdom']);
        $this->assertDatabaseHas('taxa', [
            'name' => 'Cerambyx cerdo',
            'allochthonous' => true,
            'invasive' => true,
            'restricted' => false,
            'fe_id' => 'feId',
            'fe_old_id' => '123',
            'rank' => 'species',
        ]);
        $this->assertDatabaseHas('taxon_translations', [
            'locale' => 'en',
            'native_name' => 'great capricorn beetle',
        ]);

        $taxon = Taxon::where(['name' => 'Cerambyx cerdo', 'rank' => 'species'])->first();
        $taxonRedLists = $taxon->redLists;
        $taxonConservationLegislations = $taxon->conservationLegislations;

        $this->assertTrue($taxonRedLists->contains($redList));
        $this->assertCount(1, $taxonRedLists);
        $this->assertEquals('EX', $taxonRedLists->first()->pivot->category);
        $this->assertTrue($taxonConservationLegislations->contains($conservationLegislation));
    }

    /** @test */
    public function can_handle_duplicate_names_in_same_tree()
    {
        $root = factory(Taxon::class)->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        factory(Taxon::class)->create(['name' => 'Cerambyx cerdo', 'parent_id' => $root->id, 'rank' => 'species']);

        $path = $this->createTempFile(
            "kingdom,species,allochthonous,invasive,restricted,fe_id,fe_old_id,name_en\n".
            'Animalia,Cerambyx cerdo,X,X,,feId,123,great capricorn beetle'
        );

        $this->artisan("import:taxa {$path}");

        $taxon = Taxon::where([
            'name' => 'Cerambyx cerdo',
            'allochthonous' => true,
            'invasive' => true,
            'restricted' => false,
            'fe_id' => 'feId',
            'fe_old_id' => '123',
        ])->get();

        $taxon->assertCount(1);
        $taxon = $taxon->first();
        $this->assertEquals('Animalia', $taxon->parent->name);
        $this->assertEquals('Cerambyx cerdo', $taxon->name);
    }

    /** @test */
    public function can_handle_duplicate_names_in_different_trees()
    {
        $root = factory(Taxon::class)->create(['name' => 'Animalia', 'parent_id' => null, 'rank' => 'kingdom']);
        $animalSpecies = factory(Taxon::class)->create([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $root->id,
            'rank' => 'species',
            'allochthonous' => false,
            'invasive' => false,
            'restricted' => false,
        ]);
        $otherRoot = factory(Taxon::class)->create(['name' => 'Plantae', 'parent_id' => null, 'rank' => 'kingdom']);
        $plantSpecies = factory(Taxon::class)->create([
            'name' => 'Cerambyx cerdo',
            'parent_id' => $otherRoot->id,
            'rank' => 'species',
            'allochthonous' => false,
            'invasive' => false,
            'restricted' => false,
        ]);

        $path = $this->createTempFile(
            "kingdom,species,allochthonous,invasive,restricted,fe_id,fe_old_id,name_en\n".
            'Animalia,Cerambyx cerdo,X,X,,feId,123,great capricorn beetle'
        );

        $this->artisan("import:taxa {$path}");

        $animalSpecies->refresh();
        $plantSpecies->refresh();

        $this->assertTrue($animalSpecies->allochthonous);
        $this->assertTrue($animalSpecies->invasive);
        $this->assertFalse($animalSpecies->restricted);
        $this->assertEquals('feId', $animalSpecies->fe_id);
        $this->assertEquals('123', $animalSpecies->fe_old_id);
        $this->assertEquals('great capricorn beetle', $animalSpecies->translateOrNew('en')->native_name);

        $this->assertFalse($plantSpecies->allochthonous);
        $this->assertFalse($plantSpecies->invasive);
        $this->assertFalse($plantSpecies->restricted);
        $this->assertEmpty($plantSpecies->fe_id);
        $this->assertEmpty($plantSpecies->fe_old_id);
        $this->assertNull($plantSpecies->translateOrNew('en')->native_name);
    }

    /** @test */
    public function can_compose_name_from_fragments()
    {
        $path = $this->createTempFile("genus,species,subspecies\nCerambyx,cerdo,cerdo");

        $this->artisan('import:taxa', [
            'path' => $path,
            '--compose-species-name' => true,
        ]);

        $this->assertDatabaseHas('taxa', ['name' => 'Cerambyx', 'rank' => 'genus']);
        $this->assertDatabaseHas('taxa', ['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $this->assertDatabaseHas('taxa', ['name' => 'Cerambyx cerdo cerdo', 'rank' => 'subspecies']);
    }

    /** @test */
    public function can_chunk_reading_lines_from_csv_file()
    {
        $path = $this->createTempFile("genus,species,subspecies\nCerambyx,cerdo,cerdo\nCerambyx,scopolii,");

        $this->artisan('import:taxa', [
            'path' => $path,
            '--compose-species-name' => true,
            '--chunked' => true,
            '--chunk-size' => 1,
        ]);

        $this->assertDatabaseHas('taxa', ['name' => 'Cerambyx', 'rank' => 'genus']);
        $this->assertDatabaseHas('taxa', ['name' => 'Cerambyx cerdo', 'rank' => 'species']);
        $this->assertDatabaseHas('taxa', ['name' => 'Cerambyx cerdo cerdo', 'rank' => 'subspecies']);
        $this->assertDatabaseHas('taxa', ['name' => 'Cerambyx scopolii', 'rank' => 'species']);
    }

    /** @test */
    public function user_can_be_attributed_as_creator_of_the_tree()
    {
        $user = factory(User::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $path = $this->createTempFile("species\nCerambyx cerdo");

        $this->artisan('import:taxa', [
            'path' => $path,
            '--user' => $user->id,
        ]);

        $taxon = Taxon::where(['name' => 'Cerambyx cerdo', 'rank' => 'species'])->first();

        $taxon->activity->assertCount(1);
        $activity = $taxon->activity->first();
        $this->assertEquals('created', $activity->description);
        $this->assertTrue($activity->causer->is($user));
    }

    public function createTempFile($contents)
    {
        Storage::fake();
        $filename = Str::random().'.csv';

        Storage::put($filename, $contents);

        return Storage::path($filename);
    }
}
