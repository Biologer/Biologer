<?php

namespace App\Console\Commands;

use App\ConservationLegislation;
use App\RedList;
use App\Taxon;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportTaxa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:taxa
                            {path=taxa.csv : Path to the CSV file we want to import}
                            {--u|user= : ID of User to whom the taxa tree should be attributed to}
                            {--compose-species-name : Species and subspecies contain only suffixes so we need to combine them with genus}
                            {--chunked : Chunk reading the CSV file}
                            {--chunk-size=1000 : Size of the chunk when chunking is enabled CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import list of taxa from CSV file';

    /**
     * User that we should attribute the taxon tree to.
     *
     * @var \App\User|null
     */
    private $user;

    /**
     * Available conservation legislations.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $conservationLegislations;

    /**
     * Available red lists.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $redLists;

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

        $this->fetchRelated();

        DB::transaction(function () use ($path) {
            $this->readFile($path, function ($rows) {
                $this->createSpecies($rows);
            });
        });

        $this->info('Done!');
    }

    /**
     * Fetch user that's creating taxa tree and other data related to taxa.
     *
     * @return void
     */
    private function fetchRelated()
    {
        $this->conservationLegislations = ConservationLegislation::all();
        $this->redLists = RedList::all();
        $this->user = $this->option('user') ? User::find($this->option('user')) : null;
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
     * @param  [type] $rowNumber [description]
     * @return [type]            [description]
     */
    private function shouldInsertChunk($rowNumber)
    {
        return ($rowNumber - 1) % $this->option('chunk-size') === 0;
    }

    /**
     * Go through each row and create taxa tree based on data in it.
     *
     * @param  array  $data
     * @return void
     */
    private function createSpecies($data)
    {
        foreach ($data as $taxon) {
            $this->addEntireTreeOfTheTaxon($taxon);
        }
    }

    /**
     * Create taxon with ancestor tree using data from one row.
     *
     * @param  array  $taxon
     * @return void
     */
    private function addEntireTreeOfTheTaxon($taxon)
    {
        if ($tree = $this->buildWorkingTree($taxon)) {
            // We assume that the rest of available information describes the
            // lowest ranked taxon in the row.
            $last = end($tree);
            $last->fill($this->extractOtherTaxonData($taxon));

            $this->storeWorkingTree($tree);
            $this->saveRelations($last, $taxon);
        }
    }

    /**
     * Make taxa tree using data from a row.
     *
     * @param  array  $taxon
     * @return array
     */
    private function buildWorkingTree($taxon)
    {
        $tree = [];
        $taxa = $this->getRankNamePairsForTree($taxon);
        $existing = $this->getExistingTaxaForPotentialTree($taxa);

        foreach ($taxa as $taxon) {
            $tree[] = $existing->first(function ($existingTaxon) use ($taxon) {
                return $this->isSameTaxon($existingTaxon, $taxon);
            }, new Taxon($taxon));
        }

        return $tree;
    }

    /**
     * Check if it's the same taxon as existing one.
     *
     * @param  \App\Taxon  $existingTaxon
     * @param  array  $taxon
     * @return bool
     */
    private function isSameTaxon($existingTaxon, $taxon)
    {
        return $existingTaxon->rank === $taxon['rank'] &&
            strtolower($existingTaxon->name) === strtolower($taxon['name']);
    }

    /**
     * Get name and rank data for each taxon in the tree from the row.
     *
     * @param  array  $taxon
     * @return array
     */
    private function getRankNamePairsForTree($taxon)
    {
        $tree = [];
        $ranks = array_keys(Taxon::RANKS);

        foreach ($ranks as $rank) {
            $name = $this->getNameForRank($rank, $taxon);

            if (! $name) {
                continue;
            }

            $tree[] = [
                'name' => $name,
                'rank' => $rank,
            ];
        }

        return $tree;
    }

    /**
     * Get the name of the taxon for given rank, using the data from the row.
     * We might need to compose it if species and subspecies contains only suffix.
     *
     * @param  string  $rank
     * @param  array  $taxon
     * @return string|null
     */
    private function getNameForRank($rank, $taxon)
    {
        if ($this->option('compose-species-name')) {
            if ($this->isCompoundSubspeciesName($rank, $taxon)) {
                return $this->buildCompoundSubspeciesName($taxon);
            }

            if ($this->isCompoundSpeciesName($rank, $taxon)) {
                return $this->buildCompoundSpeciesName($taxon);
            }
        }

        return $taxon[$rank] ?? null;
    }

    /**
     * Check if we have compound name for subspecies.
     *
     * @param  string  $rank
     * @param  array  $taxon
     * @return bool
     */
    private function isCompoundSubspeciesName($rank, $taxon)
    {
        return $rank === 'subspecies' &&
            ! empty($taxon['genus'] &&
            ! empty($taxon['species']) &&
            ! empty($taxon['subspecies']));
    }

    /**
     * Build subspecies name from genus, species suffix and subspecies suffix.
     *
     * @param  array  $taxon
     * @return string
     */
    private function buildCompoundSubspeciesName($taxon)
    {
        return implode(' ', array_filter([
            $taxon['genus'],
            empty($taxon['subgenus']) ? null : '('.$taxon['subgenus'].')',
            $taxon['species'],
            $taxon['subspecies'],
        ]));
    }

    /**
     * Check if we have compound name for species.
     *
     * @param  string  $rank
     * @param  array  $taxon
     * @return bool
     */
    private function isCompoundSpeciesName($rank, $taxon)
    {
        return $rank === 'species' &&
            ! empty($taxon['genus'] &&
            ! empty($taxon['species']));
    }

    /**
     * Build subspecies name from genus, species suffix and subspecies suffix.
     *
     * @param  array  $taxon
     * @return string
     */
    private function buildCompoundSpeciesName($taxon)
    {
        return implode(' ', array_filter([
            $taxon['genus'],
            empty($taxon['subgenus']) ? null : '('.$taxon['subgenus'].')',
            $taxon['species'],
        ]));
    }

    /**
     * If we already have some taxon in database, we don't need to create it againg,
     * we'll use the one we have.
     *
     * @param  array  $tree
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getExistingTaxaForPotentialTree(array $tree)
    {
        $query = Taxon::query()->with('ancestors');

        foreach ($tree as $taxon) {
            $query->orWhere(function ($q) use ($taxon) {
                $q->where('rank', $taxon['rank'])->where('name', 'like', trim($taxon['name']));
            });
        }

        return $query->get()->groupBy(function ($taxon) {
            return $taxon->isRoot() ? $taxon->id : $taxon->ancestors->filter->isRoot()->first()->id;
        })->sortByDesc(function ($group) {
            return $group->count();
        })->first() ?: EloquentCollection::make();
    }

    /**
     * Extract the rest of information that we'll use to describe the lowest taxon in the row.
     *
     * @param  array  $row
     * @return array
     */
    private function extractOtherTaxonData($row)
    {
        return [
            'allochthonous' => ! empty($row['allochthonous']),
            'invasive' => ! empty($row['invasive']),
            'restricted' => ! empty($row['restricted']),
            'author' => $row['author'] ?? null,
            'fe_old_id' => $row['fe_old_id'] ?? null,
            'fe_id' => $row['fe_id'] ?? null,
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
        ];
    }

    /**
     * Store the working tree of a row.
     * Some taxa might already exist, some are new and need to be created.
     *
     * @param  array  $tree
     * @return array
     */
    private function storeWorkingTree($tree)
    {
        $sum = [];
        $last = null;

        foreach ($tree as $current) {
            // Connect the taxon with it's parent to establish ancestry.
            $current->parent_id = $last ? $last->id : null;
            $doesntExist = ! $current->exists;

            if ($current->isDirty() || $doesntExist) {
                $current->save();
                $this->info('Stored taxon: '.$current->name);
            }

            // If we wanted to attribute the taxa tree to a user,
            // this is the place we do it, adding an entry to
            // activity log.
            if ($doesntExist && $this->user) {
                activity()->performedOn($current)
                    ->causedBy($this->user)
                    ->log('created');
            }

            $sum[] = $current;
            $last = $current;
        }

        return $sum;
    }

    /**
     * Connect the lowest taxon in the row with some of it's relations.
     *
     * @param  \App\Taxon  $taxon
     * @param  array  $data
     * @return void
     */
    private function saveRelations($taxon, $data)
    {
        $conservationLegislationSlugs = [];

        foreach ($data as $key => $value) {
            if (Str::startsWith($key, 'red_list_') && ! empty($value)) {
                $redList = $this->redLists->where('slug', str_replace('red_list_', '', $key))->first();

                $taxon->redLists()->attach($redList, ['category' => $value]);
            } elseif (Str::startsWith($key, 'conservation_legislation_') && ! empty($value)) {
                $conservationLegislationSlugs[] = str_replace('conservation_legislation_', '', $key);
            }
        }

        $taxon->conservationLegislations()->sync(
            $this->conservationLegislations->whereIn('slug', $conservationLegislationSlugs)
        );
    }
}
