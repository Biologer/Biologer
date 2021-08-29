@extends('layouts.main', ['title' => __('navigation.partially_open_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Partially Open License</h1>
        <p class="subtitle is-2 has-text-centered">License for data with a limited geographical view</p>

        <section class="section content has-text-justified">
            <p class="mb-4">
                This license specifies the terms and conditions of Biologer "limited view"
                data usage from the database. Data is displayed on the map in the form
                of a 10×10 km square.
            </p>

            <p class="mb-4">
                The basis of this license is Creative Commons License,
                Attribution-Noncommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0).
                This means that data with limited are allowed to be shared with third
                parties under the terms of the Creative Commons
                <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" title="CC BY-NC-SA 4.0" target="_blank">licenses</a>.
            </p>

            <p class="mb-4">
                The difference compared to a completely open data is only reflected
                in the precision of the geographical location and the date of observation.
                Limited data view allows displaying of locations in the form of a 10×10 km squares,
                without the possibility of showing places more accurately to other users
                of the database and hiding data about the month and day of records.
                Other information that came with the observations will be fully shown.
                This applies both to the data view within Biologer and to the data that
                are available for export from the database or sharing within larges systems
                for data aggregation.
            </p>

            <p class="mb-4">
                By selecting this license authors retain all rights to use the precise
                location and date of their observations. Biologer.org administrators and
                taxonomic experts reserve the right to use all data entered under this license
                in order to produce the documents of national importance, for nature conservation
                (Red Book, Red List, revision of legislature, defining the borders of protested areas, etc.),
                as well as for statistical data processing (e.g. scientific publications,
                protection and conservation of the species and their habitats) but without
                publicly releasing the exact location of the original data. In the case that
                the location of the original data, which is recorded under this license,
                is needed for publishing, Аdministrators of Biologer.org and taxonomic experts
                must do so with the consent of the authors.
            </p>
        </section>
    </div>
@endsection
