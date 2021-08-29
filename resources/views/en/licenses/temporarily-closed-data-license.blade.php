@extends('layouts.main', ['title' => __('navigation.closed_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Temporarily losed License</h1>
        <p class="subtitle is-2 has-text-centered">License for data with temporary limited access (closes for {{ config('biologer.license_closed_period') }} years)</p>

        <section class="section content has-text-justified">
            <p class="mb-4">
                This license specifies the terms and conditions of the data usage
                from Biologer database for users who do not want to instantly share
                their data with others. Data is hidden for {{ config('biologer.license_closed_period') }} first years and does
                not appear on the map, but will be completely published afterwards.
                Such way of saving data could be useful if the data has been collected
                for a scientific study, leaving enough time to the author to independently
                publish data before their publication in Biologer platform.
            </p>

            <p class="mb-4">
                Selecting this license temporary restricts the visibility of data for
                other users of the database. In the first {{ config('biologer.license_closed_period') }} years after the data entry,
                it will be visible only to the author of the observation, to the taxonomic
                experts who verify the findings and to the website administrators.
                Data under this license will not be instantly shown on the taxa distribution maps,
                and other users will not be able to export this kind of data from the database.
                In {{ config('biologer.license_closed_period') }} years after the observation is entered into database,
                the data becomes open and will be shared under the conditions of the
                Creative Commons license Attribution-ShareAlike 4.0 International (CC BY-SA 4.0).
            </p>

            <p class="mb-4">
                By selecting this license authors retain all rights to the data they entered.
                Biologer.org administrators and taxonomic experts reserve the right to use
                data entered under this license in order to produce the documents of national importance,
                for nature conservation (Red Book, Red List, revision of legislature, defining
                borders of the protected areas etc.), as well as for statistical data processing
                (e.g. protection and conservation of the taxa and their habitats) but without
                publicly releasing the exact location of the original data. In scientific publications,
                this data could only be used in summary form, without details about original record.
                In the case that the location of the original data, which is recorded under this license,
                is required for publishing, Administrators of Biologer.org and taxonomic experts
                must do so with the consent of the authors.
            </p>
        </section>
    </div>
@endsection
