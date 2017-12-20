<?php

namespace Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Assert as PHPUnit;
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

        $this->app->setLocale('en');

        $this->registerMacros();
    }

    /**
     * Register macros that help with testing.
     *
     * @return void
     */
    protected function registerMacros()
    {
        $this->responseMacros();
        $this->collectionMacros();
    }

    /**
     * Register test response macros.
     *
     * @return void
     */
    protected function responseMacros()
    {
        TestResponse::macro('assertValidationError', function ($field) {
            $this->assertStatus(422);
            PHPUnit::assertArrayHasKey($field, $this->decodeResponseJson()['errors']);
        });
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
                PHPUnit::assertTrue($pair[0]->is($pair[1]));
            });
        });

        Collection::macro('assertContains', function ($item) {
            PHPUnit::assertTrue($this->contains($item), 'Failed asserting that the collection contains the specified value.');
        });

        Collection::macro('assertNotContains', function ($item) {
            PHPUnit::assertFalse($this->contains($item), 'Failed asserting that the collection does not contain the specified value.');
        });
    }
}
