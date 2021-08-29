<?php

namespace Database\Seeders;

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('name', 'admin')->first();
        $curator = Role::where('name', 'curator')->first();

        factory(User::class)->create([
            'email' => 'nen.zivanovic@gmail.com',
        ])->roles()->sync([$admin->id, $curator->id]);

        factory(User::class)->create([
            'email' => 'admin@example.com',
        ])->roles()->sync([$admin->id]);

        factory(User::class)->create([
            'email' => 'curator@example.com',
        ])->roles()->sync([$curator->id]);

        factory(User::class)->create([
            'email' => 'member@example.com',
        ]);
    }
}
