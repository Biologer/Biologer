<template>
  <form :action="action" method="POST" :lang="locale" class="literature-observation-form">
    <div class="mb-8">
      <nz-publication-autocomplete
        class="is-required"
        v-model="publicationCitation"
        @select="onPublicationSelect"
        :publication="form.publication"
        :error="form.errors.has('publication_id')"
        :message="form.errors.has('publication_id') ? form.errors.first('publication_id') : null"
        autofocus="autofocus"
        ref="publicationAutocomplete"
        :label="trans('labels.literature_observations.publication')"
        :placeholder="trans('labels.literature_observations.search_for_publication')"
      >
        <a
          class="button"
          :title="trans('labels.literature_observations.add_new_publication')"
          :href="$ziggy('admin.publications.create')"
          @click.prevent="addNewPublication"
        >
          <b-icon icon="plus" />
        </a>
      </nz-publication-autocomplete>

      <b-field
        class="is-required"
        :label="trans('labels.literature_observations.is_original_data')"
        :error="form.errors.has('is_original_data')"
        :message="form.errors.has('is_original_data') ? form.errors.first('is_original_data') : null"
      >
        <b-select :value="form.is_original_data" @input="handleIsOriginalDataInput" expanded>
          <option :value="true">{{ trans('labels.literature_observations.original_data') }}</option>
          <option :value="false">{{ trans('labels.literature_observations.citation') }}</option>
        </b-select>
      </b-field>

      <nz-publication-autocomplete
        v-show="!form.is_original_data"
        v-model="citedPublication"
        @select="onCitedPublicationSelect"
        class="is-required"
        :publication="form.cited_publication"
        :error="form.errors.has('cited_publication_id')"
        :message="form.errors.has('cited_publication_id') ? form.errors.first('cited_publication_id') : null"
        :label="trans('labels.literature_observations.cited_publication')"
        :placeholder="trans('labels.literature_observations.search_for_publication')"
      >
        <a
          class="button"
          :title="trans('labels.literature_observations.add_new_publication')"
          :href="$ziggy('admin.publications.create')"
          @click.prevent="addNewPublication"
        >
          <b-icon icon="plus" />
        </a>
      </nz-publication-autocomplete>

      <nz-taxon-autocomplete
        v-model="taxonName"
        @select="onTaxonSelect"
        class="is-required"
        :taxon="form.taxon"
        :error="form.errors.has('taxon_id')"
        :message="form.errors.has('taxon_id') ? form.errors.first('taxon_id') : null"
        autofocus="autofocus"
        ref="taxonAutocomplete"
        :label="trans('labels.literature_observations.taxon')"
        :placeholder="trans('labels.literature_observations.search_for_taxon')"
      />

      <nz-date-input
        :year.sync="form.year"
        :month.sync="form.month"
        :day.sync="form.day"
        :errors="form.errors"
        :label="trans('labels.literature_observations.date')"
        :placeholders="{
            year: trans('labels.literature_observations.year'),
            month: trans('labels.literature_observations.month'),
            day: trans('labels.literature_observations.day')
        }"
      />

      <div>
        <h2 class="is-size-4">{{ trans('labels.literature_observations.verbatim_data') }}</h2>

        <b-field
          class="is-required"
          :label="trans('labels.literature_observations.original_identification')"
          :type="form.errors.has('original_identification') ? 'is-danger' : null"
          :message="form.errors.has('original_identification') ? form.errors.first('original_identification') : null"
        >
          <b-input name="original_identification" v-model="form.original_identification" />
        </b-field>

        <b-field
          class="is-required"
          :label="trans('labels.literature_observations.original_identification_validity')"
          :type="form.errors.has('original_identification_validity') ? 'is-danger' : null"
          :message="form.errors.has('original_identification_validity') ? form.errors.first('original_identification_validity') : null"
        >
          <b-select name="original_identification_validity" v-model="form.original_identification_validity" expanded>
            <option :value="value" v-for="(label, value) in validityOptions" :key="value">{{ label }}</option>
          </b-select>
        </b-field>

        <div class="columns">
          <b-field
            class="column"
            :label="trans('labels.literature_observations.original_date')"
            :type="form.errors.has('original_date') ? 'is-danger' : null"
            :message="form.errors.has('original_date') ? form.errors.first('original_date') : null"
          >
            <b-input name="original_date" v-model="form.original_date"/>
          </b-field>

          <b-field
            class="column"
            :label="trans('labels.literature_observations.original_locality')"
            :type="form.errors.has('original_locality') ? 'is-danger' : null"
            :message="form.errors.has('original_locality') ? form.errors.first('original_locality') : null"
          >
            <b-input v-model="form.original_locality"/>
          </b-field>
        </div>

        <div class="columns">
          <b-field
            class="column"
            :label="trans('labels.literature_observations.original_coordinates')"
            :type="form.errors.has('original_coordinates') ? 'is-danger' : null"
            :message="form.errors.has('original_coordinates') ? form.errors.first('original_coordinates') : null"
          >
            <b-input v-model="form.original_coordinates"/>
          </b-field>

          <b-field
            class="column"
            :label="trans('labels.literature_observations.original_elevation')"
            :type="form.errors.has('original_elevation') ? 'is-danger' : null"
            :message="form.errors.has('original_elevation') ? form.errors.first('original_elevation') : null"
          >
            <b-input v-model="form.original_elevation" :placeholder="trans('labels.literature_observations.original_elevation_placeholder')"/>
          </b-field>
        </div>

        <b-field
          class="mb-8"
          :label="trans('labels.literature_observations.other_original_data')"
          :type="form.errors.has('other_original_data') ? 'is-danger' : null"
          :message="form.errors.has('other_original_data') ? form.errors.first('other_original_data') : null"
        >
           <b-input type="textarea" v-model="form.other_original_data" />
        </b-field>

        <div class="columns">
          <b-field
            class="column"
            :label="trans('labels.literature_observations.collecting_start_year')"
            :type="form.errors.has('collecting_start_year') ? 'is-danger' : null"
            :message="form.errors.has('collecting_start_year') ? form.errors.first('collecting_start_year') : null"
          >
            <b-input v-model="form.collecting_start_year"/>
          </b-field>

          <b-field
            class="column"
            :label="trans('labels.literature_observations.collecting_start_month')"
            :type="form.errors.has('collecting_start_month') ? 'is-danger' : null"
            :message="form.errors.has('collecting_start_month') ? form.errors.first('collecting_start_month') : null"
          >
            <b-input v-model="form.collecting_start_month"/>
          </b-field>
        </div>

        <div class="columns">
          <b-field
            class="column"
            :label="trans('labels.literature_observations.collecting_end_year')"
            :type="form.errors.has('collecting_end_year') ? 'is-danger' : null"
            :message="form.errors.has('collecting_end_year') ? form.errors.first('collecting_end_year') : null"
          >
            <b-input v-model="form.collecting_end_year"/>
          </b-field>

          <b-field
            class="column"
            :label="trans('labels.literature_observations.collecting_end_month')"
            :type="form.errors.has('collecting_end_month') ? 'is-danger' : null"
            :message="form.errors.has('collecting_end_month') ? form.errors.first('collecting_end_month') : null"
          >
            <b-input v-model="form.collecting_end_month"/>
          </b-field>
        </div>
      </div>

      <nz-spatial-input
        class="mb-4"
        :latitude.sync="form.latitude"
        :longitude.sync="form.longitude"
        :location.sync="form.location"
        :accuracy.sync="form.accuracy"
        :elevation.sync="form.elevation"
        @elevation-fetched="handleElevationFetched"
        :errors="form.errors"
        :has-other-errors="form.errors.has('minimum_elevation') || form.errors.has('maximum_elevation')"
      >
        <b-field
          :label="trans('labels.literature_observations.minimum_elevation_m')"
          :type="form.errors.has('minimum_elevation') ? 'is-danger' : null"
          :message="form.errors.has('minimum_elevation') ? form.errors.first('minimum_elevation') : null"
          custom-class="is-small"
        >
          <b-input type="number" size="is-small" v-model.number="form.minimum_elevation"/>
        </b-field>

        <b-field
          :label="trans('labels.literature_observations.maximum_elevation_m')"
          :type="form.errors.has('maximum_elevation') ? 'is-danger' : null"
          :message="form.errors.has('maximum_elevation') ? form.errors.first('maximum_elevation') : null"
          custom-class="is-small"
        >
          <b-input type="number" size="is-small" v-model.number="form.maximum_elevation"/>
        </b-field>
      </nz-spatial-input>

      <div class="columns">
        <div class="column">
          <b-field
            :label="trans('labels.literature_observations.georeferenced_by')"
            :type="form.errors.has('georeferenced_by') ? 'is-danger' : null"
            :message="form.errors.has('georeferenced_by') ? form.errors.first('georeferenced_by') : null"
          >
            <b-input v-model.number="form.georeferenced_by"/>
          </b-field>
        </div>

        <div class="column">
          <b-field
            :label="trans('labels.literature_observations.georeferenced_date')"
            :type="form.errors.has('georeferenced_date') ? 'is-danger' : null"
            :message="form.errors.has('georeferenced_date') ? form.errors.first('georeferenced_date') : null"
          >
            <b-datepicker
              v-model="georeferencedDate"
              :max-date="new Date()"
              :mobile-native="false"
              :date-formatter="dateFormater"
              :first-day-of-week="1"
              placeholder="DD.MM.YYYY"
            >
              <button type="button" class="button is-danger" @click="form.georeferenced_date = null">
                <b-icon icon="close" />
                <span>Clear</span>
              </button>
            </b-datepicker>
          </b-field>
        </div>
      </div>

      <b-field :label="trans('labels.literature_observations.stage')" :type="form.errors.has('stage_id') ? 'is-danger' : null" :message="form.errors.has('stage_id') ? form.errors.first('stage_id') : null">
        <b-select v-model="form.stage_id" :disabled="!stages.length" expanded="expanded">
          <option :value="null">{{ trans('labels.literature_observations.choose_a_stage') }}</option>
          <option v-for="stage in stages" :value="stage.id" :key="stage.id" v-text="trans('stages.'+stage.name)"></option>
        </b-select>
      </b-field>

      <b-field :label="trans('labels.literature_observations.sex')" :type="form.errors.has('sex') ? 'is-danger' : null" :message="form.errors.has('sex') ? form.errors.first('sex') : null">
        <b-select v-model="form.sex" expanded="expanded">
          <option :value="null">{{ trans('labels.literature_observations.choose_a_value') }}</option>
          <option v-for="(label,sex) in sexes" :key="sex" :value="sex" v-text="label"></option>
        </b-select>
      </b-field>

      <b-field
        :label="trans('labels.literature_observations.number')"
        :type="form.errors.has('number') ? 'is-danger' : null"
        :message="form.errors.has('number') ? form.errors.first('number') : null"
      >
        <b-input type="number" v-model="form.number"/>
      </b-field>

      <div class="mt-4">
        <b-field :label="trans('labels.literature_observations.note')" :error="form.errors.has('note')" :message="form.errors.has('note') ? form.errors.first('note') : null"><b-input type="textarea" v-model="form.note"/></b-field>

        <b-field
          :label="trans('labels.literature_observations.habitat')"
          :type="form.errors.has('habitat') ? 'is-danger' : null"
          :message="form.errors.has('habitat') ? form.errors.first('habitat') : null"
        >
          <b-input v-model="form.habitat"/>
        </b-field>

        <b-field
          :label="trans('labels.literature_observations.found_on')"
          :type="form.errors.has('found_on') ? 'is-danger' : null"
          :message="form.errors.has('found_on') ? form.errors.first('found_on') : null"
        >
          <label for="found_on" class="label" slot="label">
            <span class="is-dashed" v-tooltip="{content: trans('labels.literature_observations.found_on_tooltip')}">
              {{ trans('labels.literature_observations.found_on') }}
            </span>
          </label>

          <b-input id="found_on" name="found_on" v-model="form.found_on"/>
        </b-field>

        <b-field
          :label="trans('labels.literature_observations.time')"
          :type="form.errors.has('time') ? 'is-danger' : null"
          :message="form.errors.has('time') ? form.errors.first('time') : null"
        >
          <b-timepicker
            :value="time"
            @input="onTimeInput"
            :placeholder="trans('labels.literature_observations.click_to_select')"
            icon="clock-o"
            :mobile-native="false"
          >
            <button type="button" class="button is-danger" @click="form.time = null">
              <b-icon icon="close"></b-icon>
            </button>
          </b-timepicker>
        </b-field>

        <div class="columns">
          <div class="column">
            <div class="field">
              <label for="project" class="label">
                <span class="is-dashed" v-tooltip="{content: trans('labels.literature_observations.project_tooltip')}">
                  {{ trans('labels.literature_observations.project') }}
                </span>
              </label>

              <b-input id="project" name="project" v-model="form.project"/>

              <p v-if="form.errors.has('project')" v-html="form.errors.first('project')" class="help" :class="{ 'is-danger': form.errors.has('project') }"/>
            </div>
          </div>

          <div class="column">
            <div class="field">
              <label for="dataset" class="label">
                {{ trans('labels.literature_observations.dataset') }}
              </label>

              <b-input id="dataset" name="dataset" v-model="form.dataset"/>

              <p v-if="form.errors.has('dataset')" v-html="form.errors.first('dataset')" class="help" :class="{ 'is-danger': form.errors.has('dataset') }"/>
            </div>
          </div>
        </div>

        <b-field
          :label="trans('labels.literature_observations.observer')"
          :type="form.errors.has('observer') ? 'is-danger' : null"
          :message="form.errors.has('observer') ? form.errors.first('observer') : null"
        >
          <b-input name="observer" v-model="form.observer"/>
        </b-field>

        <b-field
          :label="trans('labels.literature_observations.identifier')"
          :type="form.errors.has('identifier') ? 'is-danger' : null"
          :message="form.errors.has('identifier') ? form.errors.first('identifier') : null"
        >
          <b-input name="identifier" v-model="form.identifier"/>
        </b-field>
      </div>
    </div>

    <b-field
      :label="trans('labels.literature_observations.place_where_referenced_in_publication')"
      :type="form.errors.has('place_where_referenced_in_publication') ? 'is-danger' : null"
      :message="form.errors.has('place_where_referenced_in_publication') ? form.errors.first('place_where_referenced_in_publication') : null"
    >
      <b-input v-model="form.place_where_referenced_in_publication" :placeholder="trans('labels.literature_observations.place_where_referenced_in_publication_placeholder')"/>
    </b-field>

    <hr>

    <button
      type="submit"
      class="button is-primary"
      :class="{
        'is-loading': submittingWithRedirect
      }"
      @click.prevent="submitWithRedirect"
      v-tooltip="{content: trans('labels.literature_observations.save_tooltip')}"
    >
      {{ trans('buttons.save') }}
    </button>

    <button
      type="submit"
      class="button is-primary"
      :class="{
        'is-outlined': !submittingWithoutRedirect,
        'is-loading': submittingWithoutRedirect
      }"
      @click.prevent="submitWithoutRedirect"
      v-if="submitMore"
      v-tooltip="{content: trans('labels.literature_observations.save_more_tooltip')}">
      {{ trans('buttons.save_more') }}
    </button>

    <button
      type="button"
      class="button is-primary"
      :class="{
        'is-outlined': !submittingWithoutRedirectWithSameTaxon,
        'is-loading': submittingWithoutRedirectWithSameTaxon
      }"
      @click.prevent="submitWithoutRedirectWithSameTaxon"
      v-if="submitMoreWithSameTaxon"
      v-tooltip="{content: trans('labels.literature_observations.save_more_same_taxon_tooltip')}">
      {{ trans('labels.literature_observations.save_more_same_taxon') }}
    </button>

    <a :href="cancelUrl" class="button is-text" @click="onCancel">{{ trans('buttons.cancel') }}</a>
  </form>
</template>

<script>
import Form from 'form-backend-validation'
import dayjs from '@/dayjs'
import _get from 'lodash/get'
import _find from 'lodash/find'
import _pick from 'lodash/pick'
import FormMixin from '@/mixins/FormMixin'
import PersistentForm from '@/mixins/PersistentForm'
import NzDateInput from '@/components/inputs/DateInput'
import NzSpatialInput from '@/components/inputs/SpatialInput'
import NzPublicationAutocomplete from '@/components/inputs/PublicationAutocomplete'
import NzTaxonAutocomplete from '@/components/inputs/TaxonAutocomplete'

export default {
  name: 'nzLiteratureObservationForm',

  mixins: [PersistentForm, FormMixin],

  components: {
    NzDateInput,
    NzSpatialInput,
    NzPublicationAutocomplete,
    NzTaxonAutocomplete
  },

  props: {
    submitMoreWithSameTaxon: Boolean,

    observation: {
      type: Object,
      default() {
        return {
          original_date: null,
          original_locality: null,
          original_elevation: null,
          original_coordinates: null,
          original_identification: null,
          original_identification_validity: null,
          other_original_data: null,
          collecting_start_year: null,
          collecting_start_month: null,
          collecting_end_year: null,
          collecting_end_month: null,
          place_where_referenced_in_publication: null,
          publication_id: null,
          taxon_id: null,
          year: dayjs().year(),
          month: dayjs().month() + 1,
          day: dayjs().date(),
          is_original_data: true,
          cited_publication_id: null,
          latitude: null,
          longitude: null,
          location: null,
          accuracy: null,
          elevation: null,
          minimum_elevation: null,
          maximum_elevation: null,
          georeferenced_by: null,
          georeferenced_date: null,

          observer: null,
          identifier: null,
          note: null,
          sex: null,
          number: null,
          project: null,
          found_on: null,
          habitat: null,
          stage_id: null,
          time: null,
          dataset: null,

          taxon: null,
          publication: null,
          cited_publication: null
        }
      }
    },

    sexes: {
      type: Object,
      default: () => ({})
    },

    validityOptions: {
      type: [Array, Object],
      default: () => []
    }
  },

  data() {
    return {
      keepAfterSubmit: this.getAttributesToKeep(),
      submittingWithoutRedirectWithSameTaxon: false,
      locale: window.App.locale,
      taxonName: _get(this.observation, 'taxon.name', ''),
      publicationCitation: _get(this.observation, 'publication.citation', ''),
      citedPublication: _get(this.observation, 'cited_publication.citation', '')
    }
  },

  computed: {
    stages() {
      return this.form.taxon
        ? this.form.taxon.stages
        : []
    },

    time() {
      return this.form.time
        ? dayjs(this.form.time, 'HH:mm').toDate()
        : null
    },

    georeferencedDate: {
      get() {
        const val = dayjs(this.form.georeferenced_date)

        return val.isValid() ? val.toDate() : null
      },

      set(value) {
        this.form.georeferenced_date = dayjs(value).format('YYYY-MM-DD')
      }
    }
  },

  created() {
    this.restoreState()
  },

  methods: {
    otherPersistentKeys() {
      return ['taxonName', 'publicationCitation', 'citedPublication']
    },

    /**
     * Create new form instance.
     *
     * @return {Form}
     */
    newForm() {
      return new Form({
        ...this.observation,
        reason: null
      }, {
        resetOnSuccess: false
      })
    },

    /**
     * Handle taxon being selected.
     *
     * @param {Object} value
     */
    onTaxonSelect(taxon) {
      this.form.taxon = taxon || null
      this.form.taxon_id = taxon ? taxon.id : null
      this.taxonName = taxon ? taxon.name : null

      const invalidStage = !_find(this.stages, stage => stage.id === this.form.stage_id)

      if (invalidStage) {
        this.form.stage_id = null
      }
    },

    /**
     * Handle publication being selected.
     *
     * @param {Object} value
     */
    onPublicationSelect(publication) {
      this.form.publication = publication || null
      this.form.publication_id = publication ? publication.id : null
      this.publicationCitation = publication ? publication.citation : null
    },

    /**
     * Handle cited publication being selected.
     *
     * @param {Object} value
     */
    onCitedPublicationSelect(publication) {
      this.form.cited_publication = publication || null
      this.form.cited_publication_id = publication ? publication.id : null
      this.citedPublication = publication ? publication.citation : null
    },

    /**
     * Set time.
     */
    onTimeInput(value) {
      this.form.time = value ? dayjs(value).format('HH:mm') : null
    },

    /**
     * Attributes to keep after submit without redirect.
     *
     * @return {Array}
     */
    getAttributesToKeep() {
      return [
        'location',
        'accuracy',
        'elevation',
        'latitude',
        'longitude',
        'year',
        'month',
        'day',
        'project',
        'observer',
        'identifier',
        'dataset',
        'publication',
        'publication_id',
        'cited_publication',
        'cited_publication_id',
        'is_original_data',
        'minimum_elevation',
        'maximum_elevation',
        'original_locality',
        'original_elevation',
        'original_coordinates',
        'georeferenced_by',
        'georeferenced_date'
      ]
    },

    hookAfterSubmitWithoutRedirect() {
      this.taxonName = ''
    },

    handleIsOriginalDataInput(value) {
      if (value) {
        this.onCitedPublicationSelect(null)
      }

      this.form.is_original_data = value
    },

    addNewPublication() {
      this.saveState()

      window.location.href = route('admin.publications.create')
    },

    dateFormater(date) {
      return dayjs(date).format('DD.MM.YYYY')
    },

    handleElevationFetched(elevation) {
      this.form.minimum_elevation = elevation
      this.form.maximum_elevation = elevation
    },

    /**
     * Submit the form and stay to add more, with same taxon.
     */
    submitWithoutRedirectWithSameTaxon() {
      if (this.form.processing) return

      if (this.submitOnlyDirty  && !this.isDirty()) return this.notifyNoChanges()

      if (this.shouldConfirmSubmit) return this.confirmSubmit(this.performSubmitWithoutRedirectWithSameTaxon)

      this.performSubmitWithoutRedirectWithSameTaxon()
    },

    /**
     * Submit the form and stay to add more, with same taxon.
     */
    performSubmitWithoutRedirectWithSameTaxon(reason = null) {
      this.submittingWithoutRedirectWithSameTaxon = true
      this.confirmingSubmit = false

      if (this.shouldAskReason) {
        this.form.reason = reason
      }

      this.form[this.method.toLowerCase()](this.action)
          .then(this.onSuccessfulSubmitWithoutRedirectWithSameTaxon)
          .catch(error => {
            this.submittingWithoutRedirectWithSameTaxon = false
            this.onFailedSubmit(error)
          })
    },

    /**
     * Handle successful form submit with no redirect.
     */
    onSuccessfulSubmitWithoutRedirectWithSameTaxon() {
      this.submittingWithoutRedirectWithSameTaxon = false

      this.$buefy.toast.open({
        message: this.trans('Saved successfully'),
        type: 'is-success'
      })

      // Reset the form but remember some data.
      const keep = _pick(this.form.data(), this.keepAfterSubmit.concat([
        'original_identification_validity', 'place_where_referenced_in_publication',
        'taxon', 'taxon_id', 'original_identification',
      ]))
      this.form.reset()
      this.form.populate(keep)
    },
  }
}
</script>
