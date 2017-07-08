<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, InteractsWithExceptionHandling;

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
        $this->responseMacros();
        $this->collectionMacros();
    }

    /**
     * Register macros for TestResponse.
     *
     * @return void
     */
    protected function responseMacros()
    {
        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
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
            $this->zip($collection)->eachSpread(function ($a, $b) {
                Assert::assertTrue($a->is($b));
            });
        });
    }
}
