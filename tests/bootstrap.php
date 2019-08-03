<?php

use Illuminate\Support\Collection;
use Illuminate\Contracts\Console\Kernel;
use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

require_once __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Bootstrap the testing environment
|--------------------------------------------------------------------------
|
| You have the option to specify console commands that will execute before your
| test suite is run. Caching config, routes, & events may improve performance
| and bring your testing environment closer to production.
|
*/

$commands = [
    'config:cache',
    'event:cache',
    'route:cache',
];

$app = require __DIR__.'/../bootstrap/app.php';

$console = tap($app->make(Kernel::class))->bootstrap();

foreach ($commands as $command) {
    $console->call($command);
}

/**
 * Get function to assert count.
 *
 * @return \Closure
 */
function countAssertion()
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

Collection::macro('assertCount', countAssertion());

QueryBuilder::macro('assertCount', countAssertion());

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
    $this->assertJsonValidationErrors(array_wrap($fields));

    return $this;
});
