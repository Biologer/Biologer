<?php

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

require_once __DIR__.'/../vendor/autoload.php';

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

Collection::macro('assertCount', function ($expectedCount) {
    $actualCount = $this->count();

    PHPUnit::assertEquals(
        $expectedCount,
        $actualCount,
        "Failed asserting that count of {$actualCount} equals expected count of {$expectedCount}"
    );
});

QueryBuilder::macro('assertCount', function ($expectedCount) {
    $actualCount = $this->count();

    PHPUnit::assertEquals(
        $expectedCount,
        $actualCount,
        "Failed asserting that count of {$actualCount} equals expected count of {$expectedCount}"
    );
});
