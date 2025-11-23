<?php

namespace Tests\Unit\Importing;

use PHPUnit\Framework\Attributes\Test;
use App\Import;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\FakeImporter;
use Tests\TestCase;

class ImportingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    /**
     * Make sample valid file.
     *
     * @param  string  $contents
     * @return \Illuminate\Http\Testing\File
     */
    protected function validFile($contents = null)
    {
        $file = File::fake()->create('import.csv');

        $defaultContents = "3,Cerambyx cerdo,Note\n4,Cerambyx scopolii,Other note";

        file_put_contents($file->getPathname(), $contents ?? $defaultContents);

        return $file;
    }

    protected function createImport($type, array $columns = [], $contents = null, $user = null)
    {
        return Import::create([
            'type' => $type,
            'columns' => $columns,
            'path' => $this->validFile($contents)->store('imports'),
            'user_id' => $user ? $user->id : 1,
            'lang' => app()->getLocale(),
        ]);
    }

    #[Test]
    public function it_can_parse_csv_file_and_map_the_columns()
    {
        $import = $this->createImport(FakeImporter::class, ['a', 'b', 'c']);

        $import->makeImporter()->parse();

        $this->assertTrue($import->status()->parsed());
        Storage::assertExists($import->parsedPath());

        $contents = Storage::get($import->parsedPath());
        $this->assertEquals([
            [
                'a' => '3',
                'b' => 'Cerambyx cerdo',
                'c' => 'Note',
            ],
            [
                'a' => '4',
                'b' => 'Cerambyx scopolii',
                'c' => 'Other note',
            ],
        ], json_decode($contents, true));
    }

    #[Test]
    public function it_can_validate_processed_import_and_pass_if_there_are_not_errors()
    {
        $import = $this->createImport(FakeImporter::class, ['a', 'b', 'c']);

        $import->makeImporter()->parse()->validate();

        $this->assertTrue($import->status()->validationPassed());
    }

    #[Test]
    public function it_can_validate_processed_import_and_fail_if_there_are_errors()
    {
        $content = "1,Cerambix cerdo,Note\n2,,Other note\n1,,LastNote";
        $import = $this->createImport(FakeImporter::class, ['a', 'b', 'c'], $content);

        $import->makeImporter()->parse()->validate();

        $this->assertTrue($import->status()->validationFailed());
        Storage::assertExists($import->errorsPath());
        $contents = Storage::get($import->errorsPath());

        $expectedRowNumbers = [1, 2, 3, 3];
        $expectedRowColumns = ['a', 'b', 'a', 'b'];

        foreach (json_decode($contents, true) as $i => $row) {
            $this->assertArrayHasKey('row', $row);
            $this->assertEquals($expectedRowNumbers[$i], $row['row']);
            $this->assertArrayHasKey('error', $row);
            $this->assertStringContainsString($expectedRowColumns[$i], $row['error']);
        }
    }
}
