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
            $this->fields = $mgrs10k->groupBy('field')->all();
            $this->highlighted = $mgrs10k->where('type', LiteratureObservation::class)->pluck('field')->all();
        }

        $fieldPresent = isset($this->fields[$node->getAttribute('id')]);

        if (! $fieldPresent) {
            return;
        }

        if (in_array($node->getAttribute('id'), $this->highlighted)) {
            $node->setAttribute('fill', '#A90000');
        } else {
            $node->setAttribute('fill', '#D40000');
        }

        $observationsCount = $this->fields[$node->getAttribute('id')]->sum('observationsCount');

        $tooltip = vsprintf('%d %s', [
            $observationsCount,
            trans_choice('pages.taxa.observations', $observationsCount),
        ]);

        $node->setAttribute('v-tooltip', "{content: '{$tooltip}'}");
    }
}
