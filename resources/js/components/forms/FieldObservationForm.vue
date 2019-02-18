<template>
    <form :action="action" method="POST" :lang="locale" class="field-observation-form">
        <div class="columns is-desktop">
            <div class="column is-half-desktop">
                <nz-taxon-autocomplete
                    v-model="form.taxon_suggestion"
                    @select="onTaxonSelect"
                    :taxon="observation.taxon"
                    :error="form.errors.has('taxon_id')"
                    :message="form.errors.has('taxon_id') ? form.errors.first('taxon_id') : null"
                    autofocus
                    ref="taxonAutocomplete"
                    :label="trans('labels.field_observations.taxon')"
                    :placeholder="trans('labels.field_observations.search_for_taxon')" />

                <nz-date-input
                    :year.sync="form.year"
                    :month.sync="form.month"
                    :day.sync="form.day"
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
                                :image-url="getObservationPhotoAttribute(0, 'url')"
                                :image-path="getObservationPhotoAttribute(0, 'path')"
                                :image-license="getObservationPhotoAttribute(0, 'license')"
                                :licenses="licenses"
                                :text="trans('labels.field_observations.upload')"
                                @uploaded="onPhotoUploaded"
                                @removed="onPhotoRemoved"
                                @cropped="onPhotoCropped"
                                @license-changed="onLicenseChanged(0, $event)"
                                :errors="form.errors"
                                ref="photoUpload-1"
                            ></nz-photo-upload>
                        </div>

                        <div class="column is-one-third">
                            <nz-photo-upload
                                :image-url="getObservationPhotoAttribute(1, 'url')"
                                :image-path="getObservationPhotoAttribute(1, 'path')"
                                :image-license="getObservationPhotoAttribute(1, 'license')"
                                :licenses="licenses"
                                :text="trans('labels.field_observations.upload')"
                                @uploaded="onPhotoUploaded"
                                @removed="onPhotoRemoved"
                                @cropped="onPhotoCropped"
                                @license-changed="onLicenseChanged(1, $event)"
                                :errors="form.errors"
                                ref="photoUpload-2"
                            ></nz-photo-upload>
                        </div>

                        <div class="column is-one-third">
                            <nz-photo-upload
                                :image-url="getObservationPhotoAttribute(2, 'url')"
                                :image-path="getObservationPhotoAttribute(2, 'path')"
                                :image-license="getObservationPhotoAttribute(2, 'license')"
                                :licenses="licenses"
                                :text="trans('labels.field_observations.upload')"
                                @uploaded="onPhotoUploaded"
                                @removed="onPhotoRemoved"
                                @cropped="onPhotoCropped"
                                @license-changed="onLicenseChanged(2, $event)"
                                :errors="form.errors"
                                ref="photoUpload-3"
                            ></nz-photo-upload>
                        </div>
                    </div>
                </b-field>
            </div>

            <div class="column is-half-desktop">
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
            {{ showMoreDetails ? trans('labels.field_observations.less_details') : trans('labels.field_observations.more_details') }}
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
                        <b-select v-model="form.sex" expanded>
                            <option :value="null">{{ trans('labels.field_observations.choose_a_value') }}</option>
                            <option v-for="sex in sexes" :value="sex" v-text="trans('labels.sexes.'+sex)"></option>
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
                    <span class="is-dashed" v-tooltip="{content: trans('labels.field_observations.found_on_tooltip')}">
                        {{ trans('labels.field_observations.found_on') }}
                    </span>
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

            <div class="columns">
                <div class="column">
                    <div class="field">
                        <label for="project" class="label">
                            <span class="is-dashed" v-tooltip="{content: trans('labels.field_observations.project_tooltip')}">
                                {{ trans('labels.field_observations.project') }}
                            </span>
                        </label>

                        <b-input id="project" name="project" v-model="form.project" />

                        <p
                            v-if="form.errors.has('project')"
                            v-html="form.errors.first('project')"
                            class="help"
                            :class="{ 'is-danger': form.errors.has('project') }"
                        />
                    </div>
                </div>

                <div class="column">
                    <div class="field">
                        <label for="dataset" class="label">
                            {{ trans('labels.field_observations.dataset') }}
                        </label>

                        <b-input id="dataset" name="dataset" v-model="form.dataset" />

                        <p
                            v-if="form.errors.has('dataset')"
                            v-html="form.errors.first('dataset')"
                            class="help"
                            :class="{ 'is-danger': form.errors.has('dataset') }"
                        />
                    </div>
                </div>
            </div>

            <b-checkbox v-model="form.found_dead">{{ trans('labels.field_observations.found_dead') }}</b-checkbox>

            <b-field
                :label="trans('labels.field_observations.found_dead_note')"
                v-if="form.found_dead"
                :error="form.errors.has('found_dead_note')"
                :message="form.errors.has('found_dead_note') ? form.errors.first('found_dead_note') : null"
            ><b-input type="textarea" v-model="form.found_dead_note" /></b-field>

            <template v-if="showObserverIdentifier">
                <nz-user-autocomplete
                    v-model="form.observer"
                    @select="onObserverSelect"
                    :error="form.errors.has('observer')"
                    :message="form.errors.has('observer') ? form.errors.first('observer') : null"
                    :user="form.observed_by"
                    :label="trans('labels.field_observations.observer')"
                    :placeholder="currentUser.full_name"
                />

                <nz-user-autocomplete
                    v-model="form.identifier"
                    @select="onIdentifierSelect"
                    :error="form.errors.has('identifier')"
                    :message="form.errors.has('identifier') ? form.errors.first('identifier') : null"
                    :user="form.identified_by"
                    :label="trans('labels.field_observations.identifier')"
                    :disabled="!isIdentified"
                />
            </template>

            <div class="columns">
                <div class="column">
                    <b-field
                        :label="trans('labels.field_observations.data_license')"
                        :type="form.errors.has('data_license') ? 'is-danger' : null"
                        :message="form.errors.has('data_license') ? form.errors.first('data_license') : null"
                    >
                        <b-select v-model="form.data_license" expanded>
                            <option :value="null">{{ trans('labels.field_observations.default') }}</option>
                            <option v-for="(label, value) in licenses" :value="value" v-text="label"></option>
                        </b-select>
                    </b-field>
                </div>
            </div>
        </div>

        <hr>

        <button
            type="submit"
            class="button is-primary"
            :class="{
                'is-loading': submittingWithRedirect
            }"
            @click.prevent="submitWithRedirect"
            v-tooltip="{content: trans('labels.field_observations.save_tooltip')}"
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
            v-tooltip="{content: trans('labels.field_observations.save_more_tooltip')}"
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
                    types: [],
                    observed_by_id: null,
                    observed_by: null,
                    identified_by_id: null,
                    identified_by: null,
                    dataset: null
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
        },

        showObserverIdentifier: Boolean
    },

    data() {
        return {
            keepAfterSubmit: this.getAttributesToKeep(),
            showMoreDetails: false,
            locale: window.App.locale,
            observationTypeSearch: '',
            shouldClearType: false,
            exifExtracted: false
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
        },

        isIdentified() {
            return !!(this.form.taxon_id || this.form.taxon_suggestion);
        },

        /**
         * Check if identification is changed compared to the original observation.
         * @return {Boolean}
         */
        identificationChanged() {
            return this.form.taxon_id != this.observation.taxon_id ||
              this.form.taxon_suggestion != this.observation.taxon_suggestion;
        }
    },

    created() {
        this.setDefaultObservationType();
    },

    methods: {
        /**
         * Create new form instance.
         *
         * @return {Form}
         */
        newForm() {
            const observation = this.observation;

            // If crop is not set on existing photos, we can't crop them.
            observation.photos.forEach(photo => {
               photo.crop = null;
            });

            return new Form({
                ...observation,
                observation_types_ids: observation.types.map(type => type.id),
                reason: null
            }, {
                resetOnSuccess: false
            })
        },

        /**
         * Performa after submit without redirect is successful.
         */
        hookAfterSubmitWithoutRedirect() {
            this.setDefaultObservationType();
            this.clearPhotos();
            // Focus on taxon autocomplete input.
            this.$refs.taxonAutocomplete.focusOnInput();
        },

        /**
         * Clear all photos.
         */
        clearPhotos() {
            _.range(1, 3).forEach(number => {
                this.$refs[`photoUpload-${number}`].clearPhoto();
            });
        },

        /**
         * Handle taxon veing selected.
         *
         * @param {Object} value
         */
        onTaxonSelect(taxon) {
            this.form.taxon = taxon || null;
            this.form.taxon_id = taxon ? taxon.id : null;
            this.form.taxon_suggestion = taxon ? taxon.name : null;

            const invalidStage = !collect(this.stages).contains(stage => {
                return stage.id === this.form.stage_id;
            });

            if (invalidStage) {
              this.form.stage_id = null
            }

            this.updateIdentifier();
        },

        /**
         * Set time.
         */
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

            this.promptToExtractExifData(image);
        },

        /**
         * Remove photo from form.
         *
         * @param {Object} image
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
         * Add/remove cropping information.
         *
         * @param {Object} image
         */
        onPhotoCropped(croppedImage) {
            const photoIndex = _.findIndex(this.form.photos, { path: croppedImage.path });

            if (photoIndex >= 0) {
                const photo = _.cloneDeep(this.form.photos[photoIndex]);

                photo.crop = croppedImage.crop;

                this.form.photos.splice(photoIndex, 1, photo);
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
                'year', 'month', 'day', 'project', 'observer', 'observed_by',
                'observed_by_id', 'data_license', 'image_license',
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

        /**
         * Add observation type to selections.
         *
         * @param  {Object} type
         */
        pushSelectedObservationType(type) {
            const selectedObservationTypes = this.selectedObservationTypes;

            selectedObservationTypes.push(type);

            this.selectedObservationTypes = selectedObservationTypes;
        },

        /**
         * Ask the user to use EXIF data to populate the fields.
         *
         * @param  {Object} image
         */
        promptToExtractExifData(image) {
            if (this.exifExtracted || !image.exif) return;

            this.$dialog.confirm({
                message: this.trans('Use data from photo to fill the form?'),
                cancelText: this.trans('No'),
                confirmText: this.trans('Yes'),
                onConfirm: () => { this.extractExifData(image); },
                onCancel: () => { this.exifExtracted = true; }
            });
        },

        /**
         * Fill the fields with EXIF data.
         *
         * @param  {Object} image
         */
        extractExifData(image) {
            for (let exif in image.exif) {
                let value = image.exif[exif];

                if (value) {
                   this.form[exif] = value;
                }
            }

            this.exifExtracted = true;
        },

        /**
         * Select observer.
         */
        onObserverSelect(user) {
            this.form.observed_by = user || null;
            this.form.observed_by_id = user ? user.id : null;
            this.form.observer = user ? user.full_name : null;
        },

        /**
         * Select identifier.
         */
        onIdentifierSelect(user) {
            this.form.identified_by = user || null;
            this.form.identified_by_id = user ? user.id : null;
            this.form.identifier = user ? user.full_name : null;
        },

        /**
         * Set default observation type.
         */
        setDefaultObservationType() {
            if (!this.selectedObservationTypes.length) {
                this.pushSelectedObservationType(
                    _.find(this.availableObservationTypes, { slug: 'observed' })
                );
            }
        },

        /**
         * Update identifier based on identification change.
         */
        updateIdentifier() {
            let identifier = this.observation.identified_by;

            if (this.identificationChanged) {
              identifier = this.isIdentified ? window.App.User : null;
            }

            this.onIdentifierSelect(identifier);
        },

        /**
         * Change photo license.
         *
         * @param {Number} photoIndex
         * @param {Number} license
         */
        onLicenseChanged(photoIndex, license) {
          const photo = _.cloneDeep(this.form.photos[photoIndex]);

          photo.license = license;

          this.form.photos[photoIndex] = photo;
        }
    }
}
</script>
