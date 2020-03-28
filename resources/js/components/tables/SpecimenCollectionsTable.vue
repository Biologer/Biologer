<template>
  <div class="specimen-collections-table">
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

        <b-table-column field="name" :label="trans('labels.specimen_collections.name')" sortable>
          {{ props.row.name }}
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
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import NzTable from '@/components/table/Table'

export default {
  name: 'nzSpecimenCollectionsTable',

  mixins: [PersistentTableMixin],

  components: {
    NzTable
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
      sortOrder: 'desc',
      defaultSortOrder: 'asc',
      page: 1,
      perPage: this.perPageOptions[0],
      checkedRows: [],
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
        message: this.trans('This will remove all observations connected to this collection. Are you sure you want to delete this collection?'),
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
    }
  }
}
</script>
