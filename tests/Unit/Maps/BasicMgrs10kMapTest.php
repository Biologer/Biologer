<?php

namespace Tests\Unit\Maps;

use App\Maps\BasicMgrs10kMap;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class BasicMgrs10kMapTest extends TestCase
{
    #[Test]
    public function it_can_render_map_of_serbia_as_svg_using_provided_data_to_mark_mgrs_fields(): void
    {
        $map = BasicMgrs10kMap::fromPath($this->serbianMapPath());

        $expected = file_get_contents(__DIR__.'/__results__/serbia-basic.svg');
        $mgrs10kCollection = collect([
            '34TDQ66', '34TEP27', '34TEQ11', '34TEQ10', '34TEQ44', '34TEP52',
            '34TDQ16', '34TFN37', '34TDR24', '34TCR61', '34TCR71', '34TEQ29',
            '34TER20', '34TDP57', '34TEP03', '34TDQ45', '34TDR32', '34TDQ21',
            '34TEP61', '34TCQ87', '34TDS00', '34TDR39', '34TEQ23', '34TCR98',
            '34TCR81', '34TDR00', '34TFN35', '34TDQ15', '34TEP64', '34TCR97',
            '34TCR86',
        ])->mapWithKeys(function ($mgrs) {
            return [$mgrs => [
                'observations_count' => 1,
                'present_in_literature' => false,
            ]];
        });

        $this->assertEquals($expected, $map->render($mgrs10kCollection));
    }

    #[Test]
    public function it_can_render_map_of_serbia_as_svg_dataurl_using_provided_data_to_mark_mgrs_fields(): void
    {
        $map = BasicMgrs10kMap::fromPath($this->serbianMapPath());

        $expected = file_get_contents(__DIR__.'/__results__/serbia-basic-dataurl.txt');
        $mgrs10kCollection = collect([
            '34TDQ66', '34TEP27', '34TEQ11', '34TEQ10', '34TEQ44', '34TEP52',
            '34TDQ16', '34TFN37', '34TDR24', '34TCR61', '34TCR71', '34TEQ29',
            '34TER20', '34TDP57', '34TEP03', '34TDQ45', '34TDR32', '34TDQ21',
            '34TEP61', '34TCQ87', '34TDS00', '34TDR39', '34TEQ23', '34TCR98',
            '34TCR81', '34TDR00', '34TFN35', '34TDQ15', '34TEP64', '34TCR97',
            '34TCR86',
        ])->mapWithKeys(function ($mgrs) {
            return [$mgrs => [
                'observations_count' => 1,
                'present_in_literature' => false,
            ]];
        });

        $this->assertEquals($expected, $map->toDataUrl($mgrs10kCollection));
    }

    private function serbianMapPath()
    {
        return realpath(__DIR__.'/../../../resources/maps/mgrs10k/serbia.svg');
    }
}
