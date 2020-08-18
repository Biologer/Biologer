<template>
  <div class="taxa-table">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <button
              type="button"
              class="button is-touch-full"
              @click="showFilter = !showFilter"
          >
            <b-icon icon="filter" :class="[filterIsActive ? 'has-text-primary' : 'has-text-grey-light']" />
            <span>{{ trans('buttons.filters') }}</span>
          </button>
        </div>
      </div>

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

    <b-collapse :open="showFilter" class="mt-4">
      <form @submit.prevent="applyFilter">
        <div class="columns">
          <b-field :label="trans('labels.taxa.rank')" class="column is-half">
            <b-select v-model="newFilter.rank" expanded>
              <option value=""></option>
              <option
                v-for="(rank, index) in ranks"
                :value="rank.value"
                :key="index"
                v-text="rank.label"
              />
            </b-select>
          </b-field>

          <div class="column is half">
            <nz-taxon-autocomplete
              @select="onTaxonSelect"
              v-model="newFilter.name"
              :taxon="newFilter.selectedTaxon"
              :label="trans('labels.taxa.name')"
              placeholder=""
            />
            <b-checkbox v-model="newFilter.includeChildTaxa">{{ trans('labels.taxa.include_lower_taxa') }}</b-checkbox>
          </div>
        </div>

        <button type="submit" class="button is-primary is-outlined" @click="applyFilter">{{ trans('buttons.apply') }}</button>

        <button type="button" class="button" @click="clearFilter">{{ trans('buttons.clear') }}</button>
      </form>
    </b-collapse>

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

      :checkable="hasActions"
      :checked-rows.sync="checkedRows"
    >

      <template slot-scope="{ row }">
        <b-table-column field="id" :label="trans('labels.id')" width="40" numeric sortable>
          {{ row.id }}
        </b-table-column>

        <b-table-column field="rank_level" :label="trans('labels.taxa.rank')" sortable>
          {{ row.rank_translation }}
        </b-table-column>

        <b-table-column field="name" :label="trans('labels.taxa.name')" sortable>
          {{ row.name + (row.native_name ? ` (${row.native_name})` : '') }}
        </b-table-column>

        <b-table-column width="150" numeric>
          <a @click="openActivityLogModal(row)" v-if="showActivityLog && row.activity && row.activity.length > 0" :title="trans('Activity Log')"><b-icon icon="history" /></a>

          <a :href="editLink(row)" v-if="row.can_edit"><b-icon icon="edit"></b-icon></a>

          <a @click="confirmRemove(row)" v-if="row.can_delete"><b-icon icon="trash"></b-icon></a>
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

    <b-modal :active="activityLog.length > 0" @close="activityLog = []" has-modal-card>
      <div class="modal-card">
        <div class="modal-card-head">
          <b-icon icon="history" />
          <p class="modal-card-title">{{ trans('Activity Log') }}</p>
        </div>
        <div class="modal-card-body">
          <nz-taxon-activity-log :activities="activityLog" />
        </div>
      </div>
    </b-modal>

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
import ExportDownloadModal from '@/components/exports/ExportDownloadModal'
import NzTaxonAutocomplete from '@/components/inputs/TaxonAutocomplete'
import NzTable from '@/components/table/Table'
import NzExportModal from '@/components/exports/ExportModal'

export default {
  name: 'nzTaxaTable',

  mixins: [FilterableTableMixin, PersistentTableMixin],

  components: {
    NzTaxonAutocomplete,
    NzTable,
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
    ranks: Array,
    showActivityLog: Boolean,
    exportColumns: Array,
    exportUrl: String
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
      showExportModal: false
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
    }
  },

  created() {
    this.restoreState()
    this.addMissingValuesAfterRestoration()
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

      const { selectedTaxon, ...filter } = this.filter

      return axios.get(route(this.listRoute).withQuery({
        ...filter,
        sort_by: this.sortBy,
        page: this.page,
        per_page:this.perPage,
      })).then(({ data: response }) => {
        this.data = response.data
        this.total = response.meta.total
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

    openActivityLogModal(row) {
      this.activityLog = row.activity
    },

    filterDefaults() {
      return {
        name: null,
        rank: null,
        taxonId: null,
        includeChildTaxa: null,
        selectedTaxon: null,
        id: null
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

    onTaxonSelect(taxon) {
      this.newFilter.taxonId = taxon ? taxon.id : null
      this.newFilter.selectedTaxon = taxon
    },

    getPersistantKeys() {
      return [
        'sortField', 'sortOrder', 'perPage', 'page',
        'newFilter', 'filter', 'filterIsActive'
      ]
    },

    addMissingValuesAfterRestoration() {
      this.page = this.page || 1
      this.perPage = this.perPage || this.perPageOptions[0]
      this.sortField = this.sortField || 'id'
      this.sortOrder = this.sortOrder || 'desc'
    }
  }
}
</script>
