<?php

use App\FieldObservation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterObservationsTableAddOriginalIdentificationColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('observations', function (Blueprint $table) {
            $table->string('original_identification')->nullable()->after('taxon_id');
        });

        // Use taxon from activity log to fill the first identification.
        FieldObservation::with(['activity', 'observation'])->chunk(250, function ($fieldObservations) {
            foreach ($fieldObservations as $fieldObservation) {
                $activity = $fieldObservation
                    ->activity
                    ->sortByDesc('created_at')
                    ->first(function ($activity) {
                        return array_key_exists('taxon', $activity->changes()->get('old', []));
                    });

                if (! $activity) {
                    continue;
                }

                $identification = $activity->changes()->get('old', [])['taxon'] ?? null;

                if (! $identification) {
                    continue;
                }

                $fieldObservation->observation->update([
                    'original_identification' => ucfirst($identification),
                ]);
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
        Schema::table('observations', function (Blueprint $table) {
            $table->dropColumn('original_identification');
        });
    }
}
