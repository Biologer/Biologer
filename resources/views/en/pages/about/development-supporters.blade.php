@extends('layouts.main', ['title' => 'Development supported'])

@section('content')
<section class="section content">
    <div class="container">
        <h1>Development supported</h1>

        <h2>Organisations and foundations that financially supported the development of Biologer</h2>

        <img src="https://www.rufford.org/sites/all/themes/rufford/img/rufford.jpg" alt="Rufford" class="image mx-auto mb-4">

        <p class="has-text-justified">
            The development was initiated with financial support from
            <a href="https://www.rufford.org/">Rufford Small Grants foundation</a>,
            through projects No. <a href="https://www.rufford.org/projects/ana_golubovi%C4%87_0">20507-B</a>
            and <a href="https://www.rufford.org/projects/milo%C5%A1_popovi%C4%87_0">24652-B</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/mava-foundation.jpg') }}" alt="Mava Foundation" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/udruga-hyla.jpg') }}" alt="Association Hyla" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/biolosko-drustvo-sava-petrovic.png') }}" alt='Biological Society "Dr Sava Petrović"' class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Further development of the application is financially supported by
            the <a href="http://mava-foundation.org/">MAVA Fundation</a> through the project
            <a href="http://www.hhdhyla.hr/projektii/aktualni-projekti">"The Dinaric Arc Karst biodiversity conservation programme"</a>,
            lead by the <a href="http://www.hhdhyla.hr/">Association Hyla</a>.
            The software development for this project is realised through the
            <a href="http://bddsp.org.rs/">Biological society "Dr Sava Petrović"</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/ministarstvo-prosvete-nauke-i-tehnoloskog-razvoja-srbija.png') }}" alt="Ministry of Education, Science and Technological Development of Republic of Serbia" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/pmf-nis.jpg') }}" alt="Faculty of Sciences, University of Niš" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/bioloski-fakultet-beograd.png') }}" alt="Faculty of Biology, University of Belgrade" class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            The work of Miloš Popović is supported by the founds from the
            Ministry of Education, Science and Technological Development of
            Republic of Serbia, project No.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/305-173025-evolucija-u-heterogenim-sredinama-mehanizmi-adaptacija-biomonitoring-i-konzervacija-biodiverziteta">173025</a>,
            through the Department of Biology and Ecology, Faculty of Sciences, University of Niš.
        </p>

        <p class="has-text-justified">
            The work of Ana Golubović is supported by the founds from the
            Ministry of Education, Science and Technological Development of
            Republic of Serbia, project No.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/308-173043-diverzitet-vodozamaca-i-gmizavaca-balkana-evolucioni-aspekti-i-konzervacija">173043</a>,
            through the Faculty of Biology, University of Belgrade.
        </p>

        <h2>Individuals that contributed to the development of Biologer project</h2>

        <p>
            Miloš Popović – Project leader, organisation of the portal and data, initial graphic design, development of Android application.<br>
            Ana Golubović – Organisation of the portal.<br>
            Nenad Živanović – Overall software solution for Biologer platform, web design, organisation of the portal and data.<br>
            Marko Nikolić – Legislature of Biologer community.<br>
            Branko Jovanović – Development of Android application.<br>
            Vanja Lazić – Design of icons for animal groups.<br>
            Jožef Dožai - Added icons for plants and fungi.<br>
            Boris Bradarić - Improvements to Android application support for older devices.
        </p>
    </div>
</section>
@endsection
