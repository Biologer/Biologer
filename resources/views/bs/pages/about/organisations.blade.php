@extends('layouts.main', ['title' => 'Organizacije'])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>Organizacije</h1>

            <p class="has-text-justified">
                Organizacije uključene u Biologer zajednicu iskazale su želju daljeg
                razvoja projekta Biologer i mogu, u skladu sa svojim mogućnostima,
                pomoći realizaciju aktivnosti koje Projektni tim podržava. Organizacije
                nemaju nikakvu pravnu i finansijsku obavezu prema Zajednici. Svaka
                Organizacija može, u saradnji sa Projektnim timom, obezbijediti dalja sredstva,
                kadrovsku i tehničku podršku za razvoj Biologer softvera i podsticaj Biologer zajednice.
            </p>

            <p class="has-text-justified">
                Biologer zajednica je slobodna za pristup organizacijama koje dijele
                misiju Biologer zajednice. Zainteresovane organizacije (državne ustanove,
                privatna preduzeća, organizacije civilnog društva itd.) mogu se pridružiti
                projektu, ukoliko iskažu želju da se priključe Biologer zajednici i ukoliko
                dobiju saglasnost 2/3 članova Projektnog tima. Priključivanje novih
                organizacija mora da prati zvanična odluka organizacije u kojoj je iskazana
                jasna želja da se podrži razvoj projekta Biologer i u kojoj su definisani
                načini na koje organizacija može doprinijeti daljem razvoju ovog projekta.
                Takođe, organizacija se može priključiti dostavljanjem zvaničnog pisma
                podrške u kome je jasno navedena namjera podrške projektu Biologer i
                načinima na koji organizacija može doprineti daljem razvoju Biologer zajednice.
                Možete preuzeti <a href="{{ asset('docs/letter-of-support-sr.docx') }}" download="Pismo_podrske_Biologer.docx">nacrt pisma podrške</a>
                i usaglasiti ga sa ciljevima, misijom i vizijom vaše organizacije.
            </p>

            <p class="has-text-justified">
                Organizacije mogu da delegiraju svog predstavnika u Projektni tim,
                a konačnu odluku o uključenju novih članova u Projektni tim donosi 2/3
                postojećih članovi Projektnog tima.
            </p>

            <p class="has-text-justified">
                Nakon dobijanja saglasnosti Projektnog tima, partnerske organizacije
                će biti istaknute na ovoj stranici, čime Organizacija zvanično postaju
                dio Biologer zajednice.
            </p>

            <h2>Organizacije koje podržavaju razvoj Biologera</h2>

            <p>Osnivačke organizacije:</p>

            <ul>
                <li>Biološki fakultet, Univerzitet u Beogradu (<a href="https://biologer.org/docs/letters-of-support/bioloski-fakultet-bg.pdf" target="_blank">Pismo podrške</a>)</li>
                <li>Udruženje "Hyla" (<a href="https://biologer.org/docs/letters-of-support/hyla.pdf" target="_blank">Pismo podrške</a>)</li>
                <li>Biološko društvo "Dr Sava Petrović" (<a href="https://biologer.org/docs/letters-of-support/bddsp.pdf" target="_blank">Pismo podrške</a>)</li>
            </ul>

            <p>Ostale organizacije:</p>
        </div>
    </section>
@endsection
