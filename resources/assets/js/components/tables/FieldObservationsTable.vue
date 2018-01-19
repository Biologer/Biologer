<template>
    <div class="field-observations-table">
        <div class="buttons" v-if="approvable">
            <button
                type="button"
                class="button"
                :class="{'is-loading': approving}"
                :disabled="!checkedRows.length"
                @click="confirmApprove"
            >
                <b-icon icon="check" class="has-text-success"></b-icon>

                <span>Approve</span>
            </button>

            <button
                type="button"
                class="button"
                :class="{'is-loading': markingAsUnidentifiable}"
                :disabled="!checkedRows.length"
                @click="confirmMarkingAsUnidentifiable"
            >
                <b-icon icon="times" class="has-text-danger"></b-icon>

                <span>Unidentifiable</span>
            </button>
        </div>

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

            detailed
            :mobile-cards="true"

            :checkable="approvable"
            :checked-rows.sync="checkedRows"
        >
            <template slot-scope="{ row }">
                <b-table-column field="id" label="ID" width="40" numeric sortable>
                    {{ row.id }}
                </b-table-column>

                <b-table-column field="taxon_name" label="Taxon" sortable>
                    {{ row.taxon ? row.taxon.name : '' }}
                </b-table-column>

                <b-table-column field="year" label="Year" numeric sortable>
                    {{ row.year }}
                </b-table-column>

                <b-table-column field="month" label="Month" numeric sortable>
                    {{ row.month }}
                </b-table-column>

                <b-table-column field="day" label="Day" numeric sortable>
                    {{ row.day }}
                </b-table-column>

                <b-table-column field="observer" label="Observer" sortable>
                    {{ row.observer }}
                </b-table-column>

                <b-table-column label="Actions" width="100">
                    <a @click="openImageModal(row.photos)" v-if="row.photos.length"><b-icon icon="photo"></b-icon></a>

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

            <template slot="detail" slot-scope="{ row }">
                <article class="media">
                    <figure class="media-left">
                        <p class="image is-64x64" v-for="(photo, index) in row.photos" :key="photo.id">
                            <img class="is-clickable" :src="photo.url" @click="openImageModal(row.photos, index)">
                        </p>
                    </figure>

                    <div class="media-content">
                        <div class="content">
                            <strong>{{ row.location }}</strong>

                            <small>{{ row.latitude }}, {{ row.longitude }}</small><br>

                            <small>Elevation: {{ row.elevation}}m</small><br>

                            <small>Accuracy: {{ row.accuracy}}m</small>
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
                            :key="index"
                            v-text="option"/>
                    </b-select>
                </b-field>
            </template>
        </nz-table>

        <nz-image-modal :items="modalImages" v-model="modalImageIndex" v-if="modalImages.length" @close="onCarouselClose"/>
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
        approveRoute: String,
        markAsUnidentifiableRoute: String,
        empty: {
            type: String,
            default: 'Nothing here.'
        },
        approvable: Boolean
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
            modalImages: [],
            modalImageIndex: 0,
            checkedRows: [],
            approving: false,
            markingAsUnidentifiable: false
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

        openImageModal(photos, open) {
            this.modalImageIndex = open;
            this.modalImages = photos.map(photo => photo.url);
        },

        onCarouselClose() {
            this.modalImages = [];
            this.modalImages = 0;
        },

        confirmApprove() {
            this.$dialog.confirm({
                message: 'You are about to approve checked observations.<br/>Those of them that cannot be approved, will not be approved.',
                confirmText: 'Approve',
                type: 'is-primary',
                onConfirm: this.approve.bind(this)
            })
        },

        approve() {
            this.approving = true;

            axios.post(route(this.approveRoute), {
                field_observation_ids: this.checkedRows.map(row => row.id)
            }).then(this.successfullyApproved).catch(this.failedToApprove)
        },

        successfullyApproved() {
            this.checkedRows = [];
            this.approving = false;

            this.$toast.open({
                message: 'Observations have been approved',
                type: 'is-success'
            });

            this.loadAsyncData();
        },

        failedToApprove(error) {
            this.approving = false;

            this.$toast.open({
                message: 'Observations cannot be approved',
                type: 'is-danger',
                duration: 5000
            });
        },

        confirmMarkingAsUnidentifiable() {
            this.$dialog.confirm({
                message: 'You are about to mark checked observations as unidentifiable. If some of them cannot be marked as unidentifiable none will be.',
                confirmText: 'Mark as unidentifiable',
                type: 'is-warning',
                onConfirm: this.markAsUnidentifiable.bind(this)
            })
        },

        markAsUnidentifiable() {
            this.markingAsUnidentifiable = true;

            axios.post(route(this.markAsUnidentifiableRoute), {
                field_observation_ids: this.checkedRows.map(row => row.id)
            }).then(this.successfullyMarkedAsUnidentifiable)
            .catch(this.failedToMarkAsUnidentifiable)
        },

        successfullyMarkedAsUnidentifiable() {
            this.checkedRows = [];
            this.markingAsUnidentifiable = false;
            this.$toast.open({
                message: 'Observations have been marked as unidentifiable',
                type: 'is-success'
            });
            this.loadAsyncData();
        },

        failedToMarkAsUnidentifiable(error) {
            this.markingAsUnidentifiable = false;
            this.$toast.open({
                message: 'Some of the observations cannot be marked as unidentifiable',
                type: 'is-danger',
                duration: 5000
            });
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
