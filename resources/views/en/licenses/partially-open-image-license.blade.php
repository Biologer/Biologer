@extends('layouts.main', ['title' => __('navigation.partially_open_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Copyrighted</h1>
        <p class="subtitle is-2 has-text-centered">License for images with restricted rights</p>

        <section class="section has-text-justified">
            <p class="mb-4">
                This license specifies the terms and conditions for usage of images
                from Biologer database for users who want to keep their full authorship.
                The images will be shown in public part of the web platform using watermark,
                while authors will retain all the rights for using such photographs.
            </p>

            <p class="mb-4">
                The use of photographs with restricted rights fully depends on the author
                and such images could not be shared out of Biologer platform without author’s consent.
                Local Biologer communities restrict right to share such images for promotion
                (such as sharing information on interesting species observations on social network),
                but must clearly state the authorship and keep the watermark on the image.
            </p>
        </section>
    </div>
@endsection
