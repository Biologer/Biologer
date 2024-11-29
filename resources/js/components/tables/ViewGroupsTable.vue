<template>
  <div class="view-groups-table">
    <div class="level">
      <div class="level-right" v-if="hasActions">
        <div class="level-item">
          <b-dropdown position="is-bottom-left">
            <button
              class="button is-touch-full"
              slot="trigger"
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

      <b-table-column field="name" :label="trans('labels.view_groups.name')">
        <template #default="{ row }">
          {{ row.name }}
        </template>
      </b-table-column>

      <b-table-column width="150" numeric v-slot="{ row }">
        <a :href="editLink(row)"><b-icon icon="edit"></b-icon></a>

        <a @click="confirmRemove(row)"><b-icon icon="trash"></b-icon></a>
      </b-table-column>

      <template #empty>
        <section class="section">
          <div class="content has-text-grey has-text-centered">
            <p>{{ empty }}</p>
          </div>
        </section>
      </template>
    </b-table>

    <b-modal :active="showExportModal" @close="showExportModal = false" has-modal-card :can-cancel="[]">
      <nz-export-modal
        :checked="checkedIds"
        :filter="filter"
        :columns="exportColumns"
        :url="exportUrl"
        :types="['custom']"
        :sort="sortBy"
        @cancel="showExportModal = false"
        @done="onExportDone"
      />
    </b-modal>

  </div>
</template>

<script>
import FilterableTableMixin from '@/mixins/FilterableTableMixin'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import NzPerPageSelect from '@/components/table/PerPageSelect'
import NzSortableColumnHeader from '@/components/table/SortableColumnHeader'
import ExportDownloadModal from '@/components/exports/ExportDownloadModal'
import NzExportModal from '@/components/exports/ExportModal'

export default {
  name: 'nzViewGroupsTable',

  mixins: [FilterableTableMixin, PersistentTableMixin],

  components: {
    NzPerPageSelect,
    NzSortableColumnHeader,
    NzExportModal
  },

  props: {
    perPageOptions: {
      type: Array,
      default: () => [15, 30, 50, 100],
      validator: value => value.length
    },
    listRoute: String,
    editRoute: String,
    deleteRoute: String,
    empty: {
      type: String,
      default: 'Nothing here.'
    },
    exportColumns: Array,
    exportUrl: String,
  },

  data() {
    return {
      data: [],
      total: 0,
      loading: false,
      sortField: 'id',
      sortOrder: 'desc',
      page: 1,
      perPage: this.perPageOptions[0],
      checkedRows: [],
      showExportModal: false,
    }
  },

  computed: {
    hasActions() {
      return this.exportable
    },

    exportable() {
      return !!(this.exportUrl && this.exportColumns.length)
    },

    checkedIds() {
      return this.checkedRows.map(row => row.id)
    },

    sortBy() {
      return `${this.sortField}.${this.sortOrder}`
    },

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
    this.$on('filter', () => {
      this.page = 1
      this.saveState()
      this.loadAsyncData()
    })
  },

  methods: {
    loadAsyncData() {
      this.loading = true

      return axios.get(route(this.listRoute).withQuery({
        sort_by: `${this.sortField}.${this.sortOrder}`,
        page: this.page,
        per_page: this.perPage
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

    confirmRemove(row) {
      this.$buefy.dialog.confirm({
        message: this.trans('Are you sure you want to delete this record?'),
        confirmText: this.trans('buttons.delete'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-danger',
        onConfirm: () => { this.remove(row) }
      })
    },

    remove (row) {
      return axios.delete(route(this.deleteRoute, row.id)).then(response => {
        this.$buefy.toast.open({
          message: this.trans('Record deleted'),
          type: 'is-success'
        })

        this.loadAsyncData()
      }).catch(error => { console.error(error) })
    },

    editLink (row) {
      return route(this.editRoute, row.id)
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
          message: `Something's not good, also I'm on bottom`,
          type: 'is-danger'
        })
      }
    },

  }
}
</script>
