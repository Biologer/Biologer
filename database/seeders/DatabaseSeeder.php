<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(StagesTableSeeder::class);
        $this->call(TaxaTableSeeder::class);
        $this->call(RedListsTableSeeder::class);
        $this->call(ConservationLegislationsTableSeeder::class);
        $this->call(ConservationDocumentsTableSeeder::class);
        $this->call(ObservationTypesTableSeeder::class);
        $this->call(TerritorySpecificTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
    }
}
