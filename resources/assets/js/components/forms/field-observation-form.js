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
            default () {
                return route('contributor.field-observations.index');
            }
        },
        dataDynamicFields: {
            type: Array,
            default () {
                return [];
            }
        },
        observation: {
            type: Object,
            default () {
                return {
                    taxon_id: null,
                    taxon_suggestion: null,
                    year: moment().year(),
                    month: moment().month() + 1,
                    day: moment().date(),
                    latitude: null,
                    longitude: null,
                    accuracy: 10,
                    elevation: null,
                    location: null,
                    photos: [],
                    dynamic_fields: [],
                    source: null,
                    data_license: null,
                    image_license: null,
                    comment: null
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
            dynamicFields: [],
            chosenField: null
        };
    },

    created() {
        this.updateFields();
    },

    methods: {
        /**
         * Add field to the form.
         */
        addField() {
            this.form.dynamic_fields.push({
                name: this.chosenField.name,
                value: this.chosenField.value || this.chosenField.default
            });
            this.chosenField = null;
            this.updateFields();
        },

        /**
         * Don't use the field any more.
         *
         * @param  {Object} field
         */
        removeField(field) {
            _.remove(this.form.dynamic_fields, (item) => item.name === field.name);
            this.updateFields();
        },

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
                message: error.response.data.message,
                type: 'is-danger'
            });
        },

        /**
         * Update dynamic fields' values.
         */
        updateFields() {
            this.dynamicFields = this.dataDynamicFields.filter((field) => {
                return collect(this.form.dynamic_fields).contains((item) => item.name === field.name);
            }).map((field) => {
                let value = _.find(this.form.dynamic_fields, (item) => item.name === field.name).value;
                field.value =  value || field.value || field.default;

                return field;
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
            this.form.taxon_id = taxon ? taxon.id : null;
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
    },

    computed: {
        /**
         * Fields that have not been used yet.
         *
         * @return {Array} of field data
         */
        availableDynamicFields() {
            return this.dataDynamicFields.filter((availableField) => {
                return !collect(this.dynamicFields).contains((field) => {
                    return availableField.name === field.name;
                });
            }).map((field) => {
                field.value = field.value || field.default;
                return field;
            });
        }
    }
}
