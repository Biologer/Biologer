<template>
  <div class="timed-count-observations-table">
    <b-table
      :data="data"
      :loading="loading"

      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      :current-page="page"
      @page-change="onPageChange"
      pagination-position="both"

      backend-sorting
      default-sort-direction="asc"
      :default-sort="[sortField, sortOrder]"
      @sort="onSort"

      mobile-cards
    >
      <template #top-left>
        <div class="level-item">
          <nz-per-page-select :value="perPage" @input="onPerPageChange" :options="perPageOptions" />
        </div>
        <div class="level-item">{{ showing }}</div>
      </template>
      <template #bottom-left>
        <div class="level-item">
          <nz-per-page-select :value="perPage" @input="onPerPageChange" :options="perPageOptions" />
        </div>
        <div class="level-item">{{ showing }}</div>
      </template>

      <b-table-column field="id" :label="trans('labels.id')" width="40" numeric sortable>
        <template #default="{ row }">
          {{ row.id }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="view_group" :label="trans('labels.exports.group_name')" sortable>
        <template #default="{ row }">
          {{ row.view_group.name }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="year" :label="trans('labels.field_observations.year')" numeric sortable>
        <template #default="{ row }">
          {{ row.year }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="month" :label="trans('labels.field_observations.month')" numeric sortable>
        <template #default="{ row }">
          {{ row.month }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="day" :label="trans('labels.field_observations.day')" numeric sortable>
        <template #default="{ row }">
          {{ row.day }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="start_time" :label="trans('labels.timed_count_observations.start_time')" numeric sortable>
        <template #default="{ row }">
          {{ row.start_time }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="end_time" :label="trans('labels.timed_count_observations.end_time')" numeric sortable>
        <template #default="{ row }">
          {{ row.end_time }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="duration" :label="trans('labels.timed_count_observations.duration')" sortable>
        <template #default="{ row }">
          {{ formatDuration(row.start_time, row.end_time) }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header
            :column="column"
            :sort="{ field: sortField, order: sortOrder }"
          />
        </template>
      </b-table-column>

      <b-table-column width="150" numeric v-slot="{ row }">
        <a @click="openActivityLogModal(row)" v-if="showActivityLog" :title="trans('Activity Log')"><b-icon icon="history" /></a>

        <a :href="viewLink(row)" v-if="viewRoute" :title="trans('buttons.view')"><b-icon icon="eye" /></a>

        <a @click="confirmRemove(row)"><b-icon icon="trash"></b-icon></a>
      </b-table-column>

      <b-modal :active="activityLog.length > 0" @close="activityLog = []" has-modal-card>
        <div class="modal-card">
          <div class="modal-card-head">
            <b-icon icon="history" />
            <p class="modal-card-title">{{ trans('Activity Log') }}</p>
          </div>
          <div class="modal-card-body">
            <nz-timed-count-observation-activity-log :activities="activityLog" />
          </div>
        </div>
      </b-modal>

      <template #empty>
        <section class="section">
          <div class="content has-text-grey has-text-centered">
            <p>{{ empty }}</p>
          </div>
        </section>
      </template>
    </b-table>
  </div>
</template>

<script>
import _debounce from 'lodash/debounce'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import NzPerPageSelect from '@/components/table/PerPageSelect'
import NzSortableColumnHeader from '@/components/table/SortableColumnHeader'

export default {
  name: 'nzTimedCountObservationsTable',

  mixins: [PersistentTableMixin],

  components: {
    NzPerPageSelect,
    NzSortableColumnHeader
  },

  props: {
    perPageOptions: {
      type: Array,
      default: () => [15, 30, 50, 100],
      validator: value => value.length
    },
    listRoute: String,
    viewRoute: String,
    editRoute: String,
    deleteRoute: String,
    showActivityLog: Boolean,
    empty: {
      type: String,
      default: 'Nothing here.'
    },
  },

  data() {
    return {
      data: [],
      total: 0,
      loading: false,
      sortField: 'id',
      sortOrder: 'asc',
      page: 1,
      perPage: this.perPageOptions[0],
      checkedRows: [],
      activityLog: [],
      search: ''
    }
  },

  computed: {
    showing() {
      const to = this.page * this.perPage <= this.total
        ? this.page * this.perPage
        : this.total

      const from = this.page > 1 ? (this.page - 1) * this.perPage + 1 : 1

      return this.total ? this.trans('labels.tables.from_to_total', {
          total: this.total,
          from,
          to
      }) : '';
    }
  },

  created() {
    this.restoreState()
    this.loadAsyncData()
  },

  methods: {
    loadAsyncData() {
      this.loading = true

      return axios.get(route(this.listRoute).withQuery({
        sort_by: `${this.sortField}.${this.sortOrder}`,
        page: this.page,
        per_page: this.perPage,
        search: this.search
      })).then(({ data: response }) => {
        this.data = []
        this.total = response.meta.total
        response.data.forEach((item) => this.data.push(item))
        this.loading = false
      }, response => {
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

    openActivityLogModal(row) {
      this.activityLog = row.activity
    },

    confirmRemove(row) {
      this.$buefy.dialog.confirm({
        message: this.trans('Are you sure you want to delete this record?'),
        confirmText: this.trans('buttons.delete'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-danger',
        onConfirm: () => { this.remove(row) }
      })
    },

    remove(row) {
      return axios.delete(route(this.deleteRoute, row.id)).then(response => {
        this.$buefy.toast.open({
          message: this.trans('Record deleted'),
          type: 'is-success'
        })

        this.loadAsyncData()
      }).catch(error => { console.error(error) })
    },

    editLink(row) {
      return route(this.editRoute, row.id)
    },

    viewLink(row) {
      return this.viewRoute ? route(this.viewRoute, row.id) : null
    },

    performSearch: _debounce(function (value) {
      if (this.search !== value) {
        this.search = value
        this.loadAsyncData()
      }
    }, 500),

    clearSearch() {
      this.search = ''
      this.loadAsyncData()
    },

    formatDuration(start, end) {
      if (!start || !end) return '-'

      // add dummy date so JS can parse time
      const startTime = new Date(`1970-01-01T${start}`)
      const endTime   = new Date(`1970-01-01T${end}`)

      if (isNaN(startTime) || isNaN(endTime)) return '-'

      let diffSec = Math.floor((endTime - startTime) / 1000)
      if (diffSec < 0) return '-'

      const hours   = Math.floor(diffSec / 3600)
      const minutes = Math.floor((diffSec % 3600) / 60)
      const seconds = diffSec % 60

      return hours > 0 ? `${hours}h ${minutes}m ${seconds}s` :
             minutes > 0 ? `${minutes}m ${seconds}s` :
             `${seconds}s`
    }
  }
}
</script>
