<template>
    <div class="field-observation-form">
        <div class="columns">
            <div class="column is-half">
                <nz-taxon-autocomplete v-model="form.taxon_suggestion"
                    @select="onTaxonSelect"
                    :error="form.errors.has('taxon_id')"
                    :message="form.errors.has('taxon_id') ? form.errors.first('taxon_id') : null"
                    autofocus
                    ref="taxonAutocomplete">
                </nz-taxon-autocomplete>

                <nz-date-input
                    :data-year="form.year"
                    :data-month="form.month"
                    :data-day="form.day"
                    v-on:year-input="onYearInput"
                    v-on:month-input="onMonthInput"
                    v-on:day-input="onDayInput"
                    :errors="form.errors"
                ></nz-date-input>

                <b-field label="Photos">
                    <div class="columns">
                        <div class="column is-one-third">
                            <nz-photo-upload
                                :upload-url="photoUploadUrl"
                                :remove-url="photoRemoveUrl"
                                :image-url="getObservationPhotoAttribute(0, 'url')"
                                :image-path="getObservationPhotoAttribute(0, 'path')"
                                text="Upload"
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
                                text="Upload"
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
                                text="Upload"
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

        <button type="button" class="button is-text" @click="showMoreDetails = !showMoreDetails">More details</button>

        <div class="mt-4" v-show="showMoreDetails">
            <b-field
                label="Note"
                :error="form.errors.has('note')"
                :message="form.errors.has('note') ? form.errors.first('note') : null"
            >
                <b-input
                    type="textarea"
                    v-model="form.note"
                ></b-input>
            </b-field>

            <b-field
                label="Number"
                :type="form.errors.has('number') ? 'is-danger' : null"
                :message="form.errors.has('number') ? form.errors.first('number') : null"
            >
                <b-input
                    type="number"
                    v-model="form.number">
                </b-input>
            </b-field>

            <b-field
                label="Sex"
                :type="form.errors.has('sex') ? 'is-danger' : null"
                :message="form.errors.has('sex') ? form.errors.first('sex') : null"
            >
                <b-select v-model="form.sex">
                    <option :value="null">Choose a value</option>
                    <option v-for="sex in sexes" :value="sex" v-text="sex"></option>
                </b-select>
            </b-field>

            <b-field
                label="Stage"
                :type="form.errors.has('stage_id') ? 'is-danger' : null"
                :message="form.errors.has('stage_id') ? form.errors.first('stage_id') : null"
            >
                <b-select v-model="form.stage_id" :disabled="!stages.length">
                    <option :value="null">Choose a stage</option>
                    <option v-for="stage in stages" :value="stage.id" :key="stage.id" v-text="stage.name"></option>
                </b-select>
            </b-field>

            <b-field
                label="Time"
                :type="form.errors.has('time') ? 'is-danger' : null"
                :message="form.errors.has('time') ? form.errors.first('time') : null"
            >
                <b-timepicker
                    :value="time"
                    @input="onTimeInput"
                    placeholder="Click to select..."
                    icon="clock-o"
                >
                    <button type="button" class="button is-danger"
                        @click="form.time = null">
                        <b-icon icon="close"></b-icon>
                    </button>
                </b-timepicker>
            </b-field>

            <template v-if="isCuratorOrAdmin">
                <b-field
                    label="Observer"
                    :type="form.errors.has('observer') ? 'is-danger' : null"
                    :message="form.errors.has('observer') ? form.errors.first('observer') : null"
                >
                    <b-input v-model="form.observer"></b-input>
                </b-field>

                <b-field
                    label="Identifier"
                    :type="form.errors.has('identifier') ? 'is-danger' : null"
                    :message="form.errors.has('identifier') ? form.errors.first('identifier') : null"
                >
                    <b-input v-model="form.identifier"></b-input>
                </b-field>
            </template>

            <b-checkbox v-model="form.found_dead">Found Dead?</b-checkbox>

            <b-field
                label="Note on dead observation"
                v-if="form.found_dead"
                :error="form.errors.has('found_dead_note')"
                :message="form.errors.has('found_dead_note') ? form.errors.first('found_dead_note') : null"
              >
                <b-input
                    type="textarea"
                    v-model="form.found_dead_note"
                ></b-input>
            </b-field>

            <b-field
                label="Data License"
                :type="form.errors.has('data_license') ? 'is-danger' : null"
                :message="form.errors.has('data_license') ? form.errors.first('data_license') : null"
            >
                <b-select v-model="form.data_license">
                    <option :value="null">Default</option>
                    <option v-for="(label, value) in licenses" :value="value" v-text="label"></option>
                </b-select>
            </b-field>

            <b-field
                label="Image License"
                :type="form.errors.has('image_license') ? 'is-danger' : null"
                :message="form.errors.has('image_license') ? form.errors.first('image_license') : null"
            >
                <b-select v-model="form.image_license">
                    <option :value="null">Default</option>
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
            @click="submitAndRedirect"
        >
            Save
        </button>

        <button
            type="submit"
            class="button is-primary"
            :class="{
                'is-outlined': !submittingWithoutRedirect,
                'is-loading': submittingWithoutRedirect
            }"
            @click="submitAndAddMore"
            v-if="saveMore"
        >
            Save (more)
        </button>

        <a :href="redirect" class="button is-text">Cancel</a>
    </div>
</template>

<script>
import moment from 'moment';
import collect from 'collect.js';
import Form from 'form-backend-validation';
import User from '../../models/user';

export default {
    name: 'nzFieldObservationForm',

    props: {
        action: {
            type: String,
            required: true
        },

        method: {
            type: String,
            default: 'POST'
        },

        redirect: {
            type: String,
        },

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
                    stage_id: null,
                    found_dead: false,
                    found_dead_note: '',
                    data_license: null,
                    image_license: null,
                    time: null
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
        saveMore: Boolean
    },

    data() {
        return {
            form: this.newForm(this.observation),
            showMoreDetails: false,
            user: new User(window.App.User),
            submittingWithRedirect: false,
            submittingWithoutRedirect: false
        };
    },

    computed: {
        stages() {
            return this.form.taxon ? this.form.taxon.stages : [];
        },

        isCuratorOrAdmin() {
            return this.user.hasRole(['admin', 'curator']);
        },

        time() {
            return this.form.time ? moment(this.form.time, 'HH:mm').toDate() : null
        }
    },

    created() {
        document.addEventListener('keyup', this.registerKeyListener);
    },

    beforeDestroy() {
        document.removeEventListener('keyup', this.registerKeyListener);
    },

    methods: {
        /**
         * Create new form instance.
         *
         * @param  {Object} data default form data
         * @return {Form}
         */
        newForm(data) {
            return new Form({
                ...data
            }, {
                resetOnSuccess: false
            })
        },

        /**
         * Submit the form with redirect.
         */
        submitAndRedirect() {
            if (this.form.processing) {
                return;
            }

            this.submittingWithRedirect = true;

            this.form[this.method.toLowerCase()](this.action)
                .then(this.onSuccessfulSubmitWithRedirect)
                .catch(this.onFailedSubmit);
        },

        /**
         * Submit the form and stay to add more.
         */
        submitAndAddMore() {
            if (this.form.processing) {
                return;
            }

            this.submittingWithoutRedirect = true;

            this.form[this.method.toLowerCase()](this.action)
                .then(this.onSuccessfulSubmitAndAddMore)
                .catch(this.onFailedSubmit);
        },

        /**
         * Handle successful form submit with redirect.
         */
        onSuccessfulSubmitWithRedirect() {
            this.form.processing = true

            this.$toast.open({
                message: 'Saved successfully',
                type: 'is-success'
            });

            // We want to wait a bit before we send the user to redirect route
            // so we can show the message that the action was successful.
            setTimeout(() => {
                this.form.processing = false;
                this.submittingWithRedirect = false;

                if (this.redirect) {
                    window.location.href = this.redirect;
                }
            }, 500);
        },

        /**
         * Handle successful form submit with no redirect.
         */
        onSuccessfulSubmitAndAddMore() {
            this.submittingWithoutRedirect = false;

            this.$toast.open({
                message: 'Saved successfully',
                type: 'is-success'
            });

            // Reset the form but remember date and location data.
            this.form = this.newForm({
                ...this.observation,
                ..._.pick(this.form, [
                    'location', 'accuracy', 'elevation', 'latitude', 'longitude',
                    'year', 'month', 'day'
                ])
            });

            // Focus on taxon autocomplete input.
            this.$refs.taxonAutocomplete.$el.querySelector('input').focus();
        },

        /**
         * Handle failed form submit.
         *
         * @param {Error} error
         */
        onFailedSubmit(error) {
            this.submittingWithRedirect = false;
            this.submittingWithoutRedirect = false;

            this.$toast.open({
                duration: 2500,
                message: _.get(error, 'response.data.message', error.message),
                type: 'is-danger'
            });
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
        },

        /**
         * Remove photo from form.
         *
         * @param {String} file name
         */
        onPhotoRemoved(image) {
            _.remove(this.form.photos, { path: image.path })
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
         * Keyboard shortcuts.
         *
         * @param {Event} e
         */
        registerKeyListener(e) {
            const enter = 13 === (e.which || e.keyCode);

            if (this.saveMore && e.ctrlKey && e.shiftKey && enter) {
                this.submitAndAddMore();
            } else if (e.ctrlKey && enter) {
                this.submitAndRedirect();
            }
        }
    }
}
</script>
