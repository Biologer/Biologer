<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableReplaceVerifiedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('settings');
        });

        DB::table('users')->where('verified', true)->update([
            'email_verified_at' => Carbon::now(),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('verified')->default(0)->after('settings');
        });

        DB::table('users')->whereNotNull('email_verified_at')->update([
            'verified' => true,
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
        });
    }
}
