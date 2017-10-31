import Form from 'form-backend-validation';

export default {
    name: 'nzTaxonForm',

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
                return route('admin.taxa.index');
            }
        },
        taxon: {
            type: Object,
            default () {
                return {
                    name: null,
                    parent_id: null,
                    category_level: 10
                };
            }
        },
        
        categories: Object
    },

    data() {
        return {
            form: new Form({
                ...this.taxon
            }, {
                http: window.axios
            }),
            parentName: this.taxon && this.taxon.parent ? this.taxon.parent.name : null,
        };
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

        onFailedSubmit(error) {
            this.$toast.open({
                duration: 2500,
                message: error.response.data.message,
                type: 'is-danger'
            });
        },

        onTaxonSelect(taxon) {
            this.form.parent_id = taxon ? taxon.id : null;
        }
    }
}
