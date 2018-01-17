<template lang="html">
    <form @submit.prevent="submit">
        <div class="columns">
            <div class="column is-5">
                <nz-taxon-autocomplete label="Parent"
                    v-model="parentName"
                    @select="onTaxonSelect"
                    :error="form.errors.has('parent_id')"
                    :message="form.errors.first('parent_id')"
                    :taxon="taxon.parent || null"
                    :except="taxon.id"
                    autofocus>
                </nz-taxon-autocomplete>
            </div>

            <div class="column is-5">
                <b-field label="Name"
                    :type="form.errors.has('name') ? 'is-danger' : ''"
                    :message="form.errors.has('name') ? form.errors.first('name') : ''">
                    <b-input v-model="form.name"></b-input>
                </b-field>
            </div>

            <div class="column is-2">
                <b-field label="Rank"
                    :type="form.errors.has('rank_level') ? 'is-danger' : ''"
                    :message="form.errors.has('rank_level') ? form.errors.first('rank_level') : ''">
                    <b-select placeholder="Select rank" v-model="form.rank_level">
                        <option
                            v-for="(rank, index) in rankOptions"
                            :value="rank.value"
                            :key="index"
                            v-text="rank.name">
                        </option>
                    </b-select>
                </b-field>
            </div>
        </div>

        <div class="columns">
            <div class="column is-half">
                <b-field label="(old) FaunaEuropea ID"
                    :type="form.errors.has('fe_old_id') ? 'is-danger' : ''"
                    :message="form.errors.has('fe_old_id') ? form.errors.first('fe_old_id') : ''">
                    <b-input v-model="form.fe_old_id"></b-input>
                </b-field>
            </div>

            <div class="column is-half">
                <b-field label="FaunaEuropea ID"
                    :type="form.errors.has('fe_id') ? 'is-danger' : ''"
                    :message="form.errors.has('fe_id') ? form.errors.first('fe_id') : ''">
                    <b-input v-model="form.fe_id"></b-input>
                </b-field>
            </div>
        </div>

        <b-field label="Is taxon data restricted?">
            <div class="field">
                <b-switch v-model="form.restricted">
                    {{ form.restricted ? 'Yes' : 'No' }}
                </b-switch>
            </div>
        </b-field>

        <b-field label="Stages" v-if="stages.length">
            <div class="block">
                <b-checkbox
                    v-for="stage in stages"
                    :key="stage.id"
                    v-model="form.stages_ids"
                    :native-value="stage.id"
                >
                    {{ stage.name }}
                </b-checkbox>
            </div>
        </b-field>

        <b-field label="Conventions" v-if="conventions.length">
            <div class="block">
                <b-checkbox
                    v-for="convention in conventions"
                    :key="convention.id"
                    v-model="form.conventions_ids"
                    :native-value="convention.id"
                >
                    {{ convention.name }}
                </b-checkbox>
            </div>
        </b-field>

        <div class="field" v-if="redLists.length">
            <label class="label">Red Lists</label>

            <b-field v-for="(addedRedList, index) in form.red_lists_data" :key="index" grouped>
                <div class="control is-expanded">
                    <span v-text="getRedListName(addedRedList.red_list_id)"></span>
                </div>

                <b-select v-model="addedRedList.category">
                    <option v-for="category in redListCategories" :value="category" :key="category" v-text="category">
                    </option>
                </b-select>

                <div class="control">
                    <button type="button" class="button has-text-danger" @click="removeRedList(index)">&times;</button>
                </div>
            </b-field>

            <b-field grouped v-if="availableRedLists.length">
                <b-select v-model="chosenRedList" expanded>
                    <option v-for="option in availableRedLists" :value="option" :key="option.id" v-text="option.name">
                    </option>
                </b-select>


                <div class="control">
                    <button type="button" class="button" @click="addRedList">Add red list</button>
                </div>
            </b-field>
        </div>

        <hr>

        <button type="submit"
            class="button is-primary"
            :class="{
                'is-loading': form.processing
            }"
            @click="submit">
            Save
        </button>
        <a :href="redirect" class="button is-text">Cancel</a>
    </form>
</template>

<script>
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
                    conventions: [],
                    red_lists: [],
                    stages: [],
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
                red_lists_data: this.taxon.red_lists.map(redList => {
                  return { red_list_id: redList.id, category: redList.pivot.category }
                }),
                conventions_ids: this.taxon.conventions.map(convention => convention.id),
                stages_ids: this.taxon.stages.map(stage => stage.id),
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
</script>
