<template>
  <div class="field-observations-table">
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
              :disabled="!checkedRows.length"
              @click="confirmApprove"
              v-if="approvable"
            >
              <b-icon icon="check" class="has-text-success" />

              <span>{{ trans('buttons.approve') }}</span>
            </b-dropdown-item>

            <b-dropdown-item
              :disabled="!checkedRows.length"
              @click="confirmMarkingAsUnidentifiable"
              v-if="markableAsUnidentifiable"
            >
              <b-icon icon="times" class="has-text-danger" />

              <span>{{ trans('buttons.unidentifiable') }}</span>
            </b-dropdown-item>

            <b-dropdown-item
              :disabled="!checkedRows.length"
              @click="confirmMoveToPending"
              v-if="movableToPending"
            >
              <b-icon icon="question" class="has-text-warning" />

              <span>{{ trans('buttons.move_to_pending') }}</span>
            </b-dropdown-item>

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
              :taxon="newFilter.selectedTaxon"
              :label="trans('labels.field_observations.taxon')"
              :placeholder="trans('labels.field_observations.search_for_taxon')"
            />
            <b-checkbox v-model="newFilter.includeChildTaxa">{{ trans('labels.field_observations.include_lower_taxa') }}</b-checkbox>
          </div>


          <b-field :label="trans('labels.field_observations.date')" class="column is-half">
            <b-field expanded grouped>
              <b-field expanded>
                <b-input
                  :placeholder="trans('labels.field_observations.year')"
                  v-model="newFilter.year"
                />
              </b-field>

              <b-field expanded>
                <b-select
                  :placeholder="trans('labels.field_observations.month')"
                  v-model="newFilter.month"
                  expanded
                >
                  <option :value="null"></option>

                  <option v-for="(month, index) in months" :key="index" :value="(index + 1)" v-text="month"></option>
                </b-select>
              </b-field>

              <b-field expanded>
                <b-select
                  :placeholder="trans('labels.field_observations.day')"
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
            v-if="showObserver"
            class="column is-half"
            v-model="newFilter.observer"
            :label="trans('labels.field_observations.observer')"
            placeholder=""
          />

          <b-field :label="trans('labels.field_observations.status')" class="column is-half" v-if="showStatus">
            <b-select v-model="newFilter.status" expanded>
              <option :value="null"></option>
              <option
                v-for="(status, index) in ['approved', 'unidentifiable', 'pending']"
                :value="status"
                :key="index"
                v-text="trans(`labels.field_observations.statuses.${status}`)">
              </option>
            </b-select>
          </b-field>

          <b-field :label="trans('labels.field_observations.photos')" class="column is-half">
            <b-select v-model="newFilter.photos" expanded>
              <option :value="null"></option>
              <option value="yes">{{ trans('Yes') }}</option>
              <option value="no">{{ trans('No') }}</option>
            </b-select>
          </b-field>

          <b-field :label="trans('labels.field_observations.project')" class="column is-half">
            <b-input v-model="newFilter.project" expanded />
          </b-field>
        </div>

        <button type="button" class="button is-primary is-outlined" @click="applyFilter">{{ trans('buttons.apply') }}</button>
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
      :current-page="page"
      @page-change="onPageChange"
      @per-page-change="onPerPageChange"
      :per-page-options="perPageOptions"
      pagination-on-top

      backend-sorting
      default-sort-direction="asc"
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

        <b-table-column field="taxon_name" :label="trans('labels.field_observations.taxon')" sortable>
          {{ row.taxon ? row.taxon.name : '' }}
        </b-table-column>

        <b-table-column field="year" :label="trans('labels.field_observations.year')" numeric sortable>
          {{ row.year }}
        </b-table-column>

        <b-table-column field="month" :label="trans('labels.field_observations.month')" numeric sortable>
          {{ row.month }}
        </b-table-column>

        <b-table-column field="day" :label="trans('labels.field_observations.day')" numeric sortable>
          {{ row.day }}
        </b-table-column>

        <b-table-column field="observer" :label="trans('labels.field_observations.observer')" sortable v-if="showObserver">
          {{ row.observer }}
        </b-table-column>

        <b-table-column field="status" :label="trans('labels.field_observations.status')" v-if="showStatus">
          <span :class="determineStatusClass(row.status)" :title="trans(`labels.field_observations.statuses.${row.status}`)">
            <b-icon :icon="determineStatusIcon(row.status)" />
          </span>
        </b-table-column>

        <b-table-column width="150" numeric>
          <a @click="openImageModal(row.photos)" v-if="observationHasPhotos(row)"><b-icon icon="photo" /></a>

          <a @click="openActivityLogModal(row)" v-if="showActivityLog" :title="trans('Activity Log')"><b-icon icon="history" /></a>

          <a :href="viewLink(row)" v-if="viewRoute" :title="trans('buttons.view')"><b-icon icon="eye" /></a>

          <a :href="editLink(row)" v-if="editRoute" :title="trans('buttons.edit')"><b-icon icon="edit" /></a>

          <a @click="confirmRemove(row)" v-if="deleteRoute" :title="trans('buttons.delete')"><b-icon icon="trash" /></a>
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
          <figure class="media-left">
            <p class="image is-64x64" v-for="(photo, index) in row.photos" :key="photo.id">
              <img class="is-clickable" :src="photo.url" @click="openImageModal(row.photos, index)">
            </p>
          </figure>

          <div class="media-content">
            <div class="content">
              <template v-if="row.location">
                <strong>{{ row.location }}</strong><br>
              </template>

              <template v-if="row.latitude && row.longitude">
                <small>{{ row.latitude }}, {{ row.longitude }}</small><br>
              </template>

              <small>{{ row.mgrs10k.replace(/^[0-9]+[a-zA-Z]/, '$& ') }}</small><br>

              <small>{{ trans('labels.field_observations.elevation') }}: {{ row.elevation }}m</small><br>

              <small v-if="row.accuracy">{{ trans('labels.field_observations.accuracy') }}: {{ row.accuracy }}m</small>
            </div>
          </div>
        </article>
      </template>
    </nz-table>

    <nz-image-modal :items="modalImages" v-model="modalImageIndex" v-if="modalImages.length" @close="onCarouselClose"/>

    <b-modal :active="activityLog.length > 0" @close="activityLog = []">
      <div class="modal-card">
        <div class="modal-card-head">
          <b-icon icon="history" />
          <p class="modal-card-title">{{ trans('Activity Log') }}</p>
        </div>
        <div class="modal-card-body">
          <nz-field-observation-activity-log :activities="activityLog" />
        </div>
      </div>
    </b-modal>

    <b-modal :active="showExportModal" @close="showExportModal = false" has-modal-card :can-cancel="[]">
      <nz-export-modal
        :checked="checkedIds"
        :filter="filter"
        :columns="exportColumns"
        :url="exportUrl"
        @cancel="showExportModal = false"
        @done="onExportDone"
      />
    </b-modal>
  </div>
</template>

<script>
import FilterableTableMixin from '@/mixins/FilterableTableMixin'
import PersistentTableMixin from '@/mixins/PersistentTableMixin'
import ExportDownloadModal from '@/components//exports/ExportDownloadModal'

export default {
  name: 'nzFieldObservationsTable',

  mixins: [FilterableTableMixin, PersistentTableMixin],

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
      approveRoute: String,
      markAsUnidentifiableRoute: String,
      moveToPendingRoute: String,
      empty: {
          type: String,
          default: 'Nothing here.'
      },
      approvable: Boolean,
      markableAsUnidentifiable: Boolean,
      movableToPending: Boolean,
      showStatus: Boolean,
      showActivityLog: Boolean,
      showObserver: Boolean,
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
          isImageModalActive: false,
          modalImages: [],
          modalImageIndex: 0,
          checkedRows: [],
          approving: false,
          markingAsUnidentifiable: false,
          movingToPending: false,
          activityLog: [],
          showExportModal: false
      }
  },

  computed: {
    hasActions() {
      return this.approvable
        || this.markableAsUnidentifiable
        || this.movableToPending
    },

    months() {
      return moment.months()
    },

    days() {
      return _.range(1, 31)
    },

    hasActions() {
      return this.approvable
        || this.markableAsUnidentifiable
        || this.movableToPending
        || this.exportable
    },

    actionRunning() {
      return this.approving || this.movingToPending || this.markingAsUnidentifiable
    },

    checkedIds() {
      return this.checkedRows.map(row => row.id)
    },

    exportable() {
      return !!(this.exportUrl && this.exportColumns.length)
    }
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
      const { selectedTaxon, ...filter } = this.filter

      return axios.get(route(this.listRoute, {
        ...filter,
        sort_by: `${this.sortField}.${this.sortOrder}`,
        page: this.page,
        per_page:this.perPage
      })).then(({ data: response }) => {
        this.data = []
        this.total = response.meta.total
        response.data.forEach((item) => this.data.push(item))
        this.loading = false
      }, () => {
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

    observationHasPhotos(observation) {
      return observation.photos.filter(photo => photo.url).length > 0
    },

    openImageModal(photos, open) {
      this.modalImageIndex = open
      this.modalImages = photos.map(photo => photo.url)
    },

    onCarouselClose() {
      this.modalImages = []
      this.modalImagesIndex = 0
    },

    confirmApprove() {
      this.$dialog.confirm({
        message: this.trans('You are about to approve checked observations.<br/>Those of them that cannot be approved, will not be approved.'),
        confirmText: this.trans('buttons.approve'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-primary',
        onConfirm: this.approve.bind(this)
      })
    },

    approve() {
      this.approving = true

      axios.post(route(this.approveRoute), {
        field_observation_ids: this.checkedRows.map(row => row.id)
      }).then(this.successfullyApproved).catch(this.failedToApprove)
    },

    successfullyApproved() {
      this.checkedRows = []
      this.approving = false

      this.$toast.open({
        message: this.trans('Observations have been approved'),
        type: 'is-success'
      })

      this.loadAsyncData()
    },

    failedToApprove(error) {
      this.approving = false

      this.$toast.open({
        message: this.trans('Observations cannot be approved'),
        type: 'is-danger',
        duration: 5000
      })
    },

    confirmMarkingAsUnidentifiable() {
      const dialog = this.$dialog.prompt({
        message: this.trans('You are about to mark checked observations as unidentifiable. What\'s the reason?'),
        confirmText: this.trans('buttons.mark_unidentifiable'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-warning',
        inputAttrs: {
          placeholder: this.trans('Reason'),
          required: true,
          maxlength: 255
        },
        onConfirm: this.markAsUnidentifiable.bind(this)
      })

      dialog.$nextTick(() => {
        this.validateReason(dialog)
      })
    },

    markAsUnidentifiable(reason) {
      this.markingAsUnidentifiable = true

      axios.post(route(this.markAsUnidentifiableRoute), {
        field_observation_ids: this.checkedRows.map(row => row.id),
        reason
      }).then(this.successfullyMarkedAsUnidentifiable)
      .catch(this.failedToMarkAsUnidentifiable)
    },

    successfullyMarkedAsUnidentifiable() {
      this.checkedRows = []
      this.markingAsUnidentifiable = false
      this.$toast.open({
        message: this.trans('Observations have been marked as unidentifiable'),
        type: 'is-success'
      })
      this.loadAsyncData()
    },

    failedToMarkAsUnidentifiable(error) {
      this.markingAsUnidentifiable = false
      this.$toast.open({
        message: this.trans('Some of the observations cannot be marked as unidentifiable'),
        type: 'is-danger',
        duration: 5000
      })
    },

    determineStatusIcon(status) {
      if (status === 'unidentifiable') {
        return 'times'
      }

      if (status === 'approved') {
        return 'check'
      }

      return 'question'
    },

    determineStatusClass(status) {
      if (status === 'unidentifiable') {
        return 'has-text-danger'
      }

      if (status === 'approved') {
        return 'has-text-success'
      }

      return 'has-text-info'
    },

    openActivityLogModal(row) {
      this.activityLog = row.activity
    },

    validateReason(dialog) {
      dialog.$refs.input.addEventListener('invalid', (e) => {
        e.target.setCustomValidity('')

        if (!e.target.validity.valid) {
          e.target.setCustomValidity(this.trans('This field is required and can contain max 255 chars.'))
        }
      })

      dialog.$refs.input.addEventListener('input', (e) => {
        dialog.validationMessage = null
      })
    },

    confirmMoveToPending() {
      const dialog = this.$dialog.prompt({
        message: this.trans('You are about to move checked observations to pending. What\'s the reason?'),
        confirmText: this.trans('buttons.move_to_pending'),
        cancelText: this.trans('buttons.cancel'),
        type: 'is-warning',
        inputAttrs: {
          placeholder: this.trans('Reason'),
          required: true,
          maxlength: 255
        },
        onConfirm: this.moveToPending.bind(this)
      })

      dialog.$nextTick(() => {
        this.validateReason(dialog)
      })
    },

    moveToPending(reason) {
      this.movingToPending = true

      axios.post(route(this.moveToPendingRoute), {
        field_observation_ids: this.checkedRows.map(row => row.id),
        reason
      }).then(this.successfullyMovedToPending).catch(this.failedToMoveToPending)
    },

    successfullyMovedToPending() {
      this.checkedRows = []
      this.movingToPending = false
      this.$toast.open({
        message: this.trans('Observations have been moved to pending'),
        type: 'is-success'
      })
      this.loadAsyncData()
    },

    failedToMoveToPending(error) {
      this.movingToPending = false
      this.$toast.open({
        message: this.trans('Whoops, looks like something went wrong.'),
        type: 'is-danger',
        duration: 5000
      })
      this.loadAsyncData()
    },

    filterDefaults() {
      return {
        status: null,
        taxon: null,
        taxonId: null,
        year: null,
        month: null,
        day: null,
        photos: null,
        observer: null,
        includeChildTaxa: false,
        selectedTaxon: null,
        project: null
      }
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
    },

    onTaxonSelect(taxon) {
      this.newFilter.taxonId = taxon ? taxon.id : null
      this.newFilter.selectedTaxon = taxon
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
