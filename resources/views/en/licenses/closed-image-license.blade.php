@extends('layouts.main', ['title' => __('navigation.closed_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Closed License</h1>
        <p class="subtitle is-2 has-text-centered">License for images with restricted access</p>

        <section class="section content has-text-justified">
            <p class="mb-4">
                This license specifies the terms and conditions for usage of images
                from Biologer database for users who don’t want to share their images with others.
                The images will be hidden and will not be shown in the public part of the web platform at all.
            </p>

            <p class="mb-4">
                The use of photographs with restricted access is limited to the
                Administrators, taxonomic experts and the authors, that will use
                images just for identification of the taxa from the photographs.
                The display and use of such photographs out of closed part of the Biologer platform is forbidden.
            </p>
        </section>
    </div>
@endsection
