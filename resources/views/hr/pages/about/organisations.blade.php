@extends('layouts.main', ['title' => 'Organizacije'])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>Organizacije</h1>

            <p class="has-text-justified">
                Organizacije uključene u Biologer zajednicu iskazale su želju daljnjeg
                razvoja projekta Biologer i mogu, u skladu sa svojim mogućnostima,
                pomoći u realizaciji aktivnosti koje Projektni tim podržava. Organizacije
                nemaju nikakvu pravnu i financijsku obavezu prema Zajednici. Svaka
                Organizacija može, u suradnji sa Projektnim timom, osigurati daljnja sredstva,
                kadrovsku i tehničku podršku za razvoj Biologer softvera i poticaj Biologer zajednice.
            </p>

            <p class="has-text-justified">
                Biologer zajednica je slobodna za pristup organizacijama koje dijele
                misiju Biologer zajednice. Zainteresirane organizacije (državne ustanove,
                privatne tvrtke, organizacije civilnog društva itd.) mogu se pridružiti
                projektu, ukoliko iskažu želju da se priključe Biologer zajednici i ukoliko
                dobiju suglasnost 2/3 članova Projektnog tima. Priključivanje novih
                organizacija mora pratiti službena odluka organizacije u kojoj je iskazana
                jasna želja da se podrži razvoj projekta Biologer i u kojoj su definirani
                načini na koje organizacija može doprinijeti daljnjem razvoju ovog projekta.
                Također, organizacija se može priključiti dostavljanjem službenog pisma
                podrške u kojem je jasno navedena namjera podrške projektu Biologer i
                načinima na koje organizacija može doprinijeti daljnjem razvoju Biologer
                zajednice. Možete preuzeti  <a href="{{ asset('docs/letter-of-support-sr.docx') }}" download="Biologer_Support_Letter.docx">nacrt pisma podrške</a>
                i uskladiti ga s ciljevima, misijom i vizijom vaše organizacije.
            </p>

            <p class="has-text-justified">
                Organizacije mogu delegirati svog predstavnika u Projektni tim,
                a konačnu odluku o uključenju novih članova u Projektni tim donosi 2/3
                postojećih članova Projektnog tima.
            </p>

            <p class="has-text-justified">
                Nakon dobivanja suglasnosti Projektnog tima, partnerske organizacije
                će biti istaknute na ovoj stranici, čime Organizacije službeno postaju
                dio Biologer zajednice.
            </p>

            <h2>Organizacije koje podržavaju razvoj Biologera</h2>

            <p>Osnivačke organizacije:</p>

            <ul>
                <li>Biološki fakultet, Univerzitet u Beogradu (<a href="https://biologer.org/docs/letters-of-support/bioloski-fakultet-bg.pdf" target="_blank">Pismo podrške</a>)</li>
                <li>Udruga "Hyla" (<a href="https://biologer.org/docs/letters-of-support/hyla.pdf" target="_blank">Pismo podrške</a>)</li>
                <li>Biološko društvo "Dr Sava Petrović" (<a href="https://biologer.org/docs/letters-of-support/bddsp.pdf" target="_blank">Pismo podrške</a>)</li>
            </ul>

            <p>Ostale organizacije:</p>
        </div>
    </section>
@endsection
