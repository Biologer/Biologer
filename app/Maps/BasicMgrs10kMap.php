<?php

namespace App\Maps;

use Illuminate\Support\Collection;
use SVG\Nodes\SVGNode;

class BasicMgrs10kMap extends AbstractMgrs10kMap
{
    /**
     * Handle modifications of the node that has id among given mgrs 10k fields.
     *
     * @param  \SVG\Nodes\SVGNode  $node
     * @param  \Illuminate\Support\Collection  $mgrs10k
     * @return void
     */
    protected function handleNode(SVGNode $node, Collection $mgrs10k)
    {
        if (! ($fieldData = $mgrs10k->get($node->getAttribute('id')))) {
            return;
        }

        if ($fieldData['present_in_literature']) {
            $node->setAttribute('fill', '#A90000');
        } else {
            $node->setAttribute('fill', '#D40000');
        }

        $node->setAttribute('v-tooltip', "{content: '{$this->formatTooltip($fieldData)}'}");
    }

    /**
     * Format tooltip using field data.
     *
     * @param  array  $fieldData
     * @return string
     */
    private function formatTooltip($fieldData)
    {
        return vsprintf('%d %s', [
            $fieldData['observations_count'],
            trans_choice('pages.taxa.observations', $fieldData['observations_count']),
        ]);
    }
}
