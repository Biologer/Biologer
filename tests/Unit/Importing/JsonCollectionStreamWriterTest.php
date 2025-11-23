<?php

namespace Tests\Unit\Importing;

use PHPUnit\Framework\Attributes\Test;
use App\Importing\JsonCollectionStreamWriter;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class JsonCollectionStreamWriterTest extends TestCase
{
    /**
     * Path of the written file.
     *
     * @var string
     */
    private $path;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->path = __DIR__.DIRECTORY_SEPARATOR.Str::random().'.json';
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        @unlink($this->path);

        parent::tearDown();
    }

    #[Test]
    public function it_can_write_json_collections_to_given_path()
    {
        $writer = new JsonCollectionStreamWriter($this->path);

        $items = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];

        foreach ($items as $item) {
            $writer->add($item);
        }

        $writer->close();

        $this->assertTrue(file_exists($this->path));
        $this->assertEquals('[{"id":1},{"id":2},{"id":3}]', file_get_contents($this->path));
    }

    #[Test]
    public function it_closes_unclosed_collection_when_destructing()
    {
        $writer = new JsonCollectionStreamWriter($this->path);

        $items = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];

        foreach ($items as $item) {
            $writer->add($item);
        }

        unset($writer);

        $this->assertTrue(file_exists($this->path));
        $this->assertEquals('[{"id":1},{"id":2},{"id":3}]', file_get_contents($this->path));
    }
}
