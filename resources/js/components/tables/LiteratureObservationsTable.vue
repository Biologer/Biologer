<template>
  <div class="literature-observations-table">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <button
            type="button"
            class="button is-touch-full"
            @click="showFilter = !showFilter"
          >
            <b-icon
              icon="filter"
              :size="filterIsActive ? null : 'is-small'"
              :class="[filterIsActive ? 'has-text-primary' : 'has-text-grey']"
            />

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

    <b-collapse :open="showFilter" class="mt-4">
      <form @submit.prevent="applyFilter">
        <div class="columns is-multiline">
          <div class="column is half">
            <nz-taxon-autocomplete
              @select="onTaxonSelect"
              v-model="newFilter.taxon"
              :taxon="newFilter.selected_taxon"
              :label="trans('labels.literature_observations.taxon')"
              :placeholder="trans('labels.literature_observations.search_for_taxon')"
            />
            <b-checkbox v-model="newFilter.include_child_taxa">{{ trans('labels.literature_observations.include_lower_taxa') }}</b-checkbox>
          </div>

          <b-field :label="trans('labels.literature_observations.date')" class="column is-half">
            <b-field expanded grouped>
              <b-field expanded>
                <b-input
                  :placeholder="trans('labels.literature_observations.year')"
                  v-model="newFilter.year"
                />
              </b-field>

              <b-field expanded>
                <b-select
                  :placeholder="trans('labels.literature_observations.month')"
                  v-model="newFilter.month"
                  expanded
                >
                  <option :value="null"></option>

                  <option v-for="(month, index) in months" :key="index" :value="(index + 1)" v-text="month"></option>
                </b-select>
              </b-field>

              <b-field expanded>
                <b-select
                  :placeholder="trans('labels.literature_observations.day')"
                  v-model="newFilter.day"
                  expanded
                >
                  <option :value="null"></option>

                  <option v-for="day in days" :value="day" :key="day" v-text="day"></option>
                </b-select>
              </b-field>
            </b-field>
          </b-field>

          <nz-user-autocomplete
            class="column is-half"
            v-model="newFilter.observer"
            :label="trans('labels.literature_observations.observer')"
            placeholder=""
          />

          <b-field :label="trans('labels.literature_observations.project')" class="column is-half">
            <b-input v-model="newFilter.project" expanded />
          </b-field>

          <b-field :label="trans('labels.id')" class="column is-one-third">
            <b-input v-model="newFilter.id" expanded />
          </b-field>

          <nz-publication-autocomplete
            class="column is-two-thirds"
            v-model="newFilter.publication_citation"
            @select="onPublicationSelect"
            :publication="newFilter.selected_publication"
            :label="trans('labels.literature_observations.publication')"
            :placeholder="trans('labels.literature_observations.search_for_publication')"
          />
        </div>

        <button type="submit" class="button is-primary is-outlined">{{ trans('buttons.apply') }}</button>
        <button type="button" class="button" @click="clearFilter">{{ trans('buttons.clear') }}</button>
      </form>
    </b-collapse>

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

      detailed
      mobile-cards

      :checkable="hasActions"
      :checked-rows.sync="checkedRows"
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

      <b-table-column field="taxon_name" :label="trans('labels.literature_observations.taxon')" sortable>
        <template #default="{ row }">
          {{ row.taxon ? row.taxon.name : '' }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="year" :label="trans('labels.literature_observations.year')" numeric sortable>
        <template #default="{ row }">
          {{ row.year }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="month" :label="trans('labels.literature_observations.month')" numeric sortable>
        <template #default="{ row }">
          {{ row.month }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="day" :label="trans('labels.literature_observations.day')" numeric sortable>
        <template #default="{ row }">
          {{ row.day }}
        </template>
        <template #header="{ column }">
          <nz-sortable-column-header :column="column" :sort="{ field: sortField, order: sortOrder }" />
        </template>
      </b-table-column>

      <b-table-column field="publication_citation" :label="trans('labels.literature_observations.publication')">
        <template #default="{ row }">
          <span v-tooltip="{ content: row.publication.citation }">
            {{ row.publication.citation | truncate(50) }}
          </span>
        </template>
      </b-table-column>

      <b-table-column width="150" numeric v-slot="{ row }">
        <a @click.prevent="openActivityLogModal(row)" v-if="showActivityLog && row.activity" :title="trans('Activity Log')"><b-icon icon="history" /></a>

        <a :href="viewLink(row)" v-if="viewRoute" :title="trans('buttons.view')"><b-icon icon="eye" /></a>

        <a :href="editLink(row)" v-if="editRoute" :title="trans('buttons.edit')"><b-icon icon="edit" /></a>

        <a @click.prevent="confirmRemove(row)" v-if="deleteRoute" :title="trans('buttons.delete')"><b-icon icon="trash" /></a>
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

              <small>{{ trans('labels.literature_observations.elevation') }}: {{ row.elevation}}m</small><br>

              <small v-if="row.accuracy">{{ trans('labels.literature_observations.accuracy') }}: {{ row.accuracy}}m</small>
            </div>
          </div>
        </article>
      </template>
    </b-table>

    <b-modal :active="activityLog.length > 0" @close="activityLog = []" has-modal-card>
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
        :types="['custom', 'darwin_core']"
      />
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios'
import _range from 'lodash/range'
import dayjs from '@/dayjs'
import FilterableTableMixin from '@/mixins/FilterableTableMixin'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import ExportDownloadModal from '@/components/exports/ExportDownloadModal'
import NzPublicationAutocomplete from '@/components/inputs/PublicationAutocomplete'
import NzTaxonAutocomplete from '@/components/inputs/TaxonAutocomplete'
import NzUserAutocomplete from '@/components/inputs/UserAutocomplete'
import NzPerPageSelect from '@/components/table/PerPageSelect'
import NzSortableColumnHeader from '@/components/table/SortableColumnHeader'
import NzExportModal from '@/components/exports/ExportModal'

export default {
  name: 'nzLiteratureObservationsTable',

  mixins: [FilterableTableMixin, PersistentTableMixin],

  components: {
    NzPerPageSelect,
    NzSortableColumnHeader,
    NzExportModal,
    NzPublicationAutocomplete,
    NzTaxonAutocomplete,
    NzUserAutocomplete
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
      return _range(1, 32)
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
      this.checkedRows = []

      const { selected_taxon, selected_publication, publication_citation, ...filter } = this.filter

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
        taxon: null,
        taxon_id: null,
        include_child_taxa: false,
        selected_taxon: null,
        year: null,
        month: null,
        day: null,
        observer: null,
        project: null,
        selected_publication: null,
        publication_id: null,
        publication_citation: null,
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
      this.newFilter.taxon_id = taxon ? taxon.id : null
      this.newFilter.selected_taxon = taxon
    },

    onPublicationSelect(publication) {
      this.newFilter.publication_id = publication ? publication.id : null
      this.newFilter.selected_publication = publication
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
