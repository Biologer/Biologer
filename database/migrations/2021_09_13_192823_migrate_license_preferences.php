<?php

use App\User;
use Illuminate\Database\Migrations\Migration;

class MigrateLicensePreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::each(function (User $user) {
            if ($user->settings()->data_license === 11) {
                $user->settings()->data_license = 10;
            }

            if ($user->settings()->image_license === 11) {
                $user->settings()->image_license = 10;
            }

            if ($user->settings()->image_license === 35) {
                $user->settings()->image_license = 40;
            }

            $user->save();
        });
    }
}
