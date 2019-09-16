<template>
  <div class="modal-card">
    <div class="modal-card-head">
      <p class="modal-card-title">{{ trans('labels.exports.title') }}</p>
    </div>

    <div class="modal-card-body">
      <template v-if="processing">
        {{ trans('labels.exports.processing') }}

        <span class="loader mr-2 is-inline-block"></span>
      </template>

      <template v-else>
        <b-field>
          <b-checkbox v-model="onlyChecked">{{ trans('labels.exports.only_checked') }}</b-checkbox>
        </b-field>

        <b-field>
          <b-checkbox v-model="applyFilters">{{ trans('labels.exports.apply_filters') }}</b-checkbox>
        </b-field>

        <b-field
          :type="form.errors.has('with_header') ? 'is-danger' : null"
          :message="form.errors.first('with_header')"
        >
          <b-checkbox v-model="withHeader">{{ trans('labels.exports.with_header') }}</b-checkbox>
        </b-field>

        <div class="field">
          <p class="help is-danger" v-if="form.errors.has('columns')">{{ form.errors.first('columns') }}</p>

          <nz-columns-picker v-model="selectedColumns" :columns="columns" :title="trans('labels.exports.columns')"/>
        </div>
      </template>
    </div>

    <div class="modal-card-foot" v-if="!processing">
      <button type="button" class="button is-primary" @click="sendExportRequest">{{ trans('buttons.export') }}</button>

      <button type="button" class="button" @click="$emit('cancel')">{{ trans('buttons.cancel') }}</button>
    </div>
  </div>
</template>

<script>
import Form from 'form-backend-validation'
import _get from 'lodash/get'
import NzColumnsPicker from '@/components/inputs/ColumnsPicker'

export default {
  name: 'nzCustomExport',

  components: {
    NzColumnsPicker
  },

  props: {
    checked: {
      type: Array,
      default: () => []
    },

    filter: {
      type: Object,
      default: () => ({})
    },

    columns: {
     type: [Array, Object],
     required: true
    },

    url: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      onlyChecked: false,
      applyFilters: true,
      processing: false,
      currentExport: null,
      selectedColumns: [],
      withHeader: false,
      form: new Form(),
    }
  },

  computed: {
    filters() {
      let filters = {}

      if (this.applyFilters) {
        filters = Object.assign(filters, this.filter)
      }

      if (this.onlyChecked) {
        filters.id = this.checked
      }

      return filters
    },

    exportStatus() {
      return _get(this.currentExport, 'status')
    },

    exportFinished() {
      return this.exportStatus === 'finished'
    },

    exportFailed() {
      return this.exportStatus === 'failed'
    }
  },

  methods: {
    sendExportRequest() {
      if (this.processing) return

      this.processing = true

      this.form.withData({
        ...this.filters,
        columns: this.selectedColumns,
        with_header: this.withHeader,
      }).post(this.url).then((response) => {
          this.startCheckingStatus(response)
      }).catch((e) => {
        this.processing = false
      })
    },

    startCheckingStatus(data) {
      this.currentExport = data
      this.checkInterval = setInterval(() => {
        this.checkExportStatus()
      }, 2000)
    },

    checkExportStatus() {
      axios.get(`/api/exports/${this.currentExport.id}`).then(({ data }) => {
        this.currentExport = data

        if (this.exportFailed || this.exportFinished) {
          clearInterval(this.checkInterval)
          this.processing = false

          this.$emit('done', this.currentExport)
        }
      })
    }
  }
}
</script>
