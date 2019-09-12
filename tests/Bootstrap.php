<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class Bootstrap implements BeforeFirstTestHook, AfterLastTestHook
{
    /*
    |--------------------------------------------------------------------------
    | Bootstrap The Test Environment
    |--------------------------------------------------------------------------
    |
    | You may specify console commands that execute once before your test is
    | run. You are free to add your own additional commands or logic into
    | this file as needed in order to help your test suite run quicker.
    |
    */

    use CreatesApplication;

    public function executeBeforeFirstTest(): void
    {
        $console = $this->createApplication()->make(Kernel::class);

        $commands = [
            'config:cache',
            'event:cache',
        ];

        foreach ($commands as $command) {
            $console->call($command);
        }

        $this->registerMacros();
    }

    public function executeAfterLastTest(): void
    {
        array_map('unlink', glob('bootstrap/cache/*.phpunit.php'));
    }

    /**
     * Register macros needed for testing.
     *
     * @return void
     */
    private function registerMacros()
    {
        EloquentCollection::macro('assertEquals', function ($collection) {
            $this->zip($collection)->each(function ($pair) {
                PHPUnit::assertTrue($pair[0]->is($pair[1]));
            });
        });

        Collection::macro('assertContains', function ($item) {
            PHPUnit::assertTrue($this->contains($item), 'Failed asserting that the collection contains the specified value.');
        });

        Collection::macro('assertDoesntContain', function ($item) {
            PHPUnit::assertFalse($this->contains($item), 'Failed asserting that the collection does not contain the specified value.');
        });

        Collection::macro('assertCount', $this->countAssertion());

        QueryBuilder::macro('assertCount', $this->countAssertion());

        TestResponse::macro('assertCreated', function () {
            $this->assertStatus(201);

            return $this;
        });

        TestResponse::macro('assertUnauthorized', function () {
            $this->assertStatus(401);

            return $this;
        });

        TestResponse::macro('assertValidationErrors', function ($fields) {
            $this->assertStatus(422);
            $this->assertJsonValidationErrors(Arr::wrap($fields));

            return $this;
        });
    }

    /**
     * Get function to assert count.
     *
     * @return \Closure
     */
    private function countAssertion()
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
