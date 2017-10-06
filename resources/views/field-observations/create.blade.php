@extends('layouts.contributor')

@section('main')
    <div class="container">
        <section class="section">
            <field-observation-form action="{{ route('api.field-observations.store') }}" :data-available-dynamic-fields="{{ App\FieldObservation::mappedAvailableDynamicFields() }}" method="post" class="box" inline-template>
                <div class="">
                    <nz-photo-upload></nz-photo-upload>
                    <div class="columns">
                        <div class="column is-half">
                            <nz-taxon-autocomplete v-model="form.taxon_suggestion" @select="onTaxonSelect"></nz-taxon-autocomplete>
                            <nz-date-input year="form.year"
                                           month="form.month"
                                           day="form.day"
                                           v-on:year-input="onYearInput"
                                           v-on:month-input="onMonthInput"
                                           v-on:day-input="onDayInput">
                            </nz-date-input>
                        </div>
                        <div class="column is-half">
                            <b-field label="Location">
                                <b-input name="location" v-model="form.location"></b-input>
                            </b-field>
                            <b-field grouped>
                                <b-field label="Latitude" expanded>
                                    <b-input name="latitude" v-model="form.latitude"></b-input>
                                </b-field>
                                <b-field label="Longitude" expanded>
                                    <b-input name="longitude" v-model="form.longitude"></b-input>
                                </b-field>
                            </b-field>
                            <b-field grouped>
                                <b-field label="Accuracy" expanded>
                                    <b-input name="accuracy" v-model="form.accuracy"></b-input>
                                </b-field>
                                <b-field label="Altitude" expanded>
                                    <b-input name="alititude" v-model="form.altitude"></b-input>
                                </b-field>
                            </b-field>
                        </div>
                    </div>

                    <div v-for="field in dynamicFields" :key="field.name">
                        <nz-dynamic-input :field="field" v-model="form.dynamic[field.name]" @remove="removeField(field)"></nz-dynamic-input>
                    </div>

                    <b-field label="Additional input" v-if="availableDynamicFields.length">
                        <b-field v-if="availableDynamicFields.length">
                            <b-select v-model="chosenField" expanded>
                                <option v-for="field in availableDynamicFields" :value="field" v-text="field.label"></option>
                            </b-select>

                            <button type="button" class="button" @click="addField()" :disabled="!chosenField">Add</button>
                        </b-field>
                    </b-field>

                    <hr>

                    <button type="button" class="button is-primary" @click="submit">Save</button>
                </div>
            </fild-observation-form>
        </section>
    </div>
@endsection
