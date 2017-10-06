@extends('layouts.contributor')

@section('main')
    <div class="container">
        <section class="section">
            <div class="box">
                <b-table
                    :data="{{ json_encode($observations->items()) }}"
                    :loading="false"
                    :mobile-cards="true">

                    <template scope="props">
                        <b-table-column label="ID" width="40" numeric>
                            @{{ props.row.id }}
                        </b-table-column>

                        <b-table-column label="Taxon">
                            @{{ props.row.taxon ? props.row.taxon.name : '' }}
                        </b-table-column>

                        <b-table-column label="Year">
                            @{{ props.row.year }}
                        </b-table-column>

                        <b-table-column label="Month">
                            @{{ props.row.month }}
                        </b-table-column>

                        <b-table-column label="Day">
                            @{{ props.row.day }}
                        </b-table-column>

                        <b-table-column label="Source">
                            @{{ props.row.source }}
                        </b-table-column>
                    </template>

                    <template slot="empty">
                        <section class="section">
                            <div class="content has-text-grey has-text-centered">
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>
                </b-table>


                {{-- <table class="table is-fullwidth">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Taxon</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Day</th>
                            <th>Source</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($observations as $observation)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $observation->id }}"></td>
                                <td>{{ $observation->id }}</td>
                                <td>{{ optional($observation->observation->taxon)->name }}</td>
                                <td>{{ $observation->observation->year }}</td>
                                <td>{{ $observation->observation->month }}</td>
                                <td>{{ $observation->observation->day }}</td>
                                <td>{{ $observation->source }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $observations->links() }} --}}
            </div>
        </section>
    </div>
@endsection
