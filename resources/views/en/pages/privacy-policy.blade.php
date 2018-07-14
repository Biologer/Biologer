@extends('layouts.main', ['title' => __('navigation.privacy_policy')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Privacy Policy</h1>

        <section class="section content has-text-justified">
            <h2 class="subtitle is-2">Personal information</h2>

            <p class="mb-4">
                User’s personal information includes: first name, last name,
                e-mail address, institution and GPS location given as geographical
                coordinates of the place wherea particular taxon has bean recorded.
            </p>

            <p class="mb-4">
                By selecting the default data license the user can chose what to share
                with others. Biologer supports open software and open, scientifically
                verifiable data and we do not recommend users to use closed licenses.
                Good scientific practice must ensure that the all records from the field
                can be verifiable, thus for each observation Biologer shows who
                (name, surname and institution), where (geographic coordinates or UTM square)
                and when (date) certain taxa has bean recorded. To ensure verifiability,
                users are required to correctly enter personal information, while providing
                false information can lead to deletion of the user account. If you
                would like to restrict access of your personal data for people outside
                of the editorial board (administrators and taxonomic experts for a
                particular group), you can choose the closed license.
            </p>

            <p class="mb-4">
                Biologer is committed to maintaining your privacy and will not distribute
                or sell personal information to third parties, except with the permission
                of the user. Biologer reserves the right to use these data in case of
                violations and illegal use of the site by users.
            </p>

            <p class="mb-4">
                Biologer may, in accordance with the law, collect certain data about its
                users obtained during the website usage. This information can be used
                for displaying user activity statistics, and for improving and customizing
                the website and its content. In this case, contact information of the user
                will not be displayed.
            </p>

            <p class="mb-4">
                Collected personal information can be accessed by developers and administrators
                of the Biologer web site, state authorities and persons who engage in processing
                the information on our behalf for the purposes specified in this policy.
            </p>

            <p class="mb-4">
                If you have any questions about Biologer web site or Android app, you can contact
                the administrators at <a href="mailto:admin@biologer.org">admin@biologer.org</a>/
            </p>
        </section>

        <section class="section content has-text-justified">
            <h2 class="subtitle is-2">Cookies</h2>

            <p class="mb-4">
                Biologer uses cookies to facilitate the monitoring of user activity.
                Certain settings are stored as cookies in order to facilitate the use
                of website after the first login (eg. whether the user is already logged in).
                Cookies do not impair your safety and are used only for easier and safer
                use of the website.
            </p>
        </section>
    </div>
@endsection
