@extends('layouts.main', ['title' => 'Development supported'])

@section('content')
    <section class="section content has-text-justified">
        <div class="container">
            <h1>Development supported</h1>

            <h2>Organisations and foundations that financially supported the development of Biologer</h2>

            <img src="https://www.rufford.org/sites/all/themes/rufford/img/rufford.jpg" alt="Rufford" class="image mx-auto mb-4">

            <p>
                The development was initiated with financial support from
                <a href="https://www.rufford.org/">Rufford Small Grants foundation</a>,
                through projects No. <a href="https://www.rufford.org/projects/ana_golubovi%C4%87_0">20507-B</a>
                and <a href="https://www.rufford.org/projects/milo%C5%A1_popovi%C4%87_0">24652-B</a>.
            </p>

            <div class="columns mb-4">
                <div class="column flex is-flex-center">
                    <img src="{{ asset('img/organizations/mava-foundation.jpg') }}" alt="Mava Foundation" class="image mx-auto" style="max-height: 300px">
                </div>
                <div class="column flex is-flex-center">
                    <img src="{{ asset('img/organizations/udruga-hyla.jpg') }}" alt="Association Hyla" class="image mx-auto" style="max-height: 300px">
                </div>
                <div class="column flex is-flex-center">
                    <img src="{{ asset('img/organizations/biolosko-drustvo-sava-petrovic.png') }}" alt='Biological Society "Dr Sava Petrović"' class="image mx-auto" style="max-height: 300px">
                </div>
            </div>

            <p>
                Further development of the application is financially supported by
                the <a href="http://mava-foundation.org/">MAVA</a> fundation through the project
                <a href="http://www.hhdhyla.hr/projektii/aktualni-projekti">"The Dinaric Arc Karst biodiversity conservation programme"</a>,
                lead by the organisation <a href="http://www.hhdhyla.hr/">Hyla</a>.
                The software development for this project is realised through the
                <a href="http://bddsp.org.rs/">Biological society "Dr Sava Petrović"</a>.
            </p>

            <div class="columns mb-4">
                <div class="column flex is-flex-center">
                    <img
                        src="{{ asset('img/organizations/ministarstvo-prosvete-nauke-i-tehnoloskog-razvoja-srbija.png') }}"
                        alt="Ministry of Education, Science and Technological Development of Republic of Serbia"
                        class="image mx-auto"
                        style="max-height: 300px"
                    >
                </div>
                <div class="column flex is-flex-center">
                    <img
                        src="{{ asset('img/organizations/pmf-nis.jpg') }}"
                        alt="Faculty of Sciences, University of Niš"
                        class="image mx-auto"
                        style="max-height: 300px"
                    >
                </div>
                <div class="column flex is-flex-center">
                    <img
                        src="{{ asset('img/organizations/bioloski-fakultet-beograd.png') }}"
                        alt="Faculty of Biology, University of Belgrade"
                        class="image mx-auto"
                        style="max-height: 300px"
                    >
                </div>
            </div>

            <p>
                The work of Miloš Popović is supported by the founds from the
                Ministry of Education, Science and Technological Development of
                Republic of Serbia, project No.
                <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/305-173025-evolucija-u-heterogenim-sredinama-mehanizmi-adaptacija-biomonitoring-i-konzervacija-biodiverziteta">173025</a>,
                through the Department of Biology and Ecology, Faculty of Sciences, University of Niš.
            </p>

            <p>
                The work of Ana Golubović is supported by the founds from the
                Ministry of Education, Science and Technological Development of
                Republic of Serbia, project No.
                <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/308-173043-diverzitet-vodozamaca-i-gmizavaca-balkana-evolucioni-aspekti-i-konzervacija">173043</a>,
                through the Faculty of Biology, University of Belgrade.
            </p>

            <h2>Individuals that contributed to the development of Biologer project</h2>

            <p>
                Miloš Popović – Project leader, organisation of the portal and data, graphic design.<br>
                Ana Golubović – Organisation of the portal.<br>
                Nenad Živanović – Software solution for Biologer platform and web design.<br>
                Marko Nikolić – Legislature of Biologer community.<br>
                Branko Jovanović – Development of Android application.
            </p>
        </div>
    </section>
@endsection
