<?php

namespace App\Console\Commands;

use App\User;
use App\Taxon;
use App\RedList;
use Illuminate\Console\Command;
use App\ConservationLegislation;
use Illuminate\Support\Facades\DB;

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
                            {--compose-species-name : Species and subspecies contain only suffixes so we need to combine them with genus}';

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
            $this->createSpecies($this->extractDataFromFile($path));
        });

        $this->info('Done!');
    }

    /**
     * Fetch user that's creating taxa tree and other data related to taxa.
     *
     * @return void
     */
    protected function fetchRelated()
    {
        $this->conservationLegislations = ConservationLegislation::all();
        $this->redLists = RedList::all();
        $this->user = $this->option('user') ? User::find($this->option('user')) : null;
    }

    /**
     * Extract data from CSV file.
     *
     * @param  string  $path
     * @return array
     */
    protected function extractDataFromFile($path)
    {
        $file = fopen($path, 'r');
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
            $rowNumber++;
        }

        fclose($file);

        return $rows;
    }

    /**
     * Map columns using names from the first row.
     *
     * @param  array  $row
     * @param  array  $columns
     * @return array
     */
    protected function mapColumnsOnRow($row, $columns)
    {
        $data = [];

        foreach ($columns as $index => $column) {
            $data[$column] = $row[$index] ?? null;
        }

        return $data;
    }

    /**
     * Go through each row and create taxa tree based on data in it.
     *
     * @param  array  $data
     * @return void
     */
    protected function createSpecies($data)
    {
        foreach ($data as $i => $taxon) {
            $this->info('Adding tree for row # '.($i + 1));
            $this->addEntireTreeOfTheTaxon($taxon);
        }
    }

    /**
     * Create taxon with ancestor tree using data from one row.
     *
     * @param  array  $taxon
     * @return void
     */
    protected function addEntireTreeOfTheTaxon($taxon)
    {
        $tree = $this->buildWorkingTree($taxon);

        if (! empty($tree)) {
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
    protected function buildWorkingTree($taxon)
    {
        $tree = [];
        $taxa = $this->getRankNamePairsForTree($taxon);
        $existing = $this->getExistingTaxaForPotentialTree($taxa);

        foreach ($taxa as $t) {
            $tree[] = $existing->first(function ($taxon) use ($t) {
                return $taxon->rank === $t['rank'] && $taxon->name === $t['name'];
            }, new Taxon($t));
        }

        return $tree;
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
    protected function getNameForRank($rank, $taxon)
    {
        if ($this->option('compose-species-name')) {
            if ($rank === 'subspecies' && ! empty($taxon['genus'] && ! empty($taxon['species']) && ! empty($taxon['subspecies']))) {
                return implode(' ', array_filter([
                    $taxon['genus'],
                    empty($taxon['subgenus']) ? null : '('.$taxon['subgenus'].')',
                    $taxon['species'],
                    $taxon['subspecies'],
                ]));
            }

            if ($rank === 'species' && ! empty($taxon['genus'] && ! empty($taxon['species']))) {
                return implode(' ', array_filter([
                    $taxon['genus'],
                    empty($taxon['subgenus']) ? null : '('.$taxon['subgenus'].')',
                    $taxon['species'],
                ]));
            }
        }

        return $taxon[$rank] ?? null;
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
        $query = Taxon::query();

        foreach ($tree as $taxon) {
            $query->orWhere(function ($q) use ($taxon) {
                $q->where('rank', $taxon['rank'])->where('name', $taxon['name']);
            });
        }

        return $query->get();
    }

    /**
     * Extract the rest of information that we'll use to describe the lowest taxon in the row.
     *
     * @param  array  $row
     * @return array
     */
    protected function extractOtherTaxonData($row)
    {
        return [
            'author' => $row['author'] ?? null,
            'fe_old_id' => $row['fe_old_id'] ?? null,
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
    protected function storeWorkingTree($tree)
    {
        $sum = [];
        $last = null;

        foreach ($tree as $current) {
            // Connect the taxon with it's parent to establish ancestry.
            $current->parent_id = $last ? $last->id : null;

            if ($current->isDirty() || ! $current->exists) {
                $current->save();
                $this->info('Stored taxon: '.$current->name);
            }

            // If we wanted to attribute the taxa tree to a user,
            // this is the place we do it, adding an entry to
            // activity log.
            if (! $current->exists && $this->user) {
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
            if (starts_with($key, 'red_list_') && ! empty($value)) {
                $redList = $this->redLists->where('slug', str_replace('red_list_', '', $key))->first();

                $taxon->redLists()->attach($redList, ['category' => $value]);
            } elseif (starts_with($key, 'conservation_legislation_') && ! empty($value)) {
                $conservationLegislationSlugs[] = str_replace('conservation_legislation_', '', $key);
            }
        }

        $taxon->conservationLegislations()->sync(
            $this->conservationLegislations->whereIn('slug', $conservationLegislationSlugs)
        );
    }
}
