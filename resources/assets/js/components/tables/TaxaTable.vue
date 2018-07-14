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
        </div>

        <b-collapse :open="showFilter" class="mt-4">
            <form @submit.prevent="applyFilter">
                <div class="columns">
                  <b-field :label="trans('labels.taxa.rank')" class="column">
                      <b-select v-model="newFilter.rank" expanded>
                          <option value=""></option>
                          <option
                              v-for="(rank, index) in ranks"
                              :value="rank.value"
                              :key="index"
                              v-text="rank.label">
                          </option>
                      </b-select>
                  </b-field>

                  <b-field :label="trans('labels.taxa.name')" class="column">
                      <b-input v-model="newFilter.name"></b-input>
                  </b-field>
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

            backend-sorting
            :default-sort-direction="defaultSortOrder"
            :default-sort="[sortField, sortOrder]"
            @sort="onSort"

            :mobile-cards="true">

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

            <template slot="bottom-left">
                <div class="level-item">
                    <b-field>
                        <b-select :value="perPage" @input="onPerPageChange" placeholder="Per page">
                            <option
                                v-for="(option, index) in perPageOptions"
                                :value="option"
                                :key="index"
                                v-text="option"/>
                        </b-select>
                    </b-field>
                </div>

                <div class="level-item">{{ showing }}</div>
            </template>
        </nz-table>

        <b-modal :active="activityLog.length > 0" @close="activityLog = []">
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
    </div>
</template>

<script>
import axios from 'axios';
import FilterableTableMixin from '../../mixins/FilterableTableMixin';
import PersistentTableMixin from '../../mixins/PersistentTableMixin';

export default {
    name: 'nzTaxaTable',

    mixins: [FilterableTableMixin, PersistentTableMixin],

    props: {
        perPageOptions: {
            type: Array,
            default() {
                return [15, 30, 50, 100];
            },
            validator(value) {
                return value.length;
            }
        },
        listRoute: String,
        editRoute: String,
        deleteRoute: String,
        empty: {
            type: String,
            default: 'Nothing here.'
        },
        ranks: Array,
        showActivityLog: Boolean
    },

    data() {
        return {
            data: [],
            meta: null,
            total: 0,
            loading: false,
            sortField: 'id',
            sortOrder: 'desc',
            defaultSortOrder: 'asc',
            page: 1,
            perPage: this.perPageOptions[0],
            checkedRows: [],
            activityLog: []
        };
    },

    computed: {
        showing() {
            if (!this.meta) return;

            return this.trans('labels.tables.from_to_total', {
                from: _.get(this.meta, 'from'),
                to: _.get(this.meta, 'to'),
                total: _.get(this.meta, 'total')
            });
        }
    },

    created() {
        this.restoreState();
        this.loadAsyncData();

        this.$on('filter', this.loadAsyncData);
    },

    methods: {
        loadAsyncData() {
            this.loading = true;

            return axios.get(route(this.listRoute, {
                sort_by: `${this.sortField}.${this.sortOrder}`,
                page: this.page,
                per_page: this.perPage,
                ...this.filter
            })).then(({ data }) => {
                this.data = [];
                this.total = data.meta.total;
                data.data.forEach((item) => this.data.push(item));
                this.meta = data.meta;
                this.loading = false;
            }, (response) => {
                this.data = [];
                this.meta = null;
                this.total = 0;
                this.loading = false;
            });
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

            this.loadAsyncData()
        },

        onPerPageChange(perPage) {
            if (perPage === this.perPage) return;

            this.perPage = perPage;

            this.loadAsyncData();
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
                });

                this.loadAsyncData();
            }).catch(error => { console.error(error) })
        },

        editLink (row) {
            return route(this.editRoute, row.id);
        },

        openActivityLogModal(row) {
            this.activityLog = row.activity;
        },

        filterDefaults() {
            return {
                name: '',
                rank: ''
            };
        }
    }
}
</script>
