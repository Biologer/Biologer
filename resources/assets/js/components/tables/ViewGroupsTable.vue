<template>
    <div class="view-groups-table">
        <b-table
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

                <b-table-column field="name" :label="trans('labels.view_groups.name')">
                    {{ row.name }}
                </b-table-column>

                <b-table-column :label="trans('labels.actions')" width="100">
                    <a :href="editLink(row)"><b-icon icon="edit"></b-icon></a>

                    <a @click="confirmRemove(row)"><b-icon icon="trash"></b-icon></a>
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
                <b-field>
                    <b-select :value="perPage" @input="onPerPageChange" placeholder="Per page">
                        <option
                            v-for="(option, index) in perPageOptions"
                            :value="option"
                            :key="index"
                            v-text="option"/>
                    </b-select>
                </b-field>
            </template>
        </b-table>
    </div>
</template>

<script>
import axios from 'axios';
import PersistentTableMixin from '../../mixins/PersistentTableMixin';

export default {
    name: 'nzViewGroupsTable',

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
        ranks: Array,
        showActivityLog: Boolean
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
            checkedRows: []
        };
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
                per_page: this.perPage,
                ...this.filter
            })).then(({ data }) => {
                this.data = [];
                this.total = data.meta.total;
                data.data.forEach((item) => this.data.push(item));
                this.loading = false;
            }, response => {
                this.data = [];
                this.total = 0;
                this.loading = false;
            });
        },

        /*
         * Handle page-change event
         */
        onPageChange(page) {
            this.page = page;
            this.loadAsyncData();
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
