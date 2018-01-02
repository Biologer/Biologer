import Form from 'form-backend-validation';
import collect from 'collect.js';

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
            default() {
                return route('admin.taxa.index');
            }
        },
        taxon: {
            type: Object,
            default() {
                return {
                    name: null,
                    parent_id: null,
                    rank_level: 10,
                    fe_id: null,
                    fe_old_id: null,
                    conventions_ids: [],
                    red_lists_data: [],
                    restricted: false
                };
            }
        },
        ranks: Array,
        conventions: Array,
        redListCategories: Array,
        redLists: {
            type: Array,
            default() { return []; }
        },
        stages: Array,
    },

    data() {
        return {
            form: new Form({
                ...this.taxon
            }, {
                resetOnSuccess: false
            }),
            parentName: this.taxon && this.taxon.parent ? this.taxon.parent.name : null,
            selectedParent: null,
            chosenRedList: null
        };
    },

    computed: {
        rankOptions() {
            if (this.selectedParent) {
                return this.ranks.filter((rank) => {
                    return rank.value < this.selectedParent.rank_level;
                })
            }

            return this.ranks;
        },

        availableRedLists() {
            let addedRedListIds = collect(this.form.red_lists_data)

            return this.redLists.filter(redList => {
                return !addedRedListIds.contains(rl => redList.id == rl.red_list_id);
            });
        }
    },

    watch: {
        selectedParent() {
            if (this.selectedParent &&
                this.form.rank_level >= this.selectedParent.rank_level
            ) {
                this.form.rank_level = null;
            }
        }
    },

    methods: {
        /**
         * Add field to the form.
         */
        addRedList() {
            if (!this.chosenRedList) return;

            this.form.red_lists_data.push({
                red_list_id: this.chosenRedList.id,
                category: _.first(this.redListCategories)
            });

            this.chosenRedList = null;
        },

        /**
         * Don't use the field any more.
         *
         * @param  {Object} field
         */
        removeRedList(index) {
            this.form.red_lists_data.splice(index, 1)
        },

        getRedListName(id) {
            return _.find(this.redLists, { id }).name;
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
                message: _.get(error, 'response.data.message', error.message),
                type: 'is-danger'
            });
        },

        /**
         * Handle taxon being selected.
         *
         * @param {Object} taxon
         */
        onTaxonSelect(taxon) {
            this.selectedParent = taxon;
            this.form.parent_id = taxon ? taxon.id : null;
        }
    }
}
