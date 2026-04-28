@extends('layouts.main', ['title' => __('navigation.privacy_policy')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Privacy Policy</h1>

        <section class="section content has-text-justified">
            <h2 class="subtitle is-2">Personal information</h2>

            <p class="mb-4">
                According to the General Data Protection Regulation (GDPR), that is active since May 25th 2018, the
                user allows „Biologer Community“ to collect his personal data: first name, last name, e-mail address,
                institution name, location given as geographical coordinates of the place where a particular taxon has
                bean recorded and the time of the record.
            </p>

            <p class="mb-4">
                These data could only be used for the purposes and in a means listed in this document and further
                constrained by the <a href="{{ route('licenses.index') }}" target="_blank">licenses</a> selected
                by the user. Biologer project team can changes this privacy policy, but must inform the users about the
                change. Persons responsible for implementation of the General Data Protection Regulation are the
                Administrators of  the Local Biologer community:
            </p>

            <table>
                <thead>
                    <tr>
                        <th>{{ trans('labels.users.full_name') }}</th>
                        <th>{{ trans('labels.users.institution') }}</th>
                        <th>{{ trans('labels.users.email') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $admin['full_name'] }}</td>
                        <td>{{ $admin['institution'] }}</td>
                        <td>{{ $admin['email'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p class="mb-4">
                <a href="{{ route('pages.about.local-community') }}" target="_blank">Local community</a> defines
                licenses for using data about taxa observation, which could contain your personal data. By selecting
                the default data license the user can chose what to share with others (see the
                <a href="{{ route('licenses.index') }}" target="_blank">licences page</a>). User can change the license
                that will apply to new data delivered to Local Community, or send a request to the Responsible persons
                in order to change the license of existing data. Biologer supports open software and open,
                scientifically verifiable data and we do not recommend users to use closed licenses. Good scientific
                practice must ensure that the all records from the field can be verifiable, thus for each observation
                Biologer shows who (name, surname and institution), where (geographic coordinates or UTM square) and
                when (which date) certain taxa has bean recorded. If a user would like to restrict access to his
                personal data for people outside of the editorial board (administrators and taxonomic experts for a
                particular group), closed license can be chosen.
            </p>

            <p class="mb-4">
                Biologer is committed to maintain privacy of the users and will take all technical and organisational
                measures to protect user’s personal data, with guarantee not to distribute or sell restricted data to
                third parties without permission of the user. In accordance with the General Data Protection
                Regulation, the user can use its legal rights in order to get a confirmation about the data usage,
                get a query of his personal data, correct and amend his personal data, ask to be excluded from further
                data processing, to block illegal data processing, to ask for deletion of his personal data and user
                account without any negative consequences, to take a copy of his personal data for saving them to
                other software platform, etc. The user request can be send to the persons responsible for
                implementation of the General Data Protection Regulation using e-mail address. When submitting the
                request it is required to give your name and surname, institution and the email address of the account
                registered within Local community.
            </p>

            <p class="mb-4">
                Biologer web platform may, in accordance with the law, collect certain data about its users obtained
                during the website usage. This information can be used for displaying user activity statistics, and
                for improving and customizing the website and its content. In this case, contact information, except
                for user name and surname, will not be displayed.
            </p>

            <p class="mb-4">
                Collected personal information can be accessed by developers and administrators of the Biologer Local
                community and persons engaged in processing the information on our behalf for the purposes specified
                in this policy.
            </p>
        </section>

        <section class="section content has-text-justified">
            <h2 class="subtitle is-2">Terms of use</h2>

            <p class="mb-4">
                Users are responsible for entering correct personal informations on their user account, primarly their
                full name, surname and email address. This is important since we would like to connect your field
                observations with you and to ensure verification of these data in the future.
            </p>

            <p class="mb-4">
                Users should enter correct data on the species observations from the field, while forged entries are
                considered stirct offence of our terms of use. Wrong data on species distribution are not good for
                anything and our editorial board is trying to minimise the mumber of such records.
            </p>

            <p class="mb-4">
                Users should respect other person’ opinion and use proper language in online communication.
            </p>

            <p class="mb-4">
                In case of violation of these rulles, responsible persons can warn the user or, in the final case,
                exclude the user from Biologer community. Biologer reserves the right to keep the users data in case
                of violations of the terms of use and illegal use of the site by users.
            </p>
        </section>

        <section class="section content has-text-justified">
            <h2 class="subtitle is-2">Cookies</h2>

            <p class="mb-4">
                Biologer uses cookies to facilitate the monitoring of user activity. Certain settings are stored as
                cookies in order to facilitate the use of website after the first login (eg. whether the user is
                already logged in). Cookies do not impair your safety and are used only for easier and safer use of
                the website.
            </p>
        </section>
    </div>

    <script>
        let elements = document.getElementsByClassName('email');

        for(let i = 0; i < elements.length; i++) {
            var element = elements[i];
            element.onclick = function(e) {
                e.preventDefault();
                // converts back from base64 and launches the link
                window.location = 'mailto:'+atob(e.originalTarget.dataset.target)
            }
        }
    </script>
@endsection
