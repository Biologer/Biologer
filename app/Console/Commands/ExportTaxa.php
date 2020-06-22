<?php

namespace App\Console\Commands;

use App\ConservationDocument;
use App\ConservationLegislation;
use App\RedList;
use App\Stage;
use App\Taxon;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Console\Command;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ExportTaxa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:taxa
                            {path=taxa.csv : Path to the CSV file we want to export into};
                            {--root-id= : Export taxonomic branch with root being Taxon with given ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export list of taxa into CSV file to be used for importing elsewhere';

    /**
     * Available conservation legislations.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $conservationLegislations;

    /**
     * Available conservation legislations.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $conservationDocuments;

    /**
     * Available red lists.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $redLists;

    /**
     * Available stages
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $stages;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $rootId = $this->option('root-id');

        $this->warn('Exporting...');

        $this->fetchRelated();

        $columns = $this->columns();
        $writer = WriterFactory::create(Type::CSV);

        $writer->openToFile($path);
        $writer->addRow($columns);

        $bar = $this->output->createProgressBar($this->query($rootId)->count());
        $bar->setFormat('[%bar%] %percent:3s%%');
        $bar->start();

        $this->query($rootId)->each(function ($taxon) use ($writer, $columns, $bar) {
            $writer->addRow($this->mapTaxonToColumns($taxon, $columns));
            $bar->advance();
        });

        $writer->close();

        $bar->finish();
        $this->info(' Done!');
    }

    /**
     * Fetch user that's creating taxa tree and other data related to taxa.
     *
     * @return void
     */
    private function fetchRelated()
    {
        $this->conservationDocuments = ConservationDocument::all();
        $this->conservationLegislations = ConservationLegislation::all();
        $this->redLists = RedList::all();
        $this->stages = Stage::all();
    }

    /**
     * Get columns for export
     *
     * @return array
     */
    protected function columns()
    {
        $locales = collect(LaravelLocalization::getSupportedLanguagesKeys());

        return collect(array_keys(Taxon::RANKS))->concat([
            'id', 'author', 'restricted', 'allochthonous', 'invasive',
            'fe_id', 'uses_atlas_codes', 'spid', 'birdlife_seq', 'birdlife_id',
            'ebba_code', 'euring_code', 'euring_sci_name', 'eunis_n2000code',
            'eunis_sci_name', 'bioras_sci_name', 'refer', 'prior',
            'sg', 'gn_status'
        ])->concat($this->redLists->map(function ($redList) {
            return "red_list_{$redList->slug}";
        }))->concat($this->conservationLegislations->map(function ($conservationLegislation) {
            return "conservation_legislation_{$conservationLegislation->slug}";
        }))->concat($this->conservationDocuments->map(function ($conservationDocument) {
            return "conservation_document_{$conservationDocument->slug}";
        }))->concat($this->stages->map(function ($stage) {
            return "stage_{$stage->name}";
        }))->concat($locales->map(function ($locale) {
            return 'name_'.str_replace('-', '_', strtolower($locale));
        }))->concat($locales->map(function ($locale) {
            return 'description_'.str_replace('-', '_', strtolower($locale));
        }))->all();
    }

    protected function query($rootId = null)
    {
        return Taxon::with([
            'ancestors', 'conservationDocuments', 'conservationLegislations',
            'redLists', 'stages',
        ])->when($rootId, function ($query, $rootId) {
            $query->where('id', $rootId)->orWhereHas('ancestors', function ($query) use ($rootId) {
                $query->where('id', $rootId);
            });
        })->orderByAncestry();
    }

    protected function mapTaxonToColumns(Taxon $taxon, array $columns)
    {
        $transformed = [
            'id' => $taxon->id,
            $taxon->rank => $taxon->name,
            'author' => $taxon->author ?: null,
            'restricted' => $taxon->restricted ? 'X' : null,
            'allochthonous' => $taxon->allochthonous ? 'X' : null,
            'invasive' => $taxon->invasive ? 'X' : null,
            /*'fe_old_id' => $taxon->fe_old_id ?: null,*/
            'fe_id' => $taxon->fe_id ?: null,
            'uses_atlas_codes' => $taxon->uses_atlas_codes ? 'X' : null,
        ];

        foreach ($taxon->ancestors as $ancestor) {
            $transformed[$ancestor->rank] = $ancestor->name;
        }

      	foreach ($taxon->stages as $stage) {
        	$transformed["stage_{$stage->name}"] = 'X';
        }

      	foreach ($taxon->conservationLegislations as $conservationLegislation) {
        	$transformed["conservation_legislation_{$conservationLegislation->slug}"] = 'X';
        }

      	foreach ($taxon->conservationDocuments as $conservationDocument) {
        	$transformed["conservation_document_{$conservationDocument->slug}"] = 'X';
        }

      	foreach ($taxon->redLists as $redList) {
        	$transformed["red_list_{$redList->name}"] = $redList->pivot->category;
        }

        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $translation = $taxon->translateOrNew($locale);
          	$localeSnakeCase = str_replace('-', '_', strtolower($locale));

            $transformed["native_name_{$localeSnakeCase}"] = $translation->native_name;
            $transformed["description_{$localeSnakeCase}"] = $translation->description;
        }

        return array_map(function ($column) use ($transformed) {
            return $transformed[$column] ?? null;
        }, $columns);
    }
}
