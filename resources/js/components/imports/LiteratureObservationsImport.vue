<template>
  <div class="field-observations-import">
    <div class="is-flex is-flex-center flex-col" v-if="importing">
      <div class="is-flex is-flex-center">
        <span class="loader mr-2"></span>

        <span class="has-loader" v-if="currentImport">{{ importStatus }}</span>
      </div>

      <button
        type="button"
        class="button is-text has-text-danger"
        v-if="cancellable"
        @click="cancel"
      >{{ trans('buttons.cancel') }}</button>
    </div>

    <div v-else class="mb-4">
      <b-notification type="is-success" :active.sync="showSuccessMessage">
        {{ trans('imports.success') }}
      </b-notification>

      <div class="mb-8">
        <nz-publication-autocomplete
          class="is-required"
          :label="trans('labels.literature_observations.publication')"
          :placeholder="trans('labels.literature_observations.search_for_publication')"
          v-model="publicationSearch"
          @select="publication = $event"
          :error="submissionErrors.has('publication_id')"
          :message="submissionErrors.first('publication_id')"
        />

        <b-field
          class="is-required"
          :label="trans('labels.literature_observations.is_original_data')"
          :error="submissionErrors.has('is_original_data')"
          :message="submissionErrors.first('is_original_data')"
        >
          <b-select :value="isOriginalData" @input="handleIsOriginalDataInput" expanded>
            <option :value="true">{{ trans('labels.literature_observations.original_data') }}</option>
            <option :value="false">{{ trans('labels.literature_observations.citation') }}</option>
          </b-select>
        </b-field>

        <nz-publication-autocomplete
          v-show="!isOriginalData"
          v-model="citedPublicationSearch"
          @select="citedPublication = $event"
          class="is-required"
          :publication="publication"
          :error="submissionErrors.has('cited_publication_id')"
          :message="submissionErrors.first('cited_publication_id')"
          :label="trans('labels.literature_observations.cited_publication')"
          :placeholder="trans('labels.literature_observations.search_for_publication')"
        />
      </div>

      <div class="level mb-4">
        <div class="level-left">
          <div class="level-item">
            <div class="buttons">
              <button
                type="button"
                class="button is-outlined"
                :class="[showColumnsSelection ? 'is-primary' : '']"
                @click="toggleColumns"
                v-if="!importing"
              >{{ trans('labels.imports.choose_columns') }}</button>
            </div>
          </div>

          <div class="level-item">
            <b-field class="file" v-if="!importing">
              <b-upload v-model="file" accept=".csv">
                <a class="button">
                  <span>{{ trans('labels.imports.select_csv_file') }}</span>
                </a>
              </b-upload>

              <span class="file-name" v-if="file">{{ file.name }}</span>
            </b-field>
          </div>
        </div>

        <div class="level-right">
          <div class="level-item">
            <button
              type="button"
              class="button is-primary is-outlined"
              :disabled="!canSubmit"
              @click="submit"
            >
              <b-icon icon="upload"></b-icon>

              <span>{{ trans('labels.imports.import') }}</span>
            </button>
          </div>
        </div>
      </div>

      <b-checkbox v-model="hasHeading">{{ trans('labels.imports.has_heading') }}</b-checkbox>

      <div v-if="canApproveCurated">
        <b-checkbox v-model="approveCurated">{{ trans('labels.imports.approve_curated') }}</b-checkbox>
      </div>
    </div>

    <b-collapse :open="showColumnsSelection">
      <nz-columns-picker :columns="columns" v-model="selectedColumns" :title="trans('labels.imports.columns')"/>
    </b-collapse>

    <b-table
      v-if="validationErrors.length"
      :data="validationErrors"
      :paginated="true"
      :per-page="30"
      :current-page.sync="currentErrorsPage"
      class="is-danger">

      <template slot-scope="props">
          <b-table-column field="row" :label="trans('labels.imports.row_number')">
              {{ props.row.row }}
          </b-table-column>

          <b-table-column field="error" :label="trans('labels.imports.error')">
              {{ props.row.error }}
          </b-table-column>
      </template>
    </b-table>
  </div>
</template>

<script>
import _get from 'lodash/get'
import { Errors } from 'form-backend-validation'
import NzPublicationAutocomplete from '@/components/inputs/PublicationAutocomplete'
import NzColumnsPicker from '@/components/inputs/ColumnsPicker'

export default {
  name: 'nzFieldObservationsImport',

  components: {
    NzPublicationAutocomplete,
    NzColumnsPicker
  },

  props: {
    columns: {
      type: Array,
      default: () => []
    },

    initial: {
      type: Array,
      default: () => []
    },

    runningImport: Object,

    cancellableStatuses: {
      type: Array,
      default: () => []
    },

    canSubmitForUser: Boolean,
    canApproveCurated: Boolean,
  },

  data() {
    return {
      selectedColumns: this.initial,
      showColumnsSelection: false,
      file: null,
      importing: false,
      currentImport: this.runningImport,
      validationErrors: [],
      currentErrorsPage: 1,
      submissionErrors: new Errors(),
      hasHeading: false,
      approveCurated: false,
      showSuccessMessage: false,
      cancelling: false,
      publicationSearch: '',
      citedPublicationSearch: '',
      publication: null,
      citedPublication: null,
      isOriginalData: true
    }
  },

  computed: {
    canSubmit() {
      return !this.importing && this.file
    },

    validationFailed() {
      return _get(this.currentImport, 'status') === 'validation_failed'
    },

    saved() {
      return _get(this.currentImport, 'status') === 'saved'
    },

    savingFailed() {
      return _get(this.currentImport, 'status') === 'saving_failed'
    },

    cancelled() {
      return _get(this.currentImport, 'status') === 'cancelled'
    },

    importStatus() {
      const status = _get(this.currentImport, 'status')

      return this.trans(`imports.status.${status}`)
    },

    cancellable() {
      return this.currentImport &&
        this.cancellableStatuses.includes(this.currentImport.status) &&
        !this.cancelling
    },
  },

  created() {
    if (this.currentImport && !this.saved) {
      this.importing = true

      this.startCheckingStatus()
    }
  },

  methods: {
    toggleColumns() {
      this.showColumnsSelection = !this.showColumnsSelection
    },

    submit() {
      if (!this.canSubmit) return

      this.resetForm()
      this.importing = true
      this.currentImport = null

      axios.post('/api/literature-observation-imports', this.makeForm())
        .then(this.handleSuccessfulSubmit)
        .catch(this.handleFailedSubmit)
    },

    makeForm() {
      const form = new FormData()

      form.append('file', this.file)
      this.selectedColumns.forEach((column) => {
        form.append('columns[]', column)
      })

      this.hasHeading && form.append('has_heading', 1)

      this.publication && form.append('publication_id', this.publication.id)
      form.append('is_original_data', this.isOriginalData ? 1 : 0)
      this.citedPublication && form.append('cited_publication_id', this.citedPublication.id)

      return form
    },

    resetForm() {
      this.validationErrors = []
      this.currentErrorsPage = 1
      this.showColumnsSelection = false
      this.submissionErrors.clear()
    },

    handleSuccessfulSubmit({ data }) {
      this.currentImport = data
      this.file = null

      this.startCheckingStatus()
    },

    handleFailedSubmit(error) {
      this.importing = false
      this.submissionErrors.record(_get(error, 'response.data.errors', {}))

      this.$buefy.toast.open({
        duration: 2500,
        message: _get(error, 'response.data.message'),
        type: 'is-danger'
      })
    },

    startCheckingStatus() {
      this.interval = setInterval(() => {
        this.checkStatus()
      }, 2500)
    },

    checkStatus() {
      return axios.get(`/api/literature-observation-imports/${this.currentImport.id}`).then(({ data }) => {
        this.currentImport = data

        if (this.validationFailed) {
          return this.handleFailedValidation()
        }

        if (this.savingFailed) {
          return this.handleFailedSaving()
        }

        if (this.cancelled) {
          return this.stopCheckingImport()
        }

        if (this.saved) {
          this.handleStored()
        }
      })
    },

    showValidationErrors() {
      return axios.get(this.currentImport.errors_url).then(({ data }) => {
        this.validationErrors = data
      })
    },

    handleFailedValidation() {
      this.$buefy.toast.open({
        duration: 2500,
        message: this.trans('imports.validation_failed'),
        type: 'is-danger'
      })

      this.showColumnsSelection = false

      this.showValidationErrors()

      this.stopCheckingImport()
    },

    handleFailedSaving() {
      this.$buefy.toast.open({
        duration: 2500,
        message: this.trans('imports.saving_failed'),
        type: 'is-danger'
      })

      this.showColumnsSelection = false

      this.stopCheckingImport()
    },

    handleStored() {
      this.showSuccessMessage = true

      // Reset publication inputs
      this.publicationSearch = ''
      this.citedPublicationSearch = ''
      this.isOriginalData = true

      this.stopCheckingImport()
    },

    cancel() {
      if (!this.cancellable) return

      this.cancelling = true

      axios.post('/api/cancelled-imports', {
        import_id: this.currentImport.id,
      }).then(() => {
        this.cancelling = false

        this.stopCheckingImport()
      }).catch(() => {
        this.cancelling = false
      })
    },

    stopCheckingImport() {
      clearInterval(this.interval)
      this.importing = false
      this.currentImport = null
    },

    handleIsOriginalDataInput(value) {
      if (value) {
        this.citedPublication = null
        this.citedPublicationSearch = ''
      }

      this.isOriginalData = value
    },
  }
}
</script>

<style lang="scss" scoped>
  .buttons {
    .field {
      margin-bottom: 0;

      &.file {
        margin-right: .5rem;
      }

      .upload {
        &:hover {
          cursor: pointer;
        }

        .button {
          margin-right: 0;

        }
      }
    }
  }
</style>
