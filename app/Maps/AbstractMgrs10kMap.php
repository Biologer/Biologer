<?php

namespace App\Maps;

use SVG\SVGImage;
use SVG\Nodes\SVGNode;
use Illuminate\Support\Collection;
use SVG\Nodes\Structures\SVGDocumentFragment;

abstract class AbstractMgrs10kMap
{
    const IMAGE_HEIGHT = 1000;

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

        return $this->processImage($mgrs10k)->__toString();
    }

    /**
     * Process the SVG image.
     *
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return \SVG\SVGImage
     */
    protected function processImage(Collection $mgrs10k)
    {
        return tap($this->makeImage(), function ($image) use ($mgrs10k) {
            $this->colorizeCountries($image->getDocument());
            $this->colorizeMgrs10kFields($image->getDocument(), $mgrs10k);
            $this->setWidthAndHeight($image->getDocument());
        });
    }

    /**
     * Set fill colour on countries.
     *
     * @param  \SVG\Nodes\Structures\SVGDocumentFragment  $document
     */
    protected function colorizeCountries(SVGDocumentFragment $document)
    {
        foreach ($document->getElementsByClassName('country') as $country) {
            $country->setAttribute('fill', '#D2D2D2');
        }
    }

    /**
     * Set fill colour on mgrs 10k fields.
     *
     * @param  \SVG\Nodes\Structures\SVGDocumentFragment  $document
     * @param  \Illuminate\Support\Collection  $mgrs10k
     */
    protected function colorizeMgrs10kFields(SVGDocumentFragment $document, Collection $mgrs10k)
    {
        foreach ($document->getElementsByClassName('mgrs10k-field') as $node) {
            $node->setAttribute('fill', 'transparent');

            if ($mgrs10k->contains($node->getAttribute('id'))) {
                $this->handleNode($node, $mgrs10k);
            }
        }
    }

    /**
     * Set width and height to SVG element.
     *
     * @param \SVG\Nodes\Structures\SVGDocumentFragment  $document
     */
    protected function setWidthAndHeight(SVGDocumentFragment $document)
    {
        $this->setAttributesToDocument($document, $this->extractWidthAndHeight($document));
    }

    /**
     * Extract width and heigth from "viewBox" attribute.
     *
     * @param  \SVG\Nodes\Structures\SVGDocumentFragment  $document
     * @return array
     */
    protected function extractWidthAndHeight(SVGDocumentFragment $document)
    {
        $matches = [];

        preg_match(
            '/^\d*\ \d* (\d*) (\d*)$/',
            $document->getAttribute('viewBox'),
            $matches
        );

        return $this->adjustWidthAndHeigth($matches[1], $matches[2]);
    }

    /**
     * Use defined height and adjust width to preserve ratio.
     *
     * @param  int  $width
     * @param  int  $height
     * @return array
     */
    protected function adjustWidthAndHeigth($width, $height)
    {
        return [
            'width' => (static::IMAGE_HEIGHT / $height) * $width,
            'height' => static::IMAGE_HEIGHT,
        ];
    }

    /**
     * Set attributes to SVG document.
     *
     * @param \SVG\Nodes\Structures\SVGDocumentFragment  $document
     * @param array
     */
    protected function setAttributesToDocument(SVGDocumentFragment $document, $attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            $document->setAttribute($attribute, $value);
        }
    }

    /**
     * Make instance of dom crawler.
     *
     * @return \SVG\SVGImage
     */
    protected function makeImage()
    {
        return SVGImage::fromString($this->mapContents());
    }

    /**
     * Handle modifications of the node that has id among given mgrs 10k fields.
     *
     * @param  \SVG\Nodes\SVGNode  $node
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return void
     */
    abstract protected function handleNode(SVGNode $node, Collection $mgrs10k);

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

    /**
     * Render image as PNG.
     *
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return string
     */
    public function renderPng(Collection $mgrs10k)
    {
        $svg = $this->render($mgrs10k);

        $imagick = new \Imagick();
        $imagick->setBackgroundColor(new \ImagickPixel('transparent'));

        if (! empty($svg)) {
            $imagick->readImageBlob($svg);
        }

        $imagick->setImageFormat('png24');

        $png = $imagick->getImagesBlob();

        $imagick->clear();
        $imagick->destroy();

        return $png;
    }

    /**
     * Get rendered image as data uri encoded.
     *
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return string
     */
    public function toPngDataUrl(Collection $mgrs10k)
    {
        if (! $this->mapExists()) {
            return;
        }

        return 'data:image/png;base64,'.base64_encode($this->renderPng($mgrs10k));
    }
}
