@extends('layouts.contributor')

@section('main')
    <div class="container">
        <section class="section">
            <div class="box">
                <field-observation-form action="{{ route('field-observations.store') }}" method="post" inline-template
                    :data-dynamic-fields="{{ App\FieldObservation::availableDynamicFields() }}">
                    <div class="">
                        <div class="columns">
                            <div class="column is-half">
                                <nz-taxon-autocomplete v-model="form.taxon_suggestion" @select="onTaxonSelect" :errors="form.errors"></nz-taxon-autocomplete>
                                <nz-date-input :data-year="form.year"
                                               :data-month="form.month"
                                               :data-day="form.day"
                                               v-on:year-input="onYearInput"
                                               v-on:month-input="onMonthInput"
                                               v-on:day-input="onDayInput"
                                               :errors="form.errors">
                                </nz-date-input>
                                <b-field label="Photos">
                                    <div class="columns">
                                        <div class="column is-one-third">
                                            <nz-photo-upload upload-url="{{ route('api.uploads.store') }}"
                                                remove-url="{{ route('api.uploads.destroy') }}"
                                                text="Upload"
                                                icon="upload"
                                                @uploaded="onPhotoUploaded"
                                                @removed="onPhotoRemoved"
                                                :errors="form.errors">
                                            </nz-photo-upload>
                                        </div>
                                       <div class="column is-one-third">
                                            <nz-photo-upload upload-url="{{ route('api.uploads.store') }}"
                                                remove-url="{{ route('api.uploads.destroy') }}"
                                                text="Upload"
                                                icon="upload"
                                                @uploaded="onPhotoUploaded"
                                                @removed="onPhotoRemoved"
                                                :errors="form.errors">
                                            </nz-photo-upload>
                                        </div>
                                        <div class="column is-one-third">
                                            <nz-photo-upload upload-url="{{ route('api.uploads.store') }}"
                                                remove-url="{{ route('api.uploads.destroy') }}"
                                                text="Upload"
                                                icon="upload"
                                                @uploaded="onPhotoUploaded"
                                                @removed="onPhotoRemoved"
                                                :errors="form.errors">
                                            </nz-photo-upload>
                                        </div>
                                    </div>
                                </b-field>
                            </div>
                            <div class="column is-half">
                                <nz-spatial-input :latitude.sync="form.latitude"
                                    :longitude.sync="form.longitude"
                                    :location.sync="form.location"
                                    :accuracy.sync="form.accuracy"
                                    :altitude.sync="form.altitude"
                                    :errors="form.errors">
                                </nz-spatial-input>
                            </div>
                        </div>

                        <div v-for="field in dynamicFields" :key="field.name">
                            <nz-dynamic-input :field="field" v-model="form.dynamic[field.name]" @remove="removeField(field)" :errors="form.errors"></nz-dynamic-input>
                        </div>

                        <b-field label="Additional input" v-if="availableDynamicFields.length" >
                            <b-field v-if="availableDynamicFields.length">
                                <b-select v-model="chosenField" expanded>
                                    <option v-for="field in availableDynamicFields" :value="field" v-text="field.label"></option>
                                </b-select>
                                <div class="control">
                                    <button type="button" class="button" @click="addField()" :disabled="!chosenField">Add</button>
                                </div>
                            </b-field>
                        </b-field>

                        <hr>

                        <button type="button" class="button is-primary" @click="submit">Save</button>
                    </div>
                </fild-observation-form>
            </div>
        </section>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
