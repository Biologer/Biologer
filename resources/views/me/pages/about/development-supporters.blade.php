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
                <img src="{{ asset('img/organisations/udruga-hyla.jpg') }}" alt="Udruženje Hyla" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/biolosko-drustvo-sava-petrovic.png') }}" alt='Biološko društvo "Dr Sava Petrović"' class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Dalji razvoj aplikacije podržan je sredstvima
            <a href="http://mava-foundation.org/">Fondacije MAVA</a> preko
            <a href="http://www.hhdhyla.hr/projektii/aktualni-projekti">„Projekta zaštite biodiverziteta na krečnjacima Dinarskog masiva“</a>,
            koji realizuje <a href="http://www.hhdhyla.hr/">Udruženje Hyla</a>.
            Razvoj softvera za potrebe projekta sprovodi
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
            nauke i tehnološkog razvoja Republike Srbije, projekat br.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/305-173025-evolucija-u-heterogenim-sredinama-mehanizmi-adaptacija-biomonitoring-i-konzervacija-biodiverziteta">173025</a>,
            preko Departmana za biologiju i ekologiju, Prirodno-matematičkog fakulteta, Univerziteta u Nišu.
        </p>

        <p class="has-text-justified">
            Rad Ane Golubović podržan je sredstvima Ministarstva prosvete,
            nauke i tehnološkog razvoja Republike Srbije, projekat br.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/308-173043-diverzitet-vodozamaca-i-gmizavaca-balkana-evolucioni-aspekti-i-konzervacija">173043</a>,
            preko Biološkog fakulteta, Univerziteta u Beogradu.
        </p>

        <h2>Pojedinci koji su doprinijeli razvoju projekta Biologer</h2>

        <p>
            Miloš Popović – Vođa projekta, organizacija portala i podataka, početni grafički dizajn, razvoj Android aplikacije.<br>
            Ana Golubović – Organizacija portala.<br>
            Nenad Živanović – Softversko rješenje za Biologer platformu, veb dizajn, organizacija portala i podataka.<br>
            Marko Nikolić – Legislativa Biologer zajednice.<br>
            Branko Jovanović – Razvoj Android aplikacije.<br>
            Vanja Lazić – Dizajn ikonica za životinjske grupe.<br>
            Jožef Dožai - Dopuna ikonica za biljke i gljive.<br>
            Boris Bradarić - Poboljšanje rada Android aplikacije na starijim uređajima.
        </p>
    </div>
</section>
@endsection
