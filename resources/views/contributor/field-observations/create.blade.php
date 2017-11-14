@extends('layouts.dashboard', ['title' => 'New Observation'])

@section('content')
    <div class="container p-4">
        <div class="box">
            <field-observation-form action="{{ route('api.field-observations.store') }}" method="post" inline-template
                :data-dynamic-fields="{{ App\FieldObservation::availableDynamicFields() }}">
                <div class="">
                    <div class="columns">
                        <div class="column is-half">
                            <nz-taxon-autocomplete v-model="form.taxon_suggestion"
                                @select="onTaxonSelect"
                                :error="form.errors.has('taxon_id')"
                                :message="form.errors.has('taxon_id') ? form.errors.first('taxon_id') : null"
                                autofocus>
                            </nz-taxon-autocomplete>
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
                                :elevation.sync="form.elevation"
                                :errors="form.errors">
                            </nz-spatial-input>
                        </div>
                    </div>

                    <div v-for="(field, index) in dynamicFields" :key="field.name">
                        <nz-dynamic-input :field="field" v-model="_.find(form.dynamic_fields, {name:field.name}).value" @remove="removeField(field)" :errors="form.errors" :index="index"></nz-dynamic-input>
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
                    <a :href="redirect" class="button is-text">Cancel</a>
                </div>
            </fild-observation-form>
        </div>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li><a href="{{ route('contributor.field-observations.index') }}">My Field Observations</a></li>
            <li class="is-active"><a>New</a></li>
        </ul>
    </div>
@endsection

@push('headerScripts')
<script>
    window.App.gmaps.load = true;
</script>
@endpush
