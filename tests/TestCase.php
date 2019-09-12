<?php

namespace Tests;

use App\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, CustomAssertArraySubset, RefreshDatabase;

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'])) {
            return $this->refreshMySQLTestDatabase();
        }

        return parent::refreshTestDatabase();
    }

    private function refreshMySQLTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            // If there is users table that means we have probably ran the migrations
            // before and can proceed with running the rest instead of importing snapshot.
            if (! Schema::hasTable('users')) {
                DB::unprepared(file_get_contents(database_path('migrations_2019_08_03.sql')));
            }

            $this->artisan('migrate');

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
