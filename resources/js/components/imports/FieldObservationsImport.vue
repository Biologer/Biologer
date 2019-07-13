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

    <div v-else>
      <b-notification type="is-success" :active.sync="showSuccessMessage">
        {{ trans('imports.success') }}
      </b-notification>

      <nz-user-autocomplete
        v-if="canSubmitForUser"
        :label="trans('labels.imports.user')"
        :placeholder="userFullName"
        @select="setUserId"
        v-model="user"
      />

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
      submissionErrors: null,
      hasHeading: false,
      approveCurated: false,
      showSuccessMessage: false,
      cancelling: false,
      userId: null,
      user: null
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

    userFullName() {
      return window.App.User.full_name
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
      this.currentImport = null

      axios.post('/api/field-observation-imports', this.makeForm())
        .then(this.handleSuccessfulSubmit)
        .catch(this.handleFailedSubmit)
    },

    makeForm() {
      const form = new FormData()

      form.append('file', this.file)
      this.selectedColumns.forEach((column) => {
        form.append('columns[]', column)
      })

      if (this.hasHeading) {
        form.append('has_heading', 1)
      }

      form.append('user_id', this.userId || '')

      if (this.approveCurated) {
        form.append('options[approve_curated]', 1)
      }

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
      this.file = null

      this.startCheckingStatus()
    },

    handleFailedSubmit(error) {
      this.importing = false
      this.submissionErrors = _get(error, 'response.data.errors', [])

      this.$toast.open({
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
      return axios.get(`/api/field-observation-imports/${this.currentImport.id}`).then(({ data }) => {
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

      this.showColumnsSelection = false

      this.showValiadtionErrors()

      this.stopCheckingImport()
    },

    handleFailedSaving() {
      this.$toast.open({
        duration: 2500,
        message: this.trans('imports.saving_failed'),
        type: 'is-danger'
      })

      this.showColumnsSelection = false

      this.stopCheckingImport()
    },

    handleStored() {
      this.showSuccessMessage = true

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

    setUserId(userId) {
      this.userId = userId ? userId.id : null
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
