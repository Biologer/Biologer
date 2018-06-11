<template>
  <div class="field-observations-import">
    <div class="is-flex is-flex-center" v-if="importing" >
      <span class="loader mr-2"></span>
      <span class="has-loader" v-if="currentImport">{{ importStatus }}</span>
    </div>
    <div class="level" v-else>
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
            <b-upload v-model="files" accept=".csv">
                <a class="button">
                    <span>{{ trans('labels.imports.select_csv_file') }}</span>
                </a>
            </b-upload>
            <span class="file-name"
                v-if="files && files.length">
                {{ files[0].name }}
            </span>
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

    <b-collapse :open="showColumnsSelection">
      <nz-transfer :items="columns" v-model="selectedColumns"/>
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
export default {
  name: 'nzFieldObservationsImport',

  props: {
    columns: {
      type: Array,
      default: () => []
    },

    initial: {
      type: Array,
      default: () => []
    },

    runningImport: Object
  },

  data() {
    return {
      selectedColumns: this.initial,
      showColumnsSelection: false,
      files: [],
      importing: false,
      currentImport: this.runningImport,
      validationErrors: [],
      currentErrorsPage: 1,
      submissionErrors: null,
    }
  },

  computed: {
    canSubmit() {
      return !this.importing && (this.files && this.files.length)
    },

    validationFailed() {
      return _.get(this.currentImport, 'status') === 'validation_failed'
    },

    saved() {
      return _.get(this.currentImport, 'status') === 'saved'
    },

    savingFailed() {
      return _.get(this.currentImport, 'status') === 'saving_failed'
    },

    importStatus() {
      const status = _.get(this.currentImport, 'status')

      return this.trans(`imports.status.${status}`)
    }
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

      axios.post('/api/field-observation-imports', this.makeForm())
        .then(this.handleSuccessfulSubmit)
        .catch(this.handleFailedSubmit)
    },

    makeForm() {
      const form = new FormData()

      form.append('file', this.files[0])
      this.selectedColumns.forEach((column) => {
        form.append('columns[]', column)
      })

      return form
    },

    resetForm() {
      this.validationErrors = []
      this.currentErrorsPage = 1
      this.showColumnsSelection = false
      this.submissionErrors = null
    },

    handleSuccessfulSubmit({ data }) {
      this.currentImport = data
      this.files = []

      this.startCheckingStatus()
    },

    handleFailedSubmit(error) {
      this.importing = false
      this.submissionErrors = _.get(error, 'response.data.errors', [])

      this.$toast.open({
         duration: 2500,
         message: _.get(error, 'response.data.message'),
         type: 'is-danger'
      })
    },

    startCheckingStatus() {
      this.interval = setInterval(() => {
        this.checkStatus()
      }, 2500)
    },

    checkStatus() {
      return axios.get(`/api/field-observation-imports/${this.currentImport.id}`).then(({ data }) => {
        this.currentImport = data

        if (this.validationFailed) {
          return this.handleFailedValidation()
        }

        if (this.savingFailed) {
          return this.handleFailedSaving()
        }

        if (this.saved) {
          this.handleStored()
        }
      })
    },

    showValiadtionErrors() {
      return axios.get(this.currentImport.errors_url).then(({ data }) => {
        this.validationErrors = data
      })
    },

    handleFailedValidation() {
      this.$toast.open({
         duration: 2500,
         message: this.trans('imports.validation_failed'),
         type: 'is-danger'
      })

      this.importing = false
      this.showColumnsSelection = false

      clearInterval(this.interval)

      this.showValiadtionErrors()

      return this.currentImport
    },

    handleFailedSaving() {
      this.$toast.open({
         duration: 2500,
         message: this.trans('imports.saving_failed'),
         type: 'is-danger'
      })

      this.importing = false
      this.showColumnsSelection = false

      clearInterval(this.interval)

      return this.currentImport
    },

    handleStored() {
      this.$toast.open({
         duration: 2500,
         message: this.trans('imports.success'),
         type: 'is-success'
      })

      clearInterval(this.interval)
      this.importing = false
      this.currentImport = null
    }
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
