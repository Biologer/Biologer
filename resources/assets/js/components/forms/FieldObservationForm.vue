<template>
    <form :action="action" method="POST" :lang="locale" class="field-observation-form">
        <div class="columns">
            <div class="column is-half">
                <nz-taxon-autocomplete v-model="form.taxon_suggestion"
                    @select="onTaxonSelect"
                    :error="form.errors.has('taxon_id')"
                    :message="form.errors.has('taxon_id') ? form.errors.first('taxon_id') : null"
                    autofocus
                    ref="taxonAutocomplete"
                    :label="trans('labels.field_observations.taxon')"
                    :placeholder="trans('labels.field_observations.search_for_taxon')" />

                <nz-date-input
                    :data-year="form.year"
                    :data-month="form.month"
                    :data-day="form.day"
                    v-on:year-input="onYearInput"
                    v-on:month-input="onMonthInput"
                    v-on:day-input="onDayInput"
                    :errors="form.errors"
                    :label="trans('labels.field_observations.date')"
                    :placeholders="{
                        year: trans('labels.field_observations.year'),
                        month: trans('labels.field_observations.month'),
                        day: trans('labels.field_observations.day')
                    }" />

                <b-field :label="trans('labels.field_observations.photos')">
                    <div class="columns">
                        <div class="column is-one-third">
                            <nz-photo-upload
                                :upload-url="photoUploadUrl"
                                :remove-url="photoRemoveUrl"
                                :image-url="getObservationPhotoAttribute(0, 'url')"
                                :image-path="getObservationPhotoAttribute(0, 'path')"
                                :text="trans('labels.field_observations.upload')"
                                icon="upload"
                                @uploaded="onPhotoUploaded"
                                @removed="onPhotoRemoved"
                                :errors="form.errors"
                            ></nz-photo-upload>
                        </div>

                        <div class="column is-one-third">
                            <nz-photo-upload
                                :upload-url="photoUploadUrl"
                                :remove-url="photoRemoveUrl"
                                :image-url="getObservationPhotoAttribute(1, 'url')"
                                :image-path="getObservationPhotoAttribute(1, 'path')"
                                :text="trans('labels.field_observations.upload')"
                                icon="upload"
                                @uploaded="onPhotoUploaded"
                                @removed="onPhotoRemoved"
                                :errors="form.errors"
                            ></nz-photo-upload>
                        </div>

                        <div class="column is-one-third">
                            <nz-photo-upload
                                :upload-url="photoUploadUrl"
                                :remove-url="photoRemoveUrl"
                                :image-url="getObservationPhotoAttribute(2, 'url')"
                                :image-path="getObservationPhotoAttribute(2, 'path')"
                                :text="trans('labels.field_observations.upload')"
                                icon="upload"
                                @uploaded="onPhotoUploaded"
                                @removed="onPhotoRemoved"
                                :errors="form.errors"
                            ></nz-photo-upload>
                        </div>
                    </div>
                </b-field>
            </div>

            <div class="column is-half">
                <nz-spatial-input
                    :latitude.sync="form.latitude"
                    :longitude.sync="form.longitude"
                    :location.sync="form.location"
                    :accuracy.sync="form.accuracy"
                    :elevation.sync="form.elevation"
                    :errors="form.errors"
                ></nz-spatial-input>
            </div>
        </div>

        <button
            type="button"
            class="button is-text"
            @click="showMoreDetails = !showMoreDetails"
        >
            {{ trans('labels.field_observations.more_details') }}
        </button>

        <div class="mt-4" v-show="showMoreDetails">
            <div class="columns">
                <div class="column">
                    <b-field
                        :label="trans('labels.field_observations.stage')"
                        :type="form.errors.has('stage_id') ? 'is-danger' : null"
                        :message="form.errors.has('stage_id') ? form.errors.first('stage_id') : null"
                    >
                        <b-select v-model="form.stage_id" :disabled="!stages.length" expanded>
                            <option :value="null">{{ trans('labels.field_observations.choose_a_stage') }}</option>
                            <option v-for="stage in stages" :value="stage.id" :key="stage.id" v-text="trans('stages.'+stage.name)"></option>
                        </b-select>
                    </b-field>
                </div>

                <div class="column">
                    <b-field
                        :label="trans('labels.field_observations.sex')"
                        :type="form.errors.has('sex') ? 'is-danger' : null"
                        :message="form.errors.has('sex') ? form.errors.first('sex') : null"
                    >
                        <b-select v-model="form.sex">
                            <option :value="null">{{ trans('labels.field_observations.choose_a_value') }}</option>
                            <option v-for="sex in sexes" :value="sex" v-text="trans('labels.field_observations.'+sex)"></option>
                        </b-select>
                    </b-field>
                </div>
            </div>

            <b-field
                :label="trans('labels.field_observations.types')"
                :error="form.errors.has('observation_types_ids')"
                :message="form.errors.has('observation_types_ids') ? form.errors.first('observation_types_ids') : null"
            >
                <b-taginput
                    v-model="selectedObservationTypes"
                    :data="availableObservationTypes"
                    autocomplete
                    :allowNew="false"
                    field="name"
                    icon="tag"
                    :placeholder="trans('labels.field_observations.types_placeholder')"
                    @typing="onTypeTyping"
                    @keyup.native.delete="onTypeBackspace"
                    open-on-focus>
                </b-taginput>
            </b-field>

            <b-field
                :label="trans('labels.field_observations.number')"
                :type="form.errors.has('number') ? 'is-danger' : null"
                :message="form.errors.has('number') ? form.errors.first('number') : null"
            ><b-input type="number" v-model="form.number" /></b-field>

            <b-field
                :label="trans('labels.field_observations.note')"
                :error="form.errors.has('note')"
                :message="form.errors.has('note') ? form.errors.first('note') : null"
            ><b-input type="textarea" v-model="form.note" /></b-field>

            <div class="field">
                <label for="found_on" class="label">
                    <b-tooltip :label="trans('labels.field_observations.found_on_tooltip')" multilined dashed>
                        {{ trans('labels.field_observations.found_on') }}
                    </b-tooltip>
                </label>

                <b-input id="found_on" name="found_on" v-model="form.found_on" />

                <p
                    v-if="form.errors.has('found_on')"
                    v-html="form.errors.first('found_on')"
                    class="help"
                    :class="{ 'is-danger': form.errors.has('found_on') }"
                />
            </div>

            <b-field
                :label="trans('labels.field_observations.time')"
                :type="form.errors.has('time') ? 'is-danger' : null"
                :message="form.errors.has('time') ? form.errors.first('time') : null"
            >
                <b-timepicker
                    :value="time"
                    @input="onTimeInput"
                    :placeholder="trans('labels.field_observations.click_to_select')"
                    icon="clock-o"
                    :mobile-native="false"
                >
                    <button type="button" class="button is-danger"
                        @click="form.time = null">
                        <b-icon icon="close"></b-icon>
                    </button>
                </b-timepicker>
            </b-field>

            <div class="field">
                <label for="project" class="label">
                    <b-tooltip :label="trans('labels.field_observations.project_tooltip')" multilined dashed>
                        {{ trans('labels.field_observations.project') }}
                    </b-tooltip>
                </label>

                <b-input id="project" name="project" v-model="form.project" />

                <p
                    v-if="form.errors.has('project')"
                    v-html="form.errors.first('project')"
                    class="help"
                    :class="{ 'is-danger': form.errors.has('project') }"
                />
            </div>

            <b-checkbox v-model="form.found_dead">{{ trans('labels.field_observations.found_dead') }}</b-checkbox>

            <b-field
                :label="trans('labels.field_observations.found_dead_note')"
                v-if="form.found_dead"
                :error="form.errors.has('found_dead_note')"
                :message="form.errors.has('found_dead_note') ? form.errors.first('found_dead_note') : null"
            ><b-input type="textarea" v-model="form.found_dead_note" /></b-field>

            <template v-if="isCuratorOrAdmin">
                <b-field
                    :label="trans('labels.field_observations.observer')"
                    :type="form.errors.has('observer') ? 'is-danger' : null"
                    :message="form.errors.has('observer') ? form.errors.first('observer') : null"
                ><b-input v-model="form.observer" /></b-field>

                <b-field
                    :label="trans('labels.field_observations.identifier')"
                    :type="form.errors.has('identifier') ? 'is-danger' : null"
                    :message="form.errors.has('identifier') ? form.errors.first('identifier') : null"
                ><b-input v-model="form.identifier" /></b-field>
            </template>

            <b-field
                :label="trans('labels.field_observations.data_license')"
                :type="form.errors.has('data_license') ? 'is-danger' : null"
                :message="form.errors.has('data_license') ? form.errors.first('data_license') : null"
            >
                <b-select v-model="form.data_license">
                    <option :value="null">{{ trans('labels.field_observations.default') }}</option>
                    <option v-for="(label, value) in licenses" :value="value" v-text="label"></option>
                </b-select>
            </b-field>

            <b-field
                :label="trans('labels.field_observations.image_license')"
                :type="form.errors.has('image_license') ? 'is-danger' : null"
                :message="form.errors.has('image_license') ? form.errors.first('image_license') : null"
            >
                <b-select v-model="form.image_license">
                    <option :value="null">{{ trans('labels.field_observations.default') }}</option>
                    <option v-for="(label, value) in licenses" :value="value" v-text="label"></option>
                </b-select>
            </b-field>
        </div>

        <hr>

        <button
            type="submit"
            class="button is-primary"
            :class="{
                'is-loading': submittingWithRedirect
            }"
            @click.prevent="submitWithRedirect"
        >
            {{ trans('buttons.save') }}
        </button>

        <button
            type="submit"
            class="button is-primary"
            :class="{
                'is-outlined': !submittingWithoutRedirect,
                'is-loading': submittingWithoutRedirect
            }"
            @click.prevent="submitWithoutRedirect"
            v-if="submitMore"
        >
            {{ trans('buttons.save_more') }}
        </button>

        <a :href="cancelUrl" class="button is-text" @click="onCancel">{{ trans('buttons.cancel') }}</a>
    </form>
</template>

<script>
import collect from 'collect.js';
import Form from 'form-backend-validation';
import FormMixin from '../../mixins/FormMixin';
import UserMixin from '../../mixins/UserMixin';

export default {
    name: 'nzFieldObservationForm',

    mixins: [FormMixin, UserMixin],

    props: {
        photoUploadUrl: {
            type: String,
            required: true
        },

        photoRemoveUrl: {
            type: String,
            required: true
        },

        observation: {
            type: Object,
            default() {
                return {
                    taxon: null,
                    taxon_id: null,
                    taxon_suggestion: '',
                    year: moment().year(),
                    month: moment().month() + 1,
                    day: moment().date(),
                    latitude: null,
                    longitude: null,
                    accuracy: null,
                    elevation: null,
                    location: '',
                    photos: [],
                    observer: '',
                    identifier: '',
                    note: '',
                    sex: null,
                    number: null,
                    project: null,
                    found_on: null,
                    stage_id: null,
                    found_dead: false,
                    found_dead_note: '',
                    data_license: null,
                    image_license: null,
                    time: null,
                    types: []
                };
            }
        },

        licenses: {
            type: Object,
            default: () => { return {} }
        },

        sexes: {
            type: Array,
            default: () => []
        },

        observationTypes: {
            type: Array,
            default: () => []
        }
    },

    data() {
        return {
            keepAfterSubmit: this.getAttributesToKeep(),
            showMoreDetails: false,
            locale: window.App.locale,
            observationTypeSearch: '',
            shouldClearType: false
        };
    },

    computed: {
        stages() {
            return this.form.taxon ? this.form.taxon.stages : [];
        },

        time() {
            return this.form.time ? moment(this.form.time, 'HH:mm').toDate() : null
        },

        selectedObservationTypes: {
            get() {
                return this.observationTypes.filter(type =>
                    _.includes(this.form.observation_types_ids, type.id)
                );
            },

            set(value) {
                this.form.observation_types_ids = value.map(type => type.id);
            }
        },

        availableObservationTypes() {
            return this.observationTypes.filter(type => {
                return !_.includes(this.form.observation_types_ids, type.id)
                    && type.name.toLowerCase().includes(this.observationTypeSearch.toLowerCase());
            });
        }
    },

    created() {
        if (!this.selectedObservationTypes.length) {
            this.pushSelectedObservationType(
                _.find(this.availableObservationTypes, { slug: 'observed' })
            );
        }
    },

    methods: {
        /**
         * Create new form instance.
         *
         * @return {Form}
         */
        newForm() {
            return new Form({
                ...this.observation,
                observation_types_ids: this.observation.types.map(type => type.id),
                reason: null
            }, {
                resetOnSuccess: false
            })
        },

        /**
         * Performa after submit without redirect is successful.
         */
        hookAfterSubmitWithoutRedirect() {
            // Focus on taxon autocomplete input.
            this.$refs.taxonAutocomplete.$el.querySelector('input').focus();
        },

        /**
         * Handle year input.
         *
         * @param {Number} value
         */
        onYearInput(value) {
            this.form.year = value;
        },

        /**
         * Handle month input.
         *
         * @param {Number} value
         */
        onMonthInput(value) {
            this.form.month = value;
        },

        /**
         * Handle daz input.
         *
         * @param {Number} value
         */
        onDayInput(value) {
            this.form.day = value;
        },

        /**
         * Handle taxon veing selected.
         *
         * @param {Object} value
         */
        onTaxonSelect(taxon) {
            this.form.taxon = taxon || null;
            this.form.taxon_id = taxon ? taxon.id : null;

            const invalidStage = !collect(this.stages).contains(stage => {
                return stage.id === this.form.stage_id;
            });

            if (invalidStage) {
              this.form.stage_id = null
            }
        },

        onTimeInput(value) {
            this.form.time = value ? moment(value).format('HH:mm') : null;
        },

        /**
         * Add uploaded photo's filename to array.
         *
         * @param {String} file name
         */
        onPhotoUploaded(image) {
            this.form.photos.push(image);

            const availableType = _.find(this.availableObservationTypes, { slug: 'photographed' });

            if (availableType) {
                this.pushSelectedObservationType(availableType);
            }
        },

        /**
         * Remove photo from form.
         *
         * @param {String} file name
         */
        onPhotoRemoved(image) {
            _.remove(this.form.photos, { path: image.path });

            const selectedTypeIndex = _.findIndex(this.selectedObservationTypes, { slug: 'photographed' });

            if (!this.form.photos.length && selectedTypeIndex >= 0) {
                const selectedObservationTypes = this.selectedObservationTypes;

                selectedObservationTypes.splice(selectedTypeIndex, 1);

                this.selectedObservationTypes = selectedObservationTypes;
            }
        },

        /**
         * Get observation photo attribute.
         *
         * @param  {Number} [photoIndex=0]
         * @param  {String} [attribute='url']
         * @return {String}
         */
        getObservationPhotoAttribute(photoIndex = 0, attribute = 'url') {
            return _.get(this.observation, `photos.${photoIndex}.${attribute}`, '');
        },

        /**
         * Attributes to keep after submit without redirect.
         *
         * @return {Array}
         */
        getAttributesToKeep() {
            return [
                'location', 'accuracy', 'elevation', 'latitude', 'longitude',
                'year', 'month', 'day', 'project'
            ];
        },

        /**
         * Set tag search.
         * @param {String} value
         */
        onTypeTyping(value) {
            this.observationTypeSearch = value;
        },

        /**
         * Handle removing tags with backspace.
         */
        onTypeBackspace() {
            if (this.shouldClearType) {
                this.form.observation_types_ids.splice(-1, 1);
                this.shouldClearType = false;
            } else if (!this.observationTypeSearch) {
                this.shouldClearType = true;
            }
        },

        pushSelectedObservationType(type) {
            const selectedObservationTypes = this.selectedObservationTypes;

            selectedObservationTypes.push(type);

            this.selectedObservationTypes = selectedObservationTypes;
        }
    }
}
</script>
