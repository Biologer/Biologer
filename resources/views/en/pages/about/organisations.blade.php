@extends('layouts.main', ['title' => 'Organisations'])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>Organisations</h1>

            <p class="has-text-justified">
                Organisations involved in Biologer community have shown their willingness
                for further development of the project Biologer and can, according to
                their possibilities, help in realization of the activities supported
                by the Project team. Organisation have no legal or financial obligation
                towards the Biologer community. Each Organisation can, in cooperation
                with the Project team, provide further resources, personnels and technical
                support for Biologer software development and incentive of Biologer community.
            </p>

            <p class="has-text-justified">
                Biologer community is welcoming organisations that share the mission
                and vision of the Biologer community. Interested organisations (state
                institutions, private enterprises, organisations of the civil society,
                etc.) can join the project it they show a good will to join Biologer
                community and receive support from 2/3 of the members of the Project team.
                Joining new organisations must precedes official decision of the organisation,
                stating their willingness to support development of the project Biologer
                and defining the ways that organisation can contribute to the further
                project development. Also, the organisation can join by sending a support
                letter clearly stating the intentions of their support to the project
                Biologer and the means the organisation can contribute to further
                development of the Biologer community. You can take a
                <a href="{{ asset('docs/letter-of-support-en.docx') }}" download="Biologer_Support_Letter.docx">draft of the supporting letter here</a>
                and change it according to the aims, mission
                and vision of your organisation.
            </p>

            <p class="has-text-justified">
                Organisations can delegate their representatives for the Project team,
                and final decision on the inclusion of new members into the Project
                team must be approved by 2/3 of the current Project team members.
            </p>

            <p class="has-text-justified">
                After getting the consent from the Project team, partner organisations
                will be displayed on this page, which means that the Organisation
                officially become members of the Biologer community.
            </p>

            <h2>Organisations that support Biologer development</h2>

            <p>The founding organisations:</p>

            <ul>
                <li>Faculty of Biology, University of Belgrade (<a href="https://biologer.org/docs/letters-of-support/bioloski-fakultet-bg.pdf" target="_blank">Letter of Support</a>)</li>
                <li>Association Hyla (<a href="https://biologer.org/docs/letters-of-support/hyla-en.pdf" target="_blank">Letter of Support</a>)</li>
                <li>Biological Society "Dr Sava Petrović" (<a href="https://biologer.org/docs/letters-of-support/bddsp.pdf" target="_blank">Letter of Support</a>)</li>
            </ul>

            <p>Other organisations:</p>
        </div>
    </section>
@endsection
