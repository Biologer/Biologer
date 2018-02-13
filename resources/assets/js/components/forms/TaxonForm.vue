<template lang="html">
    <form @submit.prevent="submit">
        <div class="columns">
            <div class="column is-4">
                <nz-taxon-autocomplete :label="trans('labels.taxa.parent')"
                    v-model="parentName"
                    @select="onTaxonSelect"
                    :error="form.errors.has('parent_id')"
                    :message="form.errors.first('parent_id')"
                    :taxon="taxon.parent || null"
                    :except="taxon.id"
                    :placeholder="trans('labels.taxa.search_for_taxon')"
                    autofocus />
            </div>

            <div class="column is-5">
                <b-field :label="trans('labels.taxa.name')"
                    :type="form.errors.has('name') ? 'is-danger' : ''"
                    :message="form.errors.has('name') ? form.errors.first('name') : ''">
                    <b-input v-model="form.name" />
                </b-field>
            </div>

            <div class="column is-3">
                <b-field :label="trans('labels.taxa.rank')"
                    :type="form.errors.has('rank') ? 'is-danger' : ''"
                    :message="form.errors.has('rank') ? form.errors.first('rank') : ''">
                    <b-select v-model="form.rank" expanded>
                        <option
                            v-for="(rank, index) in rankOptions"
                            :value="rank.value"
                            :key="index"
                            v-text="rank.label" />
                    </b-select>
                </b-field>
            </div>
        </div>

        <b-field :label="trans('labels.taxa.author')"
            :type="form.errors.has('author') ? 'is-danger' : ''"
            :message="form.errors.has('author') ? form.errors.first('author') : ''">
            <b-input v-model="form.author" />
        </b-field>

        <hr>

        <b-field :label="trans('labels.taxa.native_name')">
            <b-tabs size="is-small" class="block">
                <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
                    <b-input v-model="form.native_name[locale]" />
                </b-tab-item>
            </b-tabs>
        </b-field>

        <b-field :label="trans('labels.taxa.description')">
            <b-tabs size="is-small" class="block">
                <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
                    <b-input type="textarea" v-model="form.description[locale]" />
                </b-tab-item>
            </b-tabs>
        </b-field>

        <hr>

        <div class="columns">
            <div class="column is-half">
                <b-field :label="trans('labels.taxa.fe_old_id')"
                    :type="form.errors.has('fe_old_id') ? 'is-danger' : ''"
                    :message="form.errors.has('fe_old_id') ? form.errors.first('fe_old_id') : ''">
                    <b-input v-model="form.fe_old_id"></b-input>
                </b-field>
            </div>

            <div class="column is-half">
                <b-field :label="trans('labels.taxa.fe_id')"
                    :type="form.errors.has('fe_id') ? 'is-danger' : ''"
                    :message="form.errors.has('fe_id') ? form.errors.first('fe_id') : ''">
                    <b-input v-model="form.fe_id"></b-input>
                </b-field>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <b-field :label="trans('labels.taxa.restricted')">
                    <div class="field">
                        <b-switch v-model="form.restricted">
                            {{ form.restricted ? trans('labels.taxa.yes') : trans('labels.taxa.no') }}
                        </b-switch>
                    </div>
                </b-field>
            </div>
            <div class="column">
                <b-field :label="trans('labels.taxa.allochthonous')">
                    <b-switch v-model="form.allochthonous">
                        {{ form.allochthonous ? trans('labels.taxa.yes') : trans('labels.taxa.no') }}
                    </b-switch>
                </b-field>
            </div>
            <div class="column">
                <b-field :label="trans('labels.taxa.invasive')">
                    <b-switch v-model="form.invasive">
                        {{ form.invasive ? trans('labels.taxa.yes') : trans('labels.taxa.no') }}
                    </b-switch>
                </b-field>
            </div>
        </div>

        <b-field :label="trans('labels.taxa.stages')" v-if="stages.length">
            <div class="block">
                <b-checkbox
                    v-for="stage in stages"
                    :key="stage.id"
                    v-model="form.stages_ids"
                    :native-value="stage.id"
                >
                    {{ trans('stages.' + stage.name) }}
                </b-checkbox>
            </div>
        </b-field>

        <b-field :label="trans('labels.taxa.conservation_lists')" v-if="conservationLists.length">
            <div class="block">
                <div class="columns is-multiline is-gapless">
                    <div class="column is-half" v-for="conservationList in conservationLists" :key="conservationList.id">
                        <b-tooltip :label="conservationList.description"
                            multilined>
                            <b-checkbox
                                v-model="form.conservation_lists_ids"
                                :native-value="conservationList.id"
                            >
                                {{ conservationList.name }}
                            </b-checkbox>
                        </b-tooltip>
                    </div>
                </div>
            </div>
        </b-field>

        <div class="field" v-if="redLists.length">
            <label class="label">{{ trans('labels.taxa.red_lists') }}</label>

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
                    <button type="button" class="button" @click="addRedList">{{ trans('labels.taxa.add_red_list') }}</button>
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
            {{ trans('buttons.save') }}
        </button>
        <a :href="redirect" class="button is-text">{{ trans('buttons.cancel') }}</a>
    </form>
</template>

<script>
import Form from 'form-backend-validation';
import collect from 'collect.js';

function defaultTranslations() {
    const value = {};

    _.keys(window.supportedLocales).forEach(locale => {
        value[locale] = null;
    });

    return value;
}

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
                    rank: 'species',
                    rank_level: 10,
                    author: null,
                    fe_id: null,
                    fe_old_id: null,
                    conservation_lists: [],
                    red_lists: [],
                    stages: [],
                    restricted: false,
                    allochthonous: false,
                    invasive: false,
                    restricted: false
                };
            }
        },
        ranks: Array,
        conservationLists: Array,
        redListCategories: Array,
        redLists: {
            type: Array,
            default() { return []; }
        },
        stages: Array,
        nativeNames: {
            type: Object,
            default: () => defaultTranslations()
        },
        descriptions: {
            type: Object,
            default: () => defaultTranslations()
        }
    },

    data() {
        return {
            form: new Form({
                ...this.taxon,
                stages_ids: this.taxon.stages.map(stage => stage.id),
                conservation_lists_ids: this.taxon.conservation_lists.map(conservationList => conservationList.id),
                red_lists_data: this.taxon.red_lists.map(redList => {
                  return { red_list_id: redList.id, category: redList.pivot.category }
                }),
                native_name: this.nativeNames,
                description: this.descriptions,
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
                    return rank.level < this.selectedParent.rank_level;
                })
            }

            return this.ranks;
        },

        availableRedLists() {
            let addedRedListIds = collect(this.form.red_lists_data)

            return this.redLists.filter(redList => {
                return !addedRedListIds.contains(rl => redList.id == rl.red_list_id);
            });
        },

        supportedLocales() {
            return window.App.supportedLocales;
        }
    },

    watch: {
        selectedParent(value) {
            if (this.shouldResetRank(value)) {
                this.form.rank = null;
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
                message: this.trans('Saved successfully'),
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
        },

        shouldResetRank(selectedParent) {
            return selectedParent &&
                this.getRankLevel(this.form.rank) >= selectedParent.rank_level;
        },

        getRankLevel(rank) {
            return _.get(_.find(this.ranks, { value: rank }), 'level');
        }
    }
}
</script>
