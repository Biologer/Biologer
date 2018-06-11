<?php

namespace App\Importing;

class JsonCollectionStreamReader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @param  string  $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Read from file using streams and pass parsed object from JSON collection
     * as an array to callback function.
     *
     * @param  callable  $callback
     * @return void
     */
    public function read(callable $callback)
    {
        (new \JsonCollectionParser\Parser())->parse($this->path, $callback);
    }
}
