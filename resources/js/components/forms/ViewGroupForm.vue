<template>
    <form :action="action" method="POST" @submit.prevent="submitWithRedirect">
        <b-field :label="trans('labels.view_groups.parent')">
            <b-select v-model="parent">
                <option></option>
                <option v-for="rootGroup in rootGroups" :value="rootGroup">{{ rootGroup.name }}</option>
            </b-select>
        </b-field>

        <b-field :label="trans('labels.view_groups.name')">
            <b-tabs size="is-small" class="block" @change="(index) => focusOnTranslation(index, 'name')">
                <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
                    <b-input v-model="form.name[locale]" :id="`name-${locale}`" />
                </b-tab-item>
            </b-tabs>
        </b-field>

        <b-field :label="trans('labels.view_groups.description')">
            <b-tabs size="is-small" class="block" @change="(index) => focusOnTranslation(index, 'description')">
                <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
                    <b-input type="textarea" v-model="form.description[locale]" :id="`description-${locale}`" />
                </b-tab-item>
            </b-tabs>
        </b-field>

        <b-field :label="trans('labels.view_groups.taxa')" v-if="!isRoot">
            <b-taginput v-model="taxa"
                :data="filteredTaxa"
                autocomplete
                field="name"
                @typing="onTaxonNameInput"
            ></b-taginput>
        </b-field>

        <hr>

        <button type="submit"
            class="button is-primary"
            :class="{
                'is-loading': form.processing
            }"
            @click="submitWithRedirect">
            {{ trans('buttons.save') }}
        </button>

        <a :href="cancelUrl" class="button is-text" @click="onCancel">{{ trans('buttons.cancel') }}</a>
    </form>
</template>

<script>
import Form from 'form-backend-validation';
import collect from 'collect.js';
import FormMixin from '../../mixins/FormMixin';

function defaultTranslations() {
    const value = {};

    _.keys(window.App.supportedLocales).forEach(locale => {
        value[locale] = null;
    });

    return value;
}

export default {
    name: 'nzViewGroupForm',

    mixins: [FormMixin],

    props: {
        group: {
            type: Object,
            default() {
                return {
                    parent_id: null,
                    taxa: []
                };
            }
        },
        rootGroups: Array,
        names: {
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
            form: this.newForm(),
            taxa: this.group.taxa,
            filteredTaxa: []
        };
    },

    computed: {
        supportedLocales() {
            return window.App.supportedLocales;
        },

        parent: {
            get() {
                return _.find(this.rootGroups, { id: this.form.parent_id });
            },

            set(value) {
                this.form.parent_id = _.get(value, 'id');
            }
        },

        isRoot() {
            return !this.form.parent_id;
        }
    },

    watch: {
        taxa(value) {
            this.form.taxa_ids = value.map(taxon => taxon.id);
        }
    },

    methods: {
        newForm() {
            return new Form({
                parent_id: this.group.parent_id,
                name: this.names,
                description: this.descriptions,
                taxa_ids: this.group.taxa.map(taxon => taxon.id)
            }, {
                resetOnSuccess: false
            });
        },

        focusOnTranslation(index, attribute) {
            const locales = _.keys(this.supportedLocales);
            const selector = `#${attribute}-${locales[index]}`;

            setTimeout(() => {
                this.$el.querySelector(selector).focus();
            }, 500)
        },

        fetchTaxa(name) {
            axios.get(route('api.taxa.index'), {
                params: {
                    name,
                    page: 1,
                    per_page: 10,
                    except: this.taxa.map(taxon => taxon.id)
                }
            }).then(({ data }) => {
                this.filteredTaxa = data.data;
            });
        },

        onTaxonNameInput: _.debounce(function (name) {
            return this.fetchTaxa(name)
        }, 500)
    }
}
</script>
