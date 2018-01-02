@extends('layouts.dashboard', ['title' => 'New Observation'])

@section('content')
    <div class="box">
        <field-observation-form action="{{ route('api.field-observations.store') }}" method="post" inline-template>
            <div class="field-observation-form">
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

                <button type="button" class="button is-text" @click="showMoreDetails = !showMoreDetails">More details</button>

                <div class="mt-4" v-show="showMoreDetails">
                    <b-field label="Note"
                        :error="form.errors.has('note')"
                        :message="form.errors.has('note') ? form.errors.first('note') : null">
                        <b-input type="textarea"
                            v-model="form.note"
                        ></b-input>
                    </b-field>

                    <b-field label="Number"
                        :type="form.errors.has('number') ? 'is-danger' : null"
                        :message="form.errors.has('number') ? form.errors.first('number') : null">
                        <b-input type="number"
                            v-model="form.number">
                        </b-input>
                    </b-field>

                    <b-field label="Sex"
                        :type="form.errors.has('sex') ? 'is-danger' : null"
                        :message="form.errors.has('sex') ? form.errors.first('sex') : null">
                        <b-select v-model="form.sex">
                            <option :value="null">Choose a value</option>
                            @foreach (\App\Observation::SEX_OPTIONS as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </b-select>
                    </b-field>

                    <b-field label="Stage"
                        :type="form.errors.has('stage_id') ? 'is-danger' : null"
                        :message="form.errors.has('stage_id') ? form.errors.first('stage_id') : null">
                        <b-select v-model="form.stage_id" :disabled="!stages.length">
                            <option :value="null">Choose a stage</option>
                            <option v-for="stage in stages" :value="stage.id" :key="stage.id" v-text="stage.name"></option>
                        </b-select>
                    </b-field>

                    @role(['admin', 'curator'])
                        <b-field label="Observer"
                            :type="form.errors.has('observer') ? 'is-danger' : null"
                            :message="form.errors.has('observer') ? form.errors.first('observer') : null">
                            <b-input v-model="form.observer"></b-input>
                        </b-field>

                        <b-field label="Identifier"
                            :type="form.errors.has('identifier') ? 'is-danger' : null"
                            :message="form.errors.has('identifier') ? form.errors.first('identifier') : null">
                            <b-input v-model="form.identifier"></b-input>
                        </b-field>
                    @endrole

                    <b-checkbox v-model="form.found_dead">Found Dead?</b-checkbox>

                    <b-field label="Note on dead observation" v-if="form.found_dead"
                        :error="form.errors.has('found_dead_note')"
                        :message="form.errors.has('found_dead_note') ? form.errors.first('found_dead_note') : null">
                        <b-input type="textarea"
                            v-model="form.found_dead_note"
                        ></b-input>
                    </b-field>

                    <b-field label="Data License"
                        :type="form.errors.has('data_license') ? 'is-danger' : null"
                        :message="form.errors.has('data_license') ? form.errors.first('data_license') : null">
                        <b-select v-model="form.data_license">
                            <option :value="null">Default</option>
                            @foreach (\App\License::getAvailable() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </b-select>
                    </b-field>

                    <b-field label="Image License"
                        :type="form.errors.has('image_license') ? 'is-danger' : null"
                        :message="form.errors.has('image_license') ? form.errors.first('image_license') : null">
                        <b-select v-model="form.image_license">
                            <option :value="null">Default</option>
                            @foreach (\App\License::getAvailable() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </b-select>
                    </b-field>
                </div>

                <hr>

                <button type="button" class="button is-primary" @click="submit">Save</button>
                <a :href="redirect" class="button is-text">Cancel</a>

            </div>
        </fild-observation-form>
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
