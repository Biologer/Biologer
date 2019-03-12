<?php

namespace App\DEM;

class MemoizingReader implements Reader
{
    /**
     * @var \App\DEM\Reader
     */
    protected $reader;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * Create reader instance.
     *
     * @param  \App\DEM\Reader  $reader
     * @return void
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Get elevation in meters for given coordinates.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @return float|null
     */
    public function getElevation($latitude, $longitude)
    {
        $key = $this->cacheKey($latitude, $longitude);

        if (! array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $this->reader->getElevation($latitude, $longitude);
        }

        return $this->cache[$key];
    }

    /**
     * Get cache key based on latitude and longitude.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @return string
     */
    protected function cacheKey($latitude, $longitude)
    {
        return sha1($latitude.','.$longitude);
    }
}
