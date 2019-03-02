<?php

namespace App\DEM;

class MemoizingReader implements ReaderInterface
{
    /**
     * @var \App\DEM\ReaderInterface
     */
    protected $reader;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * Create reader instance.
     *
     * @param  \App\DEM\ReaderInterface  $reader
     * @return void
     */
    public function __construct(ReaderInterface $reader)
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
        return $latitude.','.$longitude;
    }
}
