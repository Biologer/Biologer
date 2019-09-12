<?php

use App\LiteratureObservation;
use App\Observation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

class CreateLiteratureObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('literature_observations', function (Blueprint $table) {
            $table->increments('id');
            $table->time('time')->nullable();
            $table->string('original_date')->nullable();
            $table->string('original_locality')->nullable();
            $table->string('original_elevation')->nullable();
            $table->string('original_coordinates')->nullable();
            $table->tinyInteger('original_identification_validity');
            $table->string('georeferenced_by')->nullable();
            $table->date('georeferenced_date')->nullable();
            $table->smallInteger('minimum_elevation')->nullable();
            $table->smallInteger('maximum_elevation')->nullable();
            $table->unsignedInteger('publication_id');
            $table->boolean('is_original_data')->default(true);
            $table->unsignedInteger('cited_publication_id')->nullable();
            $table->string('place_where_referenced_in_publication')->nullable();
            $table->timestamps();

            $table->foreign('publication_id')
                  ->references('id')
                  ->on('publications');

            $table->foreign('cited_publication_id')
                  ->references('id')
                  ->on('publications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('literature_observations');

        // Clean up left over information for removed literature observations.
        $morphClass = (new LiteratureObservation)->getMorphClass();
        Observation::where('details_type', $morphClass)->delete();
        Activity::where('subject_type', $morphClass)->delete();
    }
}
