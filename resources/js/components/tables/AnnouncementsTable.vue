<template>
  <div class="announcements-table">
    <b-table
      :data="data"
      :loading="loading"

      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      @page-change="onPageChange"
      pagination-position="both"

      backend-sorting
      default-sort-direction="asc"
      :default-sort="[sortField, sortOrder]"
      @sort="onSort"

      mobile-cards
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

      <b-table-column field="title" :label="trans('labels.announcements.title')" sortable>
        <template #default="{ row }">
          {{ row.title || '--' }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="created_at" :label="trans('labels.created_at')" sortable>
        <template #default="{ row }">
          {{ row.created_at | formatDateTime }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
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
  </div>
</template>

<script>
import dayjs from '@/dayjs'
import _get from 'lodash/get'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import NzPerPageSelect from '@/components/table/PerPageSelect'
import NzSortableColumnHeader from '@/components/table/SortableColumnHeader'

export default {
  name: 'nzAnnouncementsTable',

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
    editRoute: String,
    deleteRoute: String,
    empty: {
      type: String,
      default: 'Nothing here.'
    },
    ranks: Array
  },

  data() {
    return {
      data: [],
      meta: null,
      total: 0,
      loading: false,
      sortField: 'name',
      sortOrder: 'asc',
      page: 1,
      perPage: this.perPageOptions[0],
      checkedRows: []
    }
  },

  computed: {
    showing() {
      if (!this.meta) return

      return this.trans('labels.tables.from_to_total', {
        from: _get(this.meta, 'from') || 0,
        to: _get(this.meta, 'to') || 0,
        total: _get(this.meta, 'total') || 0
      })
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
        per_page: this.perPage
      })).then(({ data }) => {
        this.data = []
        this.total = data.meta.total
        data.data.forEach((item) => this.data.push(item))
        this.meta = data.meta
        this.loading = false
      }, response => {
        this.data = []
        this.meta = null
        this.total = 0
        this.loading = false
      })
    },

    /**
     * Handle page-change event
     */
    onPageChange(page) {
      this.page = page
      this.loadAsyncData()
    },

    /**
     * Handle sort event
     */
    onSort(field, order) {
      this.sortField = field
      this.sortOrder = order

      this.saveState()

      this.loadAsyncData()
    },

    clearFilter() {
      for (let field in this.newFilter) {
        this.newFilter[field] = ''
      }

      this.onFilter()
    },

    onFilter() {
      let reload = false

      for (let field in this.newFilter) {
        if (this.filter[field] !== this.newFilter[field]) {
            reload = true
        }

        this.filter[field] = this.newFilter[field]
      }

      if (reload) {
        this.loadAsyncData()
      }
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
    }
  },

  filters: {
    formatDateTime(dateTime) {
      const d = dayjs(dateTime)
      return d.isValid() ? d.format('D.M.YYYY HH:mm') : ''
    }
  }
}
</script>
