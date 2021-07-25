<template>
  <div class="users-table">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <b-field grouped>
            <b-input
              :placeholder="trans('labels.users.search')"
              :value="search"
              @input="performSearch"
              icon="search"
              expanded
            />

            <p class="control" v-if="search">
              <span class="button" @click="clearSearch">
                <b-icon icon="close" />
              </span>
            </p>
          </b-field>
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

      <b-table-column field="first_name" :label="trans('labels.users.first_name')" sortable>
        <template #default="{ row }">
          {{ row.first_name }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="last_name" :label="trans('labels.users.last_name')" sortable>
        <template #default="{ row }">
          {{ row.last_name }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="email" :label="trans('labels.users.email')" sortable>
        <template #default="{ row }">
          {{ row.email }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="institution" :label="trans('labels.users.institution')" sortable>
       <template #default="{ row }">
          {{ row.institution }}
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
import _debounce from 'lodash/debounce'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import NzPerPageSelect from '@/components/table/PerPageSelect'
import NzSortableColumnHeader from '@/components/table/SortableColumnHeader'

export default {
  name: 'nzUsersTable',

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

    performSearch: _debounce(function (value) {
      if (this.search !== value) {
        this.search = value
        this.loadAsyncData()
      }
    }, 500),

    clearSearch() {
      this.search = ''
      this.loadAsyncData()
    }
  }
}
</script>
