<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

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
        $this->registerRequestMacros();
    }

    /**
     * Register macros for TestResponse.
     *
     * @return void
     */
    protected function registerRequestMacros()
    {
        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });
    }
}
