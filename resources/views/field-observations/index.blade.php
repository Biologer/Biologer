@extends('layouts.contributor')

@section('main')
    <div class="container">
        <section class="section">
            <div class="box">
                <b-table
                    :data="{{ json_encode($observations->items()) }}"
                    :loading="false"
                    :mobile-cards="true"
                    detailed>

                    <template scope="props">
                        <b-table-column label="ID" width="40" numeric v-text="props.row.id">
                        </b-table-column>

                        <b-table-column label="Taxon" v-text="props.row.taxon ? props.row.taxon.name : ''">
                        </b-table-column>

                        <b-table-column label="Year" v-text="props.row.year">
                        </b-table-column>

                        <b-table-column label="Month" v-text="props.row.month">
                        </b-table-column>

                        <b-table-column label="Day" v-text="props.row.day">
                        </b-table-column>

                        <b-table-column label="Source" v-text="props.row.source">
                        </b-table-column>

                        <b-table-column label="Actions">
                            <a :href="'/contributor/field-observations/'+props.row.id+'/edit'">Edit</a>
                        </b-table-column>
                    </template>

                    <template slot="empty">
                        <section class="section">
                            <div class="content has-text-grey has-text-centered">
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>

                    <template slot="detail" scope="props">
                        <article class="media">
                            <figure class="media-left">
                                <p class="image is-64x64" v-for="photo in props.row.photos">
                                    <img :src="photo">
                                </p>
                            </figure>
                            <div class="media-content">
                            <div class="content">
                                <strong>@{{ props.row.location }}</strong>
                                <small>@{{ props.row.latitude }}, @{{ props.row.longitude }}</small><br>
                                <small>Elevation: @{{ props.row.altitude}}m</small><br>
                                <small>Accuracy: @{{ props.row.accuracy}}m</small>
                            </div>
                        </div>
                        </article>
                    </template>
                </b-table>
            </div>
        </section>
    </div>
@endsection
