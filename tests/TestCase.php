<?php

namespace Tests;

use Illuminate\Database\Query\Builder;
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
        $this->collectionMacros();
        $this->responseMacros();
        $this->queryBuilderMacros();
    }

    /**
     * Register collection macros.
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

        Collection::macro('assertCount', $this->countAssertion());
    }

    /**
     * Register test response macros.
     */
    protected function responseMacros()
    {
        TestResponse::macro('assertUnauthenticated', function () {
            $this->assertStatus(401);

            return $this;
        });

        TestResponse::macro('assertUnauthorized', function () {
            $this->assertStatus(403);

            return $this;
        });

        TestResponse::macro('assertValidationErrors', function ($fields) {
            $this->assertStatus(422);
            $this->assertJsonValidationErrors(array_wrap($fields));

            return $this;
        });
    }

    /**
     * Register query builder macros.
     */
    protected function queryBuilderMacros()
    {
        Builder::macro('assertCount', $this->countAssertion());
    }

    /**
     * Get function to assert count.
     *
     * @return \Closure
     */
    protected function countAssertion()
    {
        return function ($expectedCount) {
            $actualCount = $this->count();

            PHPUnit::assertEquals(
                $expectedCount,
                $actualCount,
                "Failed asserting that count of {$actualCount} equals expected count of {$expectedCount}"
            );
        };
    }
}
