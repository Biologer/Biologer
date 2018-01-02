import moment from 'moment';
import collect from 'collect.js';
import Form from 'form-backend-validation';

export default {
    name: 'field-observation-form',

    props: {
        action: {
            type: String,
            required: true
        },

        method: {
            type: String,
            default: 'post'
        },

        redirect: {
            type: String,
            default: () => route('contributor.field-observations.index')
        },

        observation: {
            type: Object,
            default() {
                return {
                    taxon: null,
                    taxon_id: null,
                    taxon_suggestion: null,
                    year: moment().year(),
                    month: moment().month() + 1,
                    day: moment().date(),
                    latitude: null,
                    longitude: null,
                    accuracy: null,
                    elevation: null,
                    location: null,
                    photos: [],
                    observer: null,
                    identifier: null,
                    note: null,
                    sex: null,
                    number: null,
                    stage_id: null,
                    found_dead: false,
                    found_dead_note: null,
                    data_license: null,
                    image_license: null
                };
            }
        }
    },

    data() {
        return {
            form: new Form({
                ...this.observation
            }, {
                resetOnSuccess: false
            }),
            showMoreDetails: false
        };
    },

    computed: {
      stages() {
        return this.form.taxon ? this.form.taxon.stages : [];
      }
    },

    methods: {
        /**
         * Submit the form.
         */
        submit() {
            if (this.form.processing) {
                return;
            }

            this.form[this.method.toLowerCase()](this.action)
                .then(this.onSuccessfulSubmit)
                .catch(this.onFailedSubmit);
        },

        /**
         * Handle successful form submit.
         */
        onSuccessfulSubmit() {
            this.form.processing = true

            this.$toast.open({
                message: 'Saved successfully',
                type: 'is-success'
            });

            // We want to wait a bit before we send the user to redirect route
            // so we can show the message that the action was successful.
            setTimeout(() => {
                this.form.processing = false;

                window.location.href = this.redirect;
            }, 500);
        },

        /**
         * Handle failed form submit.
         *
         * @param {Error} error
         */
        onFailedSubmit(error) {
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

            if (!this.stages.length || !collect(this.stages).contains(stage => {
              return stage.id === this.form.stage_id
            })) {
              this.form.stage_id = null
            }
        },

        /**
         * Add uploaded photo's filename to array.
         *
         * @param {String} file name
         */
        onPhotoUploaded(file) {
            this.form.photos.push(file);
        },

        /**
         * Remove photo from form.
         *
         * @param {String} file name
         */
        onPhotoRemoved(file) {
            this.form.photos.splice(this.form.photos.indexOf(file), 1);
        }
    }
}
