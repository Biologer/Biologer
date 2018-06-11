<?php

namespace App\Importing;

class JsonCollectionStreamWriter
{
    /**
     * @var resource
     */
    protected $stream;

    /**
     * Keep track of collection item we're inserting.
     *
     * @var int
     */
    protected $key = 0;

    /**
     * @param  string  $path
     */
    public function __construct($path)
    {
        $this->stream = $this->openFile($path);
    }

    /**
     * Open stream to the file and write the opening bracket.
     *
     * @param  string  $path
     * @return resource
     */
    protected function openFile($path)
    {
        // Reset the key
        $this->key = 0;

        // Just to make sure we have an empty file.
        file_put_contents($path, '');

        // Open stream
        $stream = fopen($path, 'wb');

        // Start the collection
        fwrite($stream, '[');

        return $stream;
    }

    /**
     * Insert closing bracket and close the stream.
     *
     * @return void
     */
    public function close()
    {
        fwrite($this->stream, ']');

        fclose($this->stream);
    }

    /**
     * Serialize the item and write it to the collection.
     *
     * @param  array|object  $item
     * @return void
     */
    public function add($item)
    {
        // We don't need to separate from the previous item if there are none.
        if ($this->key !== 0) {
            fwrite($this->stream, ',');
        }

        fwrite($this->stream, json_encode($item));

        $this->key++;
    }

    /**
     * Just in case we have some loose ends, close the stream.
     */
    public function __destruct()
    {
        if (is_resource($this->stream)) {
            $this->close();
        }
    }
}
