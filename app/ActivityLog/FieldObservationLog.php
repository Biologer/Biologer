<?php

namespace App\ActivityLog;

use App\Stage;
use App\License;
use App\FieldObservation;

class FieldObservationLog
{
    public static function changes(FieldObservation $fieldObservation, array $oldData, $photoSync)
    {
        return (new self())->getChangedData($fieldObservation, $oldData, $photoSync);
    }

    /**
     * Get changed field observation data.
     * NOTE: This is seriously wierd thing and needs to be refactored and simplified.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  array  $oldData
     * @return array
     */
    protected function getChangedData(FieldObservation $fieldObservation, array $oldData, $photoSync)
    {
        $excluded = ['taxon_id', 'mgrs10k', 'time', 'observed_by_id', 'identified_by_id'];
        $changed = array_merge(
            array_keys($fieldObservation->observation->getChanges()),
            array_keys($fieldObservation->getChanges())
        );

        $data = [];
        foreach ($oldData as $key => $value) {
            if ('time' === $key && $this->timeIsChanged($fieldObservation, $value)) {
                $data[$key] = $value;
            } elseif ('photos' === $key && $this->photosAreChanged($photoSync)) {
                // We just need to know that it changed. It's confusing to show what.
                $data[$key] = null;
            } elseif ('types' === $key && $this->observationTypesAreChanged($fieldObservation, $value)) {
                $data[$key] = null;
            } elseif (in_array($key, $changed) && ! in_array($key, $excluded)) {
                if ('taxon_suggestion' === $key) {
                    // We need it with the key of "taxon", not "taxon_suggestion".
                    $data['taxon'] = $value;
                } elseif ('stage_id' === $key) {
                    // We need it with the key of "stage", not "stage_id".
                    $data['stage'] = [
                        'value' => $value,
                        'label' => $value ? 'stages.'.Stage::find($value)->name : null,
                    ];
                } elseif ('sex' === $key) {
                    $data[$key] = [
                       'value' => $value,
                       'label' => $value ? 'labels.field_observations.'.$value : null,
                   ];
                } elseif ('license' === $key) {
                    // We need it with the key of "data_license", not "license".
                    $data['data_license'] = [
                        'value' => $value,
                        'label' => 'licenses.'.License::findById($value)['name'],
                    ];
                } elseif ('found_dead' === $key) {
                    $data[$key] = ['value' => $value, 'label' => $value ? 'Yes' : 'No'];
                } else {
                    $data[$key] = $value;
                }
            }
        }

        return $data;
    }

    /**
     * Check if time changed.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  string  $oldValue
     * @return bool
     */
    protected function timeIsChanged($fieldObservation, $oldValue)
    {
        return ($oldValue && ! $fieldObservation->time)
            || $fieldObservation->time
            && $oldValue !== $fieldObservation->time->format('H:i');
    }

    /**
     * Check if photos are changed.
     *
     * @param  array  $photoSync
     * @return bool
     */
    protected function photosAreChanged($photoSync)
    {
        return count($photoSync['cropped'])
            || count($photoSync['removed'])
            || count($photoSync['added']);
    }

    /**
     * Check if observations types have changed.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  \Illuminate\Database\Eloquent\Collection  $oldValue
     * @return bool
     */
    protected function observationTypesAreChanged($fieldObservation, $oldValue)
    {
        $fieldObservation->observation->load('types');

        return $oldValue->count() !== $fieldObservation->observation->types->count()
            || ($oldValue->isNotEmpty() && $fieldObservation->observation->types->isNotEmpty()
            && $oldValue->pluck('id')->diff($fieldObservation->observation->types->pluck('id'))->isNotEmpty());
    }
}
