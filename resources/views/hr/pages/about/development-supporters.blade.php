@extends('layouts.main', ['title' => 'Razvoj podržali'])

@section('content')
<section class="section content">
    <div class="container">
        <h1>Razvoj podržali</h1>

        <h2>Organizacije i fondacije koje su finansijski podržale razvoj projekta Biologer</h2>

        <img src="https://www.rufford.org/sites/all/themes/rufford/img/rufford.jpg" alt="Rufford" class="image mx-auto mb-4">

        <p class="has-text-justified">
            Razvoj aplikacije je pokrenut sredstvima fondacije
            <a href="https://www.rufford.org/">Raford, mali grantovi</a>,
            preko projekta br. <a href="https://www.rufford.org/projects/ana_golubovi%C4%87_0">20507-B</a>
            i <a href="https://www.rufford.org/projects/milo%C5%A1_popovi%C4%87_0">24652-B</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/mava-foundation.jpg') }}" alt="MAVA Fondacija" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/udruga-hyla.jpg') }}" alt="Udruga Hyla" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/biolosko-drustvo-sava-petrovic.png') }}" alt='Biološko društvo "Dr Sava Petrović"' class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Daljnji razvoj aplikacije podržan je sredstvima fondacije
            <a href="http://mava-foundation.org/">MAVA</a> preko
            <a href="http://www.hhdhyla.hr/projektii/aktualni-projekti">„KARST – The Dinaric Arc Karst biodiversity conservation programme“</a>,
            koji realizuje udruga <a href="http://www.hhdhyla.hr/">Hyla</a>.
            Razvoj softvera za potrebe projekta provodi
            <a href="http://bddsp.org.rs/">Biološko društvo „Dr Sava Petrović“</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/ministarstvo-prosvete-nauke-i-tehnoloskog-razvoja-srbija.png') }}" alt="Ministarstvo prosvete, nauke i tehnološkog razvoja Republike Srbije" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/pmf-nis.jpg') }}" alt="Prirodno-matematički fakultet, Univerzitet u Nišu" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/bioloski-fakultet-beograd.png') }}" alt="Biološki fakultet, Univerzitet u Beogradu" class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Rad Miloša Popovića podržan je sredstvima Ministarstva prosvete,
            nauke i tehnološkog razvoja Republike Srbije, projekti br.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/305-173025-evolucija-u-heterogenim-sredinama-mehanizmi-adaptacija-biomonitoring-i-konzervacija-biodiverziteta">173025</a>,
            451-03-68/2020-14/200124 i 451-03-9/2021-14/200124,
            preko Departmana za biologiju i ekologiju, Prirodno-matematičkog fakulteta, Univerziteta u Nišu.
        </p>

        <p class="has-text-justified">
            Rad Ane Golubović podržan je sredstvima Ministarstva prosvete,
            nauke i tehnološkog razvoja Republike Srbije, projekat br.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/308-173043-diverzitet-vodozamaca-i-gmizavaca-balkana-evolucioni-aspekti-i-konzervacija">173043</a>,
            preko Biološkog fakulteta, Univerziteta u Beogradu.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/fond-za-zastitu-okolisa-federacije-bih.jpg') }}" alt="Fond za zaštitu okoliša Federaciјe BiH" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/udruzenje-biolog.jpg') }}" alt="Udruga BIO.LOG" class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Biologer zaјednica u Bosni i Hercegovini i razvoј iOS aplikaciјe su podržani od strane
            <a href="https://fzofbih.org.ba">Fonda za zaštitu okoliša Federaciјe BiH</a>
            kroz proјekt „Biologer - onlaјn baza podataka o biodiverzitetu BiH“,
            realizovan preko udruge <a href="https://www.biolog.ba/">BIO.LOG</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/eko-sistem.png') }}" alt="EKO-SISTEM" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/mladi-istrazivaci-srbije.png') }}" alt="Mladi istraživači Srbije" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/svedska.png') }}" alt="Švedska" class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Razvoј čitave softverske platforme јe nastavljen u okviru proјekta
            „Eko mreža za budućnost Srbiјe“, kao deo aktivnosti programa
            <a href="https://ekosistem.mis.org.rs/">EKO-SISTEM</a>
            koјi realizuјu <a href="https://www.mis.org.rs">Mladi istraživači Srbiјe</a>,
            a podržava <a href="https://www.swedenabroad.se/en/">Švedska</a>.
        </p>

        <h2>Pojedinci koji su doprineli razvoju projekta Biologer</h2>

        <p>
            Miloš Popović – Voditelj projekta, organizacija portala i podataka, početni grafički dizajn, razvoj Android aplikacije.<br>
            Ana Golubović – Organizacija portala.<br>
            Nenad Živanović – Softversko rješenje za Biologer platformu, veb dizajn, organizacija portala i podataka.<br>
            Marko Nikolić – Legislativa Biologer zajednice.<br>
            Branko Jovanović – Razvoj Android aplikacije.<br>
            Vanja Lazić – Dizajn ikonica za životinjske grupe.<br>
            Jožef Dožai - Dopuna ikonica za biljke i gljive.<br>
            Boris Bradarić - Poboljšanje rada Android aplikacije na starijim uređajima.<br>
            Nikola Popović – Razvoj iOS aplikacije.<br>
            Nikola Vasić – Razboj taksonomske baze i BirdLoger-a.
        </p>
    </div>
</section>
@endsection
