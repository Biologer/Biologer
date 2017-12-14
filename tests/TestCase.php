<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->registerMacros();
    }

    /**
     * Register macros that help with testing.
     *
     * @return void
     */
    protected function registerMacros()
    {
        $this->collectionMacros();
    }

    /**
     * Register collection macros.
     *
     * @return void
     */
    protected function collectionMacros()
    {
        EloquentCollection::macro('assertEquals', function ($collection) {
            $this->zip($collection)->each(function ($pair) {
                Assert::assertTrue($pair[0]->is($pair[1]));
            });
        });

        Collection::macro('assertContains', function ($item) {
            Assert::assertTrue($this->contains($item), 'Failed asserting that the collection contains the specified value.');
        });

        Collection::macro('assertNotContains', function ($item) {
            Assert::assertFalse($this->contains($item), 'Failed asserting that the collection does not contain the specified value.');
        });
    }
}
