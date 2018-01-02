<template>
    <div class="field-observations-table">
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

            detailed
            :mobile-cards="true">

            <template slot-scope="props">
                <b-table-column field="id" label="ID" width="40" numeric sortable>
                    {{ props.row.id }}
                </b-table-column>

                <b-table-column field="taxon_name" label="Taxon" sortable>
                    {{ props.row.taxon ? props.row.taxon.name : '' }}
                </b-table-column>

                <b-table-column field="year" label="Year" numeric sortable>
                    {{ props.row.year }}
                </b-table-column>

                <b-table-column field="month" label="Month" numeric sortable>
                    {{ props.row.month }}
                </b-table-column>

                <b-table-column field="day" label="Day" numeric sortable>
                    {{ props.row.day }}
                </b-table-column>

                <b-table-column field="observer" label="Observer" sortable>
                    {{ props.row.observer }}
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

            <template slot="detail" slot-scope="props">
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-64x64" v-for="photo in props.row.photos">
                            <img class="is-clickable" :src="photo" @click="openImageModal(photo)">
                        </p>
                    </figure>
                    <div class="media-content">
                        <div class="content">
                            <strong>{{ props.row.location }}</strong>
                            <small>{{ props.row.latitude }}, {{ props.row.longitude }}</small><br>
                            <small>Elevation: {{ props.row.elevation}}m</small><br>
                            <small>Accuracy: {{ props.row.accuracy}}m</small>
                        </div>
                    </div>
                </article>
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

        <b-modal :active.sync="isImageModalActive" :can-cancel="['escape', 'x']">
            <div class="image is-4by3">
                <img :src="modalImage">
            </div>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'nzFieldObservationsTable',

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
        }
    },

    data() {
        return {
            data: [],
            total: 0,
            loading: false,
            sortField: 'id',
            sortOrder: 'desc',
            defaultSortOrder: 'desc',
            page: 1,
            perPage: this.perPageOptions[0],
            isImageModalActive: false,
            modalImage: null
        };
    },

    methods: {
        loadAsyncData() {
            this.loading = true;

            return axios.get(route(this.listRoute, {
                sort_by: `${this.sortField}.${this.sortOrder}`,
                page: this.page,
                per_page:this.perPage
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
        },

        openImageModal(imageUrl) {
            this.modalImage = imageUrl;

            this.isImageModalActive = true;
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
    },

    mounted() {
        this.loadAsyncData()
    }
}
</script>
