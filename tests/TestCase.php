<?php

namespace Tests;

use Illuminate\Http\Request;
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
    }
}
