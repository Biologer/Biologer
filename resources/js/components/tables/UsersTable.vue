<template>
  <div class="users-table">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <b-field>
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

      :mobile-cards="true"
    >
      <template slot-scope="props">
        <b-table-column field="id" :label="trans('labels.id')" width="40" numeric sortable>
          {{ props.row.id }}
        </b-table-column>

        <b-table-column field="first_name" :label="trans('labels.users.first_name')" sortable>
          {{ props.row.first_name }}
        </b-table-column>

        <b-table-column field="last_name" :label="trans('labels.users.last_name')" sortable>
          {{ props.row.last_name }}
        </b-table-column>

        <b-table-column field="email" :label="trans('labels.users.email')" sortable>
          {{ props.row.email }}
        </b-table-column>

        <b-table-column field="institution" :label="trans('labels.users.institution')" sortable>
          {{ props.row.institution }}
        </b-table-column>

        <b-table-column width="150" numeric>
          <a :href="editLink(props.row)"><b-icon icon="edit"></b-icon></a>

          <a @click="confirmRemove(props.row)"><b-icon icon="trash"></b-icon></a>
        </b-table-column>
      </template>

      <template slot="empty">
        <section class="section">
          <div class="content has-text-grey has-text-centered">
            <p>{{ empty }}</p>
          </div>
        </section>
      </template>
    </nz-table>
  </div>
</template>

<script>
import _debounce from 'lodash/debounce'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'

export default {
  name: 'nzUsersTable',

  mixins: [PersistentTableMixin],

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
      total: 0,
      loading: false,
      sortField: 'id',
      sortOrder: 'asc',
      defaultSortOrder: 'asc',
      page: 1,
      perPage: this.perPageOptions[0],
      checkedRows: [],
      search: ''
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
