<template>
    <div class="taxa-table">
        <div class="buttons has-addons">
            <button type="button"
                class="button"
                @click="showFilter = !showFilter">
                <b-icon icon="filter"></b-icon>
                <span>Filters</span>
            </button>
            <button type="button" class="button" @click="clearFilter">Clear</button>
        </div>
        <b-collapse :open="showFilter">
            <div class="columns">
                <b-field label="Category" class="column">
                    <b-select v-model="newFilter.category_level" @input="onFilter" expanded>
                        <option value=""></option>
                        <option
                            v-for="(category, index) in categories"
                            :value="category.value"
                            :key="index"
                            v-text="category.name">
                        </option>
                    </b-select>
                </b-field>

                <b-field label="Name" class="column">
                    <b-input v-model="newFilter.name" @blur="onFilter" @keyup.enter.native="onFilter"></b-input>
                </b-field>
            </div>
        </b-collapse>

        <hr>

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

            <template slot-scope="props">
                <b-table-column field="id" label="ID" width="40" numeric sortable>
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column field="category_level" label="Category" sortable>
                    {{ props.row.category }}
                </b-table-column>

                <b-table-column field="name" label="Name" sortable>
                    {{ props.row.name }}
                </b-table-column>

                <b-table-column label="Actions" width="100">
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
                <b-field>
                    <b-select :value="perPage" @input="onPerPageChange" placeholder="Per page">
                        <option
                            v-for="(option, index) in perPageOptions"
                            :value="option"
                            :key="index">
                            {{ option }}
                        </option>
                    </b-select>
                </b-field>
            </template>
        </b-table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'nzTaxaTable',

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
        categories: Array
    },

    data() {
        return {
            data: [],
            total: 0,
            loading: false,
            sortField: 'name',
            sortOrder: 'asc',
            defaultSortOrder: 'asc',
            page: 1,
            perPage: this.perPageOptions[0],
            checkedRows: [],
            showFilter: false,
            filter: {
                name: '',
                category_level: ''
            },
            newFilter: {
                name: '',
                category_level: ''
            }
        };
    },

    mounted() {
        this.loadAsyncData()
    },

    methods: {
        loadAsyncData() {
            this.loading = true;

            return axios.get(route(this.listRoute, {
                sort_by: `${this.sortField}.${this.sortOrder}`,
                page: this.page,
                per_page:this.perPage,
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

            this.loadAsyncData();
        },

        confirmRemove(row) {
            this.$dialog.confirm({
                message: 'Are you sure you want to delete this record?',
                confirmText: 'Remove',
                type: 'is-danger',
                onConfirm: () => { this.remove(row) }
            })
        },

        remove (row) {
            return axios.delete(route(this.deleteRoute, row.id)).then(response => {
                this.$toast.open({
                    message: 'Record deleted',
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
