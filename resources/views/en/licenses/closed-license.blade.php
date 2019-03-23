@extends('layouts.main', ['title' => __('navigation.closed_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Closed License</h1>
        <p class="subtitle is-2 has-text-centered">License for data with limited access rights</p>

        <section class="section content has-text-justified">
            <p class="mb-4">
                This license specifies the terms and conditions of the data usage
                from Biologer.org database for users who do not want to share their
                data with others. Data is hidden and does not appear on the map.
            </p>

            <p class="mb-4">
                Selecting this license restricts the visibility of data for other
                users of the database. Entered data will be visible only to the
                author of the observation, to the taxonomic experts who verify the
                findings and to the website administrators. Data under this license
                will not be shown on the taxon distribution maps, and other users
                will not be able to export this kind of data from the database.
            </p>

            <p class="mb-4">
                By selecting this license authors retain all rights to the data
                they entered. Biologer.org administrators and taxonomic experts
                reserve the right to use data entered under this license in order
                to produce the documents of national importance (Red Book, Red List, etc.),
                as well as for statistical data processing (eg. protection and
                conservation of the taxons and their habitats) but without publicly
                releasing the exact location of the original data. In the case that
                the location of the original data, which is recorded under this license,
                is needed for publishing, administrators of Biologer.org and taxonomic
                experts must do so with the consent of the authors.
            </p>

            <p class="mb-4">
                Due to the limitations in verification of the data uploaded under
                this license and the inability of detailed data display, Biologer.org
                team does not recommend choosing this license!
            </p>
        </section>
    </div>
@endsection
