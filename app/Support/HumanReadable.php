<?php

namespace App\Support;

class HumanReadable
{
    /**
     * Format file size to be human readable.
     *
     * @param  int  $kilobytes
     * @return string
     */
    public static function fileSize($kilobytes)
    {
        $i = floor(log($kilobytes, 1024));

        return round(
            $kilobytes / pow(1024, $i),
            [0,2,2,3][$i]
        ).['kB','MB','GB','TB'][$i];
    }
}
