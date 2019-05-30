<template>
  <div class="field-observations-table">
    <div class="level">
      <div class="level-left">
      </div>

      <div class="level-right" v-if="hasActions">
        <div class="level-item">
          <b-dropdown position="is-bottom-left">
            <button
              class="button is-touch-full"
              slot="trigger"
              :class="{'is-loading': actionRunning}"
            >
              <span>{{ trans('labels.actions') }}</span>

              <span class="icon has-text-grey">
                  <i class="fa fa-angle-down"></i>
              </span>
            </button>

            <b-dropdown-item
              @click="openExportModal"
              v-if="exportable"
            >
              <b-icon icon="download" class="has-text-grey" />

              <span>{{ trans('buttons.export') }}</span>
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </div>
    </div>

    <hr>

    <nz-table
      :data="data"
      :loading="loading"

      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      @page-change="onPageChange"
      @per-page-change="onPerPageChange"
      :per-page-options="perPageOptions"
      pagination-on-top

      backend-sorting
      :default-sort-direction="defaultSortOrder"
      :default-sort="[sortField, sortOrder]"
      @sort="onSort"

      detailed
      :mobile-cards="true"

      :checkable="hasActions"
      :checked-rows.sync="checkedRows"
    >
      <template slot-scope="{ row }">
        <b-table-column field="id" :label="trans('labels.id')" width="40" numeric sortable>
          {{ row.id }}
        </b-table-column>

        <b-table-column field="taxon_name" :label="trans('labels.literature_observations.taxon')" sortable>
          {{ row.taxon ? row.taxon.name : '' }}
        </b-table-column>

        <b-table-column field="year" :label="trans('labels.literature_observations.year')" numeric sortable>
          {{ row.year }}
        </b-table-column>

        <b-table-column field="month" :label="trans('labels.literature_observations.month')" numeric sortable>
          {{ row.month }}
        </b-table-column>

        <b-table-column field="day" :label="trans('labels.literature_observations.day')" numeric sortable>
          {{ row.day }}
        </b-table-column>

        <b-table-column width="150" numeric>
          <a @click="openActivityLogModal(row)" v-if="showActivityLog && row.activity" :title="trans('Activity Log')"><b-icon icon="history" /></a>

          <a :href="viewLink(row)" v-if="viewRoute" :title="trans('buttons.view')"><b-icon icon="eye" /></a>

          <a :href="editLink(row)" :title="trans('buttons.edit')"><b-icon icon="edit" /></a>

          <a @click="confirmRemove(row)" :title="trans('buttons.delete')"><b-icon icon="trash" /></a>
        </b-table-column>
      </template>

      <template slot="empty">
        <section class="section">
          <div class="content has-text-grey has-text-centered">
            <p>{{ empty }}</p>
          </div>
        </section>
      </template>

      <template slot="detail" slot-scope="{ row }">
        <article class="media">
          <div class="media-content">
            <div class="content">
              <strong>{{ row.location }}</strong>

              <small>{{ row.latitude }}, {{ row.longitude }}</small><br>

              <small>{{ trans('labels.literature_observations.elevation') }}: {{ row.elevation}}m</small><br>

              <small v-if="row.accuracy">{{ trans('labels.literature_observations.accuracy') }}: {{ row.accuracy}}m</small>
            </div>
          </div>
        </article>
      </template>
    </nz-table>

    <b-modal :active="activityLog.length > 0" @close="activityLog = []">
      <div class="modal-card">
        <div class="modal-card-head">
          <b-icon icon="history" />
          <p class="modal-card-title">{{ trans('Activity Log') }}</p>
        </div>
        <div class="modal-card-body">
          <nz-literature-observation-activity-log :activities="activityLog" />
        </div>
      </div>
    </b-modal>

    <b-modal :active="showExportModal" @close="showExportModal = false" has-modal-card :can-cancel="[]">
      <!-- :filter="filter" -->
      <nz-export-modal
        :checked="checkedIds"
        :columns="exportColumns"
        :url="exportUrl"
        @cancel="showExportModal = false"
        @done="onExportDone"
        :types="['custom']"
      />
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios'
import FilterableTableMixin from '@/mixins/FilterableTableMixin'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import ExportDownloadModal from '@/components/exports/ExportDownloadModal'

export default {
  name: 'nzLiteratureObservationsTable',

  mixins: [PersistentTableMixin],

  props: {
    perPageOptions: {
      type: Array,
      default() {
        return [15, 30, 50, 100]
      },
      validator(value) {
        return value.length
      }
    },
    listRoute: String,
    viewRoute: String,
    editRoute: String,
    deleteRoute: String,
    empty: {
      type: String,
      default: 'Nothing here.'
    },
    showActivityLog: Boolean,
    exportUrl: String,
    exportColumns: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      data: [],
      total: 0,
      loading: false,
      sortField: 'id',
      sortOrder: 'desc',
      defaultSortOrder: 'desc',
      page: 1,
      perPage: this.perPageOptions[0],
      checkedRows: [],
      activityLog: [],
      showExportModal: false,
      exporting: false,
    }
  },

  computed: {
    hasActions() {
      return this.exportable
    },

    checkedIds() {
      return this.checkedRows.map(row => row.id)
    },

    exportable() {
      return !!(this.exportUrl && this.exportColumns.length)
    },

    actionRunning() {
      return this.exporting
    },
  },

  created() {
    this.restoreState()
    this.loadAsyncData()

    this.$on('filter', this.loadAsyncData)
  },

  methods: {
    loadAsyncData() {
      this.loading = true

      return axios.get(route(this.listRoute, {
        sort_by: `${this.sortField}.${this.sortOrder}`,
        page: this.page,
        per_page: this.perPage,
      })).then(({ data: response }) => {
        this.data = []
        this.total = response.meta.total
        response.data.forEach((item) => this.data.push(item))
        this.loading = false
      }, (response) => {
        this.data = []
        this.total = 0
        this.loading = false
      })
    },

    /*
     * Handle page-change event
     */
    onPageChange(page) {
      this.page = page
      this.loadAsyncData()
    },

    /*
     * Handle sort event
     */
    onSort(field, order) {
      this.sortField = field
      this.sortOrder = order

      this.saveState()

      this.loadAsyncData()
    },

    onPerPageChange(perPage) {
      if (perPage === this.perPage) return

      this.perPage = perPage

      this.saveState()

      this.loadAsyncData()
    },

    confirmRemove(row) {
      this.$dialog.confirm({
        message: this.trans('Are you sure you want to delete this record?'),
        confirmText: this.trans('buttons.delete'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-danger',
        onConfirm: () => { this.remove(row) }
      })
    },

    remove (row) {
      return axios.delete(route(this.deleteRoute, row.id)).then(response => {
        this.$toast.open({
          message: this.trans('Record deleted'),
          type: 'is-success'
        })

        this.loadAsyncData()
      }).catch(error => { console.error(error) })
    },

    editLink (row) {
      return route(this.editRoute, row.id)
    },

    viewLink (row) {
      return this.viewRoute ? route(this.viewRoute, row.id) : null
    },

    openActivityLogModal(row) {
      this.activityLog = row.activity
    },

    openExportModal() {
      this.showExportModal = true
    },

    onExportDone(finishedExport) {
      this.showExportModal = false

      if (finishedExport.url) {
        this.$modal.open({
          parent: this,
          component: ExportDownloadModal,
          canCancel: [],
          hasModalCard: true,
          props: {
            url: finishedExport.url,
          }
        })
      } else {
        this.$toast.open({
          duration: 0,
          message: `Something's not good, also I'm on bottom`,
          type: 'is-danger'
        })
      }
    }
  },

  filters: {
    /**
     * Filter to truncate string, accepts a length parameter
     */
    truncate(value, length) {
      return value.length > length
        ? value.substr(0, length) + '...'
        : value
    }
  }
}
</script>
