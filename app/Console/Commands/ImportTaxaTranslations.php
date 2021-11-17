<?php

namespace App\Console\Commands;

use App\Taxon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTaxaTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:taxa:translations
                            {path=taxa-translations.csv : Path to the CSV file we want to import}
                            {--chunked : Chunk reading the CSV file}
                            {--chunk-size=1000 : Size of the chunk when chunking is enabled CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import taxa translations from CSV file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');

        if (! file_exists($path)) {
            $this->error('File at given path doen\'t exist.');

            exit(1);
        }

        DB::transaction(function () use ($path) {
            $this->readFile($path, function ($rows) {
                $this->updateTaxa($rows);
            });
        });

        $this->info('Done!');
    }

    /**
     * Read data from CSV file.
     *
     * @param  string  $path
     * @param  callable  $callback
     * @return array
     */
    private function readFile($path, callable $callback)
    {
        $file = fopen($path, 'r');

        try {
            $this->extractDataFromFile($file, $callback);

            fclose($file);
        } catch (\Exception $e) {
            @fclose($file);

            throw $e;
        }
    }

    /**
     * Extract data from CSV file.
     *
     * @param  resource  $file
     * @param  callable  $callback
     * @return array
     */
    private function extractDataFromFile($file, callable $callback)
    {
        $columns = [];
        $rows = [];
        $rowNumber = 1;

        while (! feof($file)) {
            $row = fgetcsv($file);

            if (empty($row)) {
                break;
            }

            if ($rowNumber === 1) {
                $columns = $row;
            } else {
                $rows[] = $this->mapColumnsOnRow($row, $columns);
            }

            $this->info('Reading row # '.$rowNumber);

            if ($this->option('chunked') && $this->shouldInsertChunk($rowNumber)) {
                $callback($rows);
                $rows = [];
            }

            $rowNumber++;
        }

        $callback($rows);
    }

    /**
     * Map columns using names from the first row.
     *
     * @param  array  $row
     * @param  array  $columns
     * @return array
     */
    private function mapColumnsOnRow($row, $columns)
    {
        $data = [];

        foreach ($columns as $index => $column) {
            $data[$column] = $row[$index] ?? null;
        }

        return $data;
    }

    /**
     * Check if chunk should be inserted.
     *
     * @param  int  $rowNumber
     * @return bool
     */
    private function shouldInsertChunk($rowNumber)
    {
        return ($rowNumber - 1) % $this->option('chunk-size') === 0;
    }

    private function updateTaxa($rows)
    {
        foreach (collect($rows)->chunk(30) as $chunk) {
            $chunk = collect($chunk);

            $taxa = Taxon::whereIn('name', $chunk->pluck('name'))->get();

            foreach ($taxa as $taxon) {
                $names = $chunk->where('name', $taxon->name)->first();

                if (! $names) {
                    continue;
                }

                $taxon->update($this->getTranslations($names));
            }
        }
    }

    /**
     * Extract the rest of information that we'll use to describe the lowest taxon in the row.
     *
     * @param  array  $row
     * @return array
     */
    private function getTranslations($row)
    {
        return [
            'en' => [
                'native_name' => $row['name_en'] ?? null,
                'description' => $row['description_en'] ?? null,
            ],
            'sr' => [
                'native_name' => $row['name_sr'] ?? null,
                'description' => $row['description_sr'] ?? null,
            ],
            'sr-Latn' => [
                'native_name' => $row['name_sr_latn'] ?? null,
                'description' => $row['description_sr_latn'] ?? null,
            ],
            'hr' => [
                'native_name' => $row['name_hr'] ?? null,
                'description' => $row['description_hr'] ?? null,
            ],
            'bs' => [
                'native_name' => $row['name_bs'] ?? null,
                'description' => $row['description_bs'] ?? null,
            ],
        ];
    }
}
