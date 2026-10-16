<template>
  <div class="collection-observations-table">
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

      detailed
      mobile-cards

      :checkable="hasActions"
      :checked-rows.sync="checkedRows"
    >
      <template #top-left>
        <nz-per-page-select :value="perPage" @input="onPerPageChange" :options="perPageOptions" />
      </template>
      <template #bottom-left>
        <nz-per-page-select :value="perPage" @input="onPerPageChange" :options="perPageOptions" />
      </template>

      <b-table-column field="id" :label="trans('labels.id')" width="40" numeric sortable>
        <template #default="{ row }">
          {{ row.id }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="taxon_name" :label="trans('labels.collection_observations.taxon')" sortable>
        <template #default="{ row }">
          {{ row.taxon ? row.taxon.name : '' }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="collection_name" :label="trans('labels.collection_observations.collection')" sortable>
        <template #default="{ row }">
          {{ row.collection ? row.collection.name : '' }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column width="150" numeric v-slot="{ row }">
        <a :href="viewLink(row)" v-if="viewRoute" :title="trans('buttons.view')"><b-icon icon="eye" /></a>

        <a :href="editLink(row)" :title="trans('buttons.edit')"><b-icon icon="edit" /></a>

        <a @click="confirmRemove(row)" :title="trans('buttons.delete')"><b-icon icon="trash" /></a>
      </b-table-column>

      <template #empty>
        <section class="section">
          <div class="content has-text-grey has-text-centered">
            <p>{{ empty }}</p>
          </div>
        </section>
      </template>

      <template #detail="{ row }">
        <article class="media">
          <div class="media-content">
            <div class="content">
              <strong>{{ row.location }}</strong>

              <small>{{ row.latitude }}, {{ row.longitude }}</small><br>

              <small>{{ trans('labels.collection_observations.elevation') }}: {{ row.elevation}}m</small><br>

              <small v-if="row.accuracy">{{ trans('labels.collection_observations.accuracy') }}: {{ row.accuracy}}m</small>
            </div>
          </div>
        </article>
      </template>
    </b-table>
  </div>
</template>

<script>
import axios from 'axios'
import _range from 'lodash/range'
import dayjs from '@/dayjs'
import FilterableTableMixin from '@/mixins/FilterableTableMixin'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import NzPerPageSelect from '@/components/table/PerPageSelect'
import NzSortableColumnHeader from '@/components/table/SortableColumnHeader'

export default {
  name: 'nzLiteratureObservationsTable',

  mixins: [FilterableTableMixin, PersistentTableMixin],

  components: {
    NzPerPageSelect,
    NzSortableColumnHeader
  },

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
      defaultSortOrder: 'asc',
      page: 1,
      perPage: this.perPageOptions[0],
      checkedRows: [],
      activityLog: [],
      showExportModal: false,
      exporting: false,
    }
  },

  computed: {
    months() {
      return dayjs.months()
    },

    days() {
      return _range(1, 31)
    },

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

    this.$on('filter', () => {
      this.saveState()
      this.loadAsyncData()
    })
  },

  methods: {
    loadAsyncData() {
      this.loading = true
      this.checkedRows = []

      const { ...filter } = this.filter

      return axios.get(route(this.listRoute).withQuery({
        ...filter,
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

    getPersistantKeys() {
      return [
        'sortField', 'sortOrder', 'perPage', 'page',
        'newFilter', 'filter', 'filterIsActive'
      ]
    },

    /*
     * Handle page-change event
     */
    onPageChange(page) {
      this.page = page

      this.saveState()

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

    openActivityLogModal(row) {
      this.activityLog = row.activity
    },

    filterDefaults() {
      return {
      }
    },

    openExportModal() {
      this.showExportModal = true
    },

    onExportDone(finishedExport) {
      this.showExportModal = false

      if (finishedExport.url) {
        this.$buefy.modal.open({
          parent: this,
          component: ExportDownloadModal,
          canCancel: [],
          hasModalCard: true,
          props: {
            url: finishedExport.url,
          }
        })
      } else {
        this.$buefy.toast.open({
          duration: 0,
          message: 'Whoops, looks like something went wrong.',
          type: 'is-danger'
        })
      }
    },
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
