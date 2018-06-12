<template>
    <div class="users-table">
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
    </div>
</template>

<script>
import axios from 'axios';
import PersistentTableMixin from '../../mixins/PersistentTableMixin';

export default {
    name: 'nzUsersTable',

    mixins: [PersistentTableMixin],

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
            defaultSortOrder: 'asc',
            page: 1,
            perPage: this.perPageOptions[0],
            checkedRows: []
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
    },

    methods: {
        loadAsyncData() {
            this.loading = true;

            return axios.get(route(this.listRoute, {
                sort_by: `${this.sortField}.${this.sortOrder}`,
                page: this.page,
                per_page: this.perPage
            })).then(({ data }) => {
                this.data = [];
                this.total = data.meta.total;
                data.data.forEach((item) => this.data.push(item));
                this.meta = data.meta;
                this.loading = false;
            }, response => {
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
            this.sortField = field;
            this.sortOrder = order;

            this.saveState();

            this.loadAsyncData();
        },

        clearFilter() {
            for (let field in this.newFilter) {
                this.newFilter[field] = ''
            }

            this.onFilter()
        },

        onFilter() {
            let reload = false;

            for (let field in this.newFilter) {
                if (this.filter[field] !== this.newFilter[field]) {
                    reload = true;
                }

                this.filter[field] = this.newFilter[field];
            }

            if (reload) {
                this.loadAsyncData()
            }
        },

        onPerPageChange(perPage) {
            if (perPage === this.perPage) return;

            this.perPage = perPage;

            this.saveState();

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
        }
    }
}
</script>
