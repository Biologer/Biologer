<?php

namespace App\Maps;

use SVG\Nodes\SVGNode;
use Illuminate\Support\Collection;

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
        $node->setAttribute('fill', '#D40000');
    }
}
