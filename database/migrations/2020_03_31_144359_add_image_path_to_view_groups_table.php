<?php

use App\Models\ViewGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AddImagePathToViewGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('view_groups', function (Blueprint $table) {
            $table->string('image_path', 1000)->nullable()->before('image_url');
        });

        ViewGroup::eachById(function ($group) {
            $path = $group->image_url ? 'groups/'.basename($group->image_url) : null;

            if ($path && Storage::disk('public')->exists($path)) {
                $group->update(['image_path' => $path]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('view_groups', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
}
