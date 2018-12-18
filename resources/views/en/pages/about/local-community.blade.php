@extends('layouts.main', ['title' => 'Local community'])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>Local community</h1>

            <p>
                Name of the local community: {{ config('biologer.community.name') }}.<br>
                Country: {{ __(config('biologer.community.country')) }}.<br>
                Address: {{ config('biologer.community.address') }}.
            </p>

            <p class="has-text-justified">
                <b>Administrators</b> are persons that manages the database, have the overview
                in all the data from local Biologer platform and are in charge for entire
                organisation of the Local community. The initial Administration team is
                approved by the Project team during the foundation of new Local community.
                New Administrators are usually chosen from the Editors team, and this
                decision must be approved by 2/3 of the current Administrators.
                Administrators have right to access the data and to decide about data
                usage (in accordance with the licenses chosen by the Users).
            </p>

            <p class="has-text-justified">
                Administrators of the Local community "{{ config('biologer.community.name') }}" are:
            </p>

            <ul>
                @foreach ($admins as $admin)
                    <li>{{ $admin->full_name }}</li>
                @endforeach
            </ul>

            <p class="has-text-justified">
                <b>Editors</b> are taxonomic experts for certain groups of organisms and they
                are in charge of reviewing upcoming data, approving and correcting the
                records or making the record impossible to identify. Editors are experts
                with many years of experience in field work and proved knowledge in certain
                groups of organisms. Editors of the taxonomic groups in each Local community
                are chosen by Administrators from the list of Users. Adding new Editors in
                certain taxonomic group must be accepted by 2/3 of existing Editors and 2/3
                of the Administrators. Editors have right to access the data for the group
                they are in charge and to decide about their usage (in accordance with the
                licenses chosen by the Users).
            </p>

            <p class="has-text-justified">
                Editorial team in Local community "{{ config('biologer.community.name') }}" is made of
                {{ $curators->count() }} persons in charge of {{ $taxonomicGroupsCount }} taxonomic groups:

            </p>

            <ul>
                @foreach ($curators as $curator)
                    <li>{{ $curator->full_name }} - {{ $curator->curatedTaxa->pluck('name')->implode(', ') }}</li>
                @endforeach
            </ul>

            <p class="has-text-justified">
                <b>Users</b> are all the members of Local community, and this status has bean
                given after the registration on the web platform. Editors and Administrators
                are also Users. Users make Biologer community and their devotion is reason
                for gathering valuable data about distribution of taxa in certain geographic area.
            </p>

            <p class="has-text-justified">
                Biologer community "{{ config('biologer.community.name') }}" has {{ $usersCount }} Users,
                that have gathered {{ $observationsCount }} data about our biological diversity.
            </p>
        </div>
    </section>
@endsection
