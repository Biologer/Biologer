<?php

namespace App\Services;

use App\ConservationDocument;
use App\ConservationLegislation;
use App\RedList;
use App\Taxon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class TaxonomyService
{
    protected string $url;

    public function __construct()
    {
        $this->url = config('biologer.taxonomy_link', false);
    }

    /**
     * Check if a connection to the Taxonomy server can be established.
     */
    public function check(): string
    {
        if (! $this->url) {
            return 'Failed to connect! Check .env file on server!';
        }

        try {
            $response = Http::post($this->url.'/api/taxonomy/check', [
                'key' => config('biologer.taxonomy_api_key'),
            ]);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        if ($response->status() == 200) {
            return "Check for $this->url is identified as {$response->body()}";
        }

        return 'Unauthorized';
    }

    /**
     * Connect to the Taxonomy server, sending local red lists, documents, and legislations.
     */
    public function connect(): string
    {
        if (! $this->url) {
            return 'Failed to connect! Check .env file on server!';
        }

        $data = [
            'key' => config('biologer.taxonomy_api_key'),
            'red_lists' => RedList::all()->toArray(),
            'docs' => ConservationDocument::all()->toArray(),
            'legs' => ConservationLegislation::all()->toArray(),
        ];

        try {
            $response = Http::post($this->url.'/api/taxonomy/connect', $data);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        return $response->body();
    }

    /**
     * Disconnect from the Taxonomy server and clear all local taxonomy_id references.
     */
    public function disconnect(): string
    {
        if (! $this->url) {
            return 'Failed to connect! Check .env file on server!';
        }

        try {
            $response = Http::post($this->url.'/api/taxonomy/disconnect', [
                'key' => config('biologer.taxonomy_api_key'),
            ]);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        if ($response->status() == 200) {
            Taxon::whereNotNull('taxonomy_id')->each(function (Taxon $taxon) {
                $taxon->update(['taxonomy_id' => null]);
            });

            return 'Disconnected from Taxonomy';
        }

        return 'Failed to disconnect from Taxonomy!';
    }

    /**
     * Sync all unsynced taxa with the Taxonomy server in chunks.
     * Returns a summary string.
     */
    public function syncAll(): string
    {
        if (! $this->url) {
            return 'Error! Local site is not using connection to Taxonomy database.';
        }

        $synced = 0;
        $all_taxa = Taxon::where('taxonomy_id', null)->get();

        foreach ($all_taxa->chunk(1000) as $chunk) {
            $result = $this->syncChunk($this->url, $chunk);

            if (is_string($result)) {
                return $result; // propagate error message
            }

            $synced += $result;
        }

        $not_synced = Taxon::where('taxonomy_id', null)->count();
        $synced_total = Taxon::whereNotNull('taxonomy_id')->count();

        return "Sync done for {$synced} taxa. Not synced {$not_synced}. All times synced {$synced_total}";
    }

    /**
     * Sync a single parent taxon that was just created locally, to retrieve its taxonomy_id.
     * @throws \Throwable
     */
    public function syncParent(Taxon $parent): void
    {
        if (! $this->url) {
            return;
        }

        $payload = $this->buildTaxonPayload([$parent]);

        try {
            $response = Http::post($this->url.'/api/taxonomy/sync', $payload);
        } catch (Exception $e) {
            return;
        }

        if ($response->status() != 200) {
            return;
        }

        $this->applyResponseToTaxa($response['taxa'], $response['country_ref']);
    }

    /**
     * Build the request payload for a collection of taxa.
     *
     * @param  iterable<Taxon>  $taxa
     */
    private function buildTaxonPayload(iterable $taxa): array
    {
        $payload = [
            'key' => config('biologer.taxonomy_api_key'),
            'taxa' => [],
        ];

        foreach ($taxa as $taxon) {
            $payload['taxa'][$taxon->id] = [
                'name' => $taxon->name,
                'rank' => $taxon->rank,
                'ancestor_name' => $this->resolveAncestorName($taxon),
            ];
        }

        return $payload;
    }

    /**
     * Resolve the ancestor name pattern used for matching on the Taxonomy server.
     */
    private function resolveAncestorName(Taxon $taxon): string
    {
        if (empty($taxon->ancestors_names)) {
            return '';
        }

        return Arr::first(explode(',', $taxon->ancestors_names)).'%';
    }

    /**
     * Send a chunk of taxa to the Taxonomy server and apply responses locally.
     * Returns the number of synced taxa, or an error string.
     *
     * @param \Illuminate\Support\Collection<Taxon> $taxa
     * @return int|string
     * @throws \Throwable
     */
    private function syncChunk($taxa)
    {
        $payload = $this->buildTaxonPayload($taxa);

        try {
            $response = Http::post($this->url.'/api/taxonomy/sync', $payload);
        } catch (Exception $e) {
            return 'Failed to connect! No connection to server.';
        }

        if ($response->status() != 200) {
            return '<p>Error! Data not retrieved.</p><p>'.$response->status().'</p><p>'.$response->body().'</p>';
        }

        return $this->applyResponseToTaxa($response['taxa'], $response['country_ref']);
    }

    /**
     * Apply Taxonomy server responses to local taxa.
     * Returns the count of taxa that were updated.
     * @throws \Throwable
     */
    private function applyResponseToTaxa(array $returnedTaxa, array $countryRef): int
    {
        $synced = 0;

        foreach ($returnedTaxa as $id => $item) {
            if (empty($item['response'])) {
                continue;
            }

            $taxon = Taxon::find($id);
            if (! $taxon) {
                continue;
            }

            app(SyncTaxonService::class)->updateTaxon($item['response'], $taxon, $countryRef);

            $synced++;
        }

        return $synced;
    }
}
