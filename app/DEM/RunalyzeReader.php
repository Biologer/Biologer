<?php

namespace App\DEM;

use Exception;
use Runalyze\DEM\Provider\ProviderInterface;
use RuntimeException;

class RunalyzeReader implements Reader
{
    /**
     * @var \Runalyze\DEM\Provider\ProviderInterface[]
     */
    protected $providers = [];

    /**
     * @param  \Runalyze\DEM\Provider\ProviderInterface|null  $provider
     * @return void
     */
    public function __construct(ProviderInterface $provider = null)
    {
        if (null !== $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * @param  \Runalyze\DEM\Provider\ProviderInterface  $provider
     * @return void
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * Get elevation in meters for given coordinates.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @return int|null
     */
    public function getElevation($latitude, $longitude)
    {
        try {
            $provider = $this->getProvider($latitude, $longitude);

            $elevation = $provider->getElevation($latitude, $longitude);

            return $elevation ? (int) $elevation : null;
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * Find provider that can get elevation for given latitude and longitude.
     *
     * @param  float  $latitude
     * @param  float  $longitude
     * @return \Runalyze\DEM\Provider\ProviderInterface
     */
    private function getProvider($latitude, $longitude)
    {
        $boundaries = [[$latitude, $longitude]];

        foreach ($this->providers as $provider) {
            if ($provider->hasDataFor($boundaries)) {
                return $provider;
            }
        }

        throw new RuntimeException('No provider can handle given location.');
    }
}
