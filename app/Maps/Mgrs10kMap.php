<?php

namespace App\Maps;

use Illuminate\Support\Collection;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class Mgrs10kMap
{
    /**
     * Territory name.
     *
     * @var string
     */
    protected $territory;

    public function __construct($territory)
    {
        $this->territory = $territory;
    }

    /**
     * Render map with changes depending on data and the way we want to handle it.
     *
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return string
     */
    public function render(Collection $mgrs10k)
    {
        if (! $this->mapExists()) {
            return;
        }

        return tap($this->makeCrawler(), function ($crawler) use ($mgrs10k) {
            // TODO: Implement actual filtering of DOM nodes based on SVG structure.
            $crawler->filter('.country')->attr('fill', '#D2D2D2');
            $crawler->filter('.mgrs10k-field')->each(function ($node) use ($mgrs10k) {
                $node->attr('fill', 'transparent');

                if ($mgrs10k->contains($node->attr('id'))) {
                    $this->handleNode($node, $mgrs10k);
                }

                return $node;
            });
        })->__toString();
    }

    /**
     * Make instance of dom crawler.
     *
     * @return \Wa72\HtmlPageDom\HtmlPageCrawler
     */
    protected function makeCrawler()
    {
        return HtmlPageCrawler::create($this->mapContents());
    }

    /**
     * Handle modifications of the node that has id among given mgrs 10k fields.
     *
     * @param  mixed  $node
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return void
     */
    protected function handleNode($node, Collection $mgrs10k)
    {
        $node->attr('fill', '#D40000');
    }

    /**
     * Get map contents.
     *
     * @return string
     */
    protected function mapContents()
    {
        return file_get_contents($this->filePath());
    }

    /**
     * Check if map exists.
     *
     * @return bool
     */
    protected function mapExists()
    {
        return file_exists($this->filePath());
    }

    /**
     * Get name of the view that holds the base.
     *
     * @return string
     */
    protected function filePath()
    {
        return resource_path('maps/mgrs10k/'.strtolower($this->territory).'.svg');
    }

    /**
     * Get rendered image as data uri encoded.
     *
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return string
     */
    public function toDataUrl(Collection $mgrs10k)
    {
        if (! $this->mapExists()) {
            return;
        }

        return 'data:image/svg+xml;base64,'.base64_encode($this->render($mgrs10k));
    }
}
