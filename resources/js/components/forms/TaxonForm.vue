<template>
  <form :action="action" method="POST" @submit.prevent="submitWithRedirect">
    <div class="columns">
      <div class="column is-4">
        <nz-taxon-autocomplete
          :label="trans('labels.taxa.parent')"
          v-model="parentName"
          @select="onTaxonSelect"
          :error="form.errors.has('parent_id')"
          :message="form.errors.first('parent_id')"
          :taxon="taxon.parent || null"
          :except="taxon.id"
          :placeholder="trans('labels.taxa.search_for_taxon')"
          autofocus
        />

      </div>

      <div class="column is-5">
        <b-field
          :label="trans('labels.taxa.name')"
          class="is-required"
          :type="form.errors.has('name') ? 'is-danger' : ''"
          :message="form.errors.has('name') ? form.errors.first('name') : ''"
        >
          <b-input v-model="form.name" />
        </b-field>
      </div>

      <div class="column is-3">
        <b-field
          :label="trans('labels.taxa.rank')"
          class="is-required"
          :type="form.errors.has('rank') ? 'is-danger' : ''"
          :message="form.errors.has('rank') ? form.errors.first('rank') : ''"
        >
          <b-select v-model="form.rank" expanded>
            <option
              v-for="(rank, index) in rankOptions"
              :value="rank.value"
              :key="index"
              v-text="rank.label"
            />
          </b-select>
        </b-field>
      </div>
    </div>

    <b-field
      :label="trans('labels.taxa.author')"
      :type="form.errors.has('author') ? 'is-danger' : ''"
      :message="form.errors.has('author') ? form.errors.first('author') : ''"
    >
      <b-input v-model="form.author" />
    </b-field>

    <hr>

    <b-field :label="trans('labels.taxa.native_name')">
      <b-tabs size="is-small" class="block" @change="(index) => focusOnTranslation(index, 'native_name')">
        <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
          <b-input v-model="form.native_name[locale]" :ref="`native_name-${locale}`" />
        </b-tab-item>
      </b-tabs>
    </b-field>

    <b-field :label="trans('labels.taxa.description')">
      <b-tabs size="is-small" class="block" @change="(index) => focusOnTranslation(index, 'description')">
        <b-tab-item :label="trans('languages.' + data.name)" v-for="(data, locale) in supportedLocales" :key="locale">
          <nz-wysiwyg v-model="form.description[locale]" :ref="`description-${locale}`" />
        </b-tab-item>
      </b-tabs>
    </b-field>

    <hr>


    <b-field
      :label="trans('labels.taxa.fe_id')"
      :type="form.errors.has('fe_id') ? 'is-danger' : ''"
      :message="form.errors.has('fe_id') ? form.errors.first('fe_id') : ''"
    >
      <b-input v-model="form.fe_id" />
    </b-field>


    <div class="columns">
      <div class="column">
        <b-field :label="trans('labels.taxa.restricted')">
          <div class="field">
            <b-switch v-model="form.restricted">
              {{ form.restricted ? trans('Yes') : trans('No') }}
            </b-switch>
          </div>
        </b-field>
      </div>
      <div class="column">
        <b-field :label="trans('labels.taxa.allochthonous')">
          <b-switch v-model="form.allochthonous">
            {{ form.allochthonous ? trans('Yes') : trans('No') }}
          </b-switch>
        </b-field>
      </div>
      <div class="column">
        <b-field :label="trans('labels.taxa.invasive')">
          <b-switch v-model="form.invasive">
            {{ form.invasive ? trans('Yes') : trans('No') }}
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

    <b-field :label="trans('labels.taxa.conservation_legislations')" v-if="conservationLegislations.length">
      <div class="block">
        <div class="columns is-multiline is-gapless">
          <div class="column is-half" v-for="conservationLegislation in conservationLegislations" :key="conservationLegislation.id">
            <b-tooltip :label="conservationLegislation.description" multilined>
              <b-checkbox
                v-model="form.conservation_legislations_ids"
                :native-value="conservationLegislation.id"
              >
                {{ conservationLegislation.name }}
              </b-checkbox>
            </b-tooltip>
          </div>
        </div>
      </div>
    </b-field>

    <b-field :label="trans('labels.taxa.conservation_documents')" v-if="conservationDocuments.length">
      <div class="block">
        <div class="columns is-multiline is-gapless">
          <div class="column is-half" v-for="conservationDocument in conservationDocuments" :key="conservationDocument.id">
            <b-tooltip :label="conservationDocument.description" multilined>
              <b-checkbox
                v-model="form.conservation_documents_ids"
                :native-value="conservationDocument.id"
              >
                {{ conservationDocument.name }}
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

    <b-field :label="trans('labels.taxa.atlas_codes')">
      <b-checkbox v-model="form.uses_atlas_codes">
        {{ trans('labels.taxa.uses_atlas_codes') }}
      </b-checkbox>
    </b-field>

    <hr>

    <div class="columns">

      <div class="column">
        <b-field
          :label="'Species ID'"
          class="is-required"
          :type="form.errors.has('spid') ? 'is-danger' : ''"
          :message="form.errors.has('spid') ? form.errors.first('spid') : ''"
        >
          <b-input maxlength="10" v-model="form.spid" />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="'BirdLife sequence'"
          class="is-required"
          :type="form.errors.has('birdlife_seq') ? 'is-danger' : ''"
          :message="form.errors.has('birdlife_seq') ? form.errors.first('birdlife_seq') : ''"
        >
          <b-input type="number" v-model="form.birdlife_seq" />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="'BirdLife ID'"
          class="is-required"
          :type="form.errors.has('birdlife_id') ? 'is-danger' : ''"
          :message="form.errors.has('birdlife_id') ? form.errors.first('birdlife_id') : ''"
        >
          <b-input type="number" v-model="form.birdlife_id" />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="'EBBA code'"
          class="is-required"
          :type="form.errors.has('ebba_code') ? 'is-danger' : ''"
          :message="form.errors.has('ebba_code') ? form.errors.first('ebba_code') : ''"
        >
          <b-input type="number" v-model="form.ebba_code" />
        </b-field>
      </div>

    </div>

    <div class="columns">
      <div class="column">
        <b-field
          :label="'EURING code'"
          class="is-required"
          :type="form.errors.has('euring_code') ? 'is-danger' : ''"
          :message="form.errors.has('euring_code') ? form.errors.first('euring_code') : ''"
        >
          <b-input type="number" v-model="form.euring_code" />
        </b-field>
      </div>
      <div class="column">
        <b-field
          :label="'EURING SCI name'"
          class="is-required"
          :type="form.errors.has('euring_code') ? 'is-danger' : ''"
          :message="form.errors.has('euring_code') ? form.errors.first('euring_code') : ''"
        >
          <b-input maxlength="100" v-model="form.euring_sci_name" />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="'EUNIS N2000 code'"
        >
          <b-input maxlength="10" v-model="form.eunis_n2000code" />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="'EUNIS SCI name'"
        >
          <b-input maxlength="100" v-model="form.eunis_sci_name" />
        </b-field>
      </div>
    </div>

    <div class="columns">
      <div class="column">
        <b-field
          :label="'BioRas SCI name'"
        >
          <b-input maxlength="200" v-model="form.bioras_sci_name" />
        </b-field>
      </div>


      <div class="column">
        <b-field
          :label="'SG'"
        >
          <b-input maxlength="10" v-model="form.sg" />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="'GN Status'"
        >
          <b-input maxlength="10" v-model="form.gn_status" />
        </b-field>
      </div>

      <div class="column">
        <b-field
          :label="'Prior'"
        >
          <b-select v-model="form.prior" expanded>
            <option value="null">null</option>
            <option value="PR">PR</option>
            <option value="PR+">PR+</option>
          </b-select>
        </b-field>
      </div>

    </div>

    <b-field
      :label="'REFER'"
    >
      <b-switch v-model="form.refer" />
    </b-field>


    <hr>

    <button
      type="submit"
      class="button is-primary"
      :class="{
          'is-loading': form.processing
      }"
      @click="submitWithRedirect"
    >
      {{ trans('buttons.save') }}
    </button>

    <a :href="cancelUrl" class="button is-text" @click="onCancel">{{ trans('buttons.cancel') }}</a>
  </form>
</template>

<script>
import Form from 'form-backend-validation'
import _keys from 'lodash/keys'
import _find from 'lodash/find'
import _first from 'lodash/first'
import _get from 'lodash/get'
import FormMixin from '@/mixins/FormMixin'
import NzWysiwyg from '@/components/inputs/Wysiwyg'
import NzTaxonAutocomplete from '@/components/inputs/TaxonAutocomplete'

function defaultTranslations() {
  const value = {}

  _keys(window.App.supportedLocales).forEach(locale => {
    value[locale] = null
  })

  return value
}

export default {
  name: 'nzTaxonForm',

  mixins: [FormMixin],

  components: {
    NzWysiwyg,
    NzTaxonAutocomplete
  },

  props: {
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
          conservation_legislations: [],
          conservation_documents: [],
          red_lists: [],
          stages: [],
          restricted: false,
          allochthonous: false,
          invasive: false,
          uses_atlas_codes: false,
          spid: null,
          birdlife_seq: null,
          birdlife_id: null,
          ebba_code: null,
          euring_code: null,
          euring_sci_name: null,
          eunis_n2000code: null,
          eunis_sci_name: null,
          bioras_sci_name: null,
          refer: null,
          prior: null,
          sg: null,
          gn_status: null,
        }
      }
    },
    ranks: Array,
    conservationLegislations: Array,
    conservationDocuments: Array,
    redListCategories: Array,
    redLists: {
      type: Array,
      default() { return [] }
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
      form: this.newForm(),
      parentName: _get(this.taxon, 'parent.name'),
      selectedParent: null,
      chosenRedList: null
    }
  },

  computed: {
    rankOptions() {
      if (this.selectedParent) {
        return this.ranks.filter((rank) => rank.level < this.selectedParent.rank_level)
      }

      return this.ranks
    },

    availableRedLists() {
      const addedRedListIds = this.form.red_lists_data

      return this.redLists.filter(redList => !_find(addedRedListIds, rl => redList.id == rl.red_list_id))
    },

    supportedLocales() {
      return window.App.supportedLocales
    }
  },

  watch: {
    selectedParent(value) {
      if (this.shouldResetRank(value)) {
        this.form.rank = null
      }
    }
  },

  methods: {
    newForm() {
      return new Form({
        ...this.taxon,
        stages_ids: this.taxon.stages.map(stage => stage.id),
        conservation_legislations_ids: this.taxon.conservation_legislations.map(conservationLegislation => conservationLegislation.id),
        conservation_documents_ids: this.taxon.conservation_documents.map(conservationDocument => conservationDocument.id),
        red_lists_data: this.taxon.red_lists.map(redList => {
          return { red_list_id: redList.id, category: redList.pivot.category }
        }),
        native_name: this.nativeNames,
        description: this.descriptions,
        reason: null,
        uses_atlas_codes: this.taxon.uses_atlas_codes,
        spid: this.taxon.spid,
        birdlife_seq: this.taxon.birdlife_seq,
        birdlife_id: this.taxon.birdlife_id,
        ebba_code: this.taxon.ebba_code,
        euring_code: this.taxon.euring_code,
        euring_sci_name: this.taxon.euring_sci_name,
        eunis_n2000code: this.taxon.eunis_n2000code,
        eunis_sci_name: this.taxon.eunis_sci_name,
        bioras_sci_name: this.taxon.bioras_sci_name,
        refer: this.taxon.refer,
        prior: this.taxon.prior,
        sg: this.taxon.sg,
        gn_status: this.taxon.gn_status,
      }, {
        resetOnSuccess: false
      })
    },

    /**
     * Add field to the form.
     */
    addRedList() {
      if (!this.chosenRedList) return

      this.form.red_lists_data.push({
        red_list_id: this.chosenRedList.id,
        category: _first(this.redListCategories)
      })

      this.chosenRedList = null
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
        return _find(this.redLists, { id }).name
    },

    /**
     * Handle taxon being selected.
     *
     * @param {Object} taxon
     */
    onTaxonSelect(taxon) {
      this.selectedParent = taxon
      this.form.parent_id = taxon ? taxon.id : null

      // Inherit parent's stages
      if (taxon && taxon.stages.length) {
        this.form.stages_ids = taxon.stages.map(stage => stage.id)
      }
    },

    shouldResetRank(selectedParent) {
      return selectedParent && this.getRankLevel(this.form.rank) >= selectedParent.rank_level
    },

    /**
     * Get rank level.
     * @param {Object} rank
     * @return {Number}
     */
    getRankLevel(rank) {
      return _get(_find(this.ranks, { value: rank }), 'level')
    },

    focusOnTranslation(index, attribute) {
      const locales = _keys(this.supportedLocales)
      const selector = `${attribute}-${locales[index]}`

      setTimeout(() => {
        _first(this.$refs[selector]).focus()
      }, 500)
    }
  }
}
</script>
