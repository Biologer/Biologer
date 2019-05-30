<?php

namespace App\Maps;

use SVG\Nodes\SVGNode;
use App\LiteratureObservation;
use Illuminate\Support\Collection;

class BasicMgrs10kMap extends AbstractMgrs10kMap
{
    protected $fields = [];
    protected $highlighted = [];

    /**
     * Handle modifications of the node that has id among given mgrs 10k fields.
     *
     * @param  \SVG\Nodes\SVGNode  $node
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return void
     */
    protected function handleNode(SVGNode $node, Collection $mgrs10k)
    {
        if (! $this->fields) {
            $this->fields = $mgrs10k->pluck('field')->all();
            $this->highlighted = $mgrs10k->where('type', LiteratureObservation::class)->pluck('field')->all();
        }

        if (in_array($node->getAttribute('id'), $this->highlighted)) {
            $node->setAttribute('fill', '#A90000');
        } elseif (in_array($node->getAttribute('id'), $this->fields)) {
            $node->setAttribute('fill', '#D40000');
        }
    }
}
