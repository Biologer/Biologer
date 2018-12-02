@extends('layouts.main', ['title' => 'Razvoj podržali'])

@section('content')
    <section class="section content has-text-justified">
        <div class="container">
            <h1>Razvoj podržali</h1>

            <h2>Organizacije i fondacije koje su finansijski podržale razvoj projekta Biologer</h2>

            <img src="https://www.rufford.org/sites/all/themes/rufford/img/rufford.jpg" alt="Rufford" class="image mx-auto mb-4">

            <p>
                Razvoj aplikacije je pokrenut sredstvima fondacije
                <a href="https://www.rufford.org/">Raford, mali grantovi</a>,
                preko projekta br. <a href="https://www.rufford.org/projects/ana_golubovi%C4%87_0">20507-B</a>
                i <a href="https://www.rufford.org/projects/milo%C5%A1_popovi%C4%87_0">24652-B</a>.
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
                Dalji razvoj aplikacije podržan je sredstvima fondacije
                <a href="http://mava-foundation.org/">MAVA</a> preko
                <a href="http://www.hhdhyla.hr/projektii/aktualni-projekti">„Projekta zaštite biodiverziteta na krečnjacima Dinarskog masiva“</a>,
                koji realizuje udruženje <a href="http://www.hhdhyla.hr/">Hila</a>.
                Razvoj softvera za potrebe projekta sprovodi
                <a href="http://bddsp.org.rs/">Biološko društvo „Dr Sava Petrović“</a>.
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
                Rad Miloša Popovića podržan je sredstvima Ministarstva prosvete,
                nauke i tehnološkog razvoja Republike Srbije, projekat br.
                <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/305-173025-evolucija-u-heterogenim-sredinama-mehanizmi-adaptacija-biomonitoring-i-konzervacija-biodiverziteta">173025</a>,
                preko Departmana za biologiju i ekologiju, Prirodno-matematičkog fakulteta, Univerziteta u Nišu.
            </p>

            <p>
                Rad Ane Golubović podržan je sredstvima Ministarstva prosvete,
                nauke i tehnološkog razvoja Republike Srbije, projekat br.
                <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/308-173043-diverzitet-vodozamaca-i-gmizavaca-balkana-evolucioni-aspekti-i-konzervacija">173043</a>,
                preko Biološkog fakulteta, Univerziteta u Beogradu.
            </p>

            <h2>Pojedinci koji su doprineli razvoju projekta Biologer</h2>

            <p>
                Miloš Popović – Vođa projekta, organizacija portala i podataka, grafički dizajn.<br>
                Ana Golubović – Organizacija portala.<br>
                Nenad Živanović – Softversko rešenje za Biologer platformu.<br>
                Marko Nikolić – Legislativa Biologer zajednice.<br>
                Branko Jovanović – Razvoj Android aplikacije.
            </p>
        </div>
    </section>
@endsection
