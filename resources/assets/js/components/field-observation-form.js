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
                return route('field-observations.index');
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
                    altitude: null,
                    location: null,
                    photos: [],
                    dynamic_fields: []
                };
            }
        }
    },

    data() {
        return {
            form: new Form({
                ...this.observation
            }),
            dynamicFields: [],
            chosenField: null,
            submitting: false
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
            if (this.submitting) {
                return;
            }

            this.sumitting = true
            this.form[this.method.toLowerCase()](this.action).then(() => {
                this.submitting = false
                window.location.href = this.redirect;
            }).catch(() => {
                this.submitting = false
            });
        },

        updateFields() {
            this.dynamicFields = this.dataDynamicFields.filter((field) => {
                return collect(this.form.dynamic_fields).contains((item) => item.name === field.name);
            }).map((field) => {
                let value = _.find(this.form.dynamic_fields, (item) => item.name === field.name).value;
                field.value =  value || field.value || field.default;

                return field;
            });
        },

        onYearInput(value) {
            this.form.year = value;
        },

        onMonthInput(value) {
            this.form.month = value;
        },

        onDayInput(value) {
            this.form.day = value;
        },

        onTaxonSelect(taxon) {
            this.form.taxon_id = taxon ? taxon.id : null;
        },

        onPhotoUploaded(file) {
            this.form.photos.push(file);
        },

        onPhotoRemoved(file) {
            this.form.photos.splice(this.form.photos.indexOf(file), 1);
        }
    },

    computed: {
        /**
         * Fields that have not been used yet.
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
