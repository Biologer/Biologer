@extends('layouts.main', ['title' => 'Organizacije'])

@section('content')
    <section class="section content has-text-justified">
        <div class="container">
            <h1>Organizacije</h1>

            <p>
                Organizacije uključene u Biologer zajednicu iskazale su želju daljeg
                razvoja projekta Biologer i mogu, u skladu sa svojim mogućnostima,
                pomoći realizaciju aktivnosti koje Projektni tim podržava. Organizacije
                nemaju nikakvu pravnu i finansijsku obavezu prema Zajednici. Svaka
                Organizacija može, u saradnji sa Projektnim timom, obezbediti dalja sredstva,
                kadrovsku i tehničku podršku za razvoj Biologer softvera i podsticaj Biologer zajednice.
            </p>

            <p>
                Biologer zajednica je slobodna za pristup organizacijama koje dele
                misiju Biologer zajednice. Zainteresovane organizacije (državne ustanove,
                privatna preduzeća, organizacije civilnog društva itd.) mogu se pridružiti
                projektu, ukoliko iskažu želju da se priključe Biologer zajednici i ukoliko
                dobiju saglasnost 2/3 članova Projektnog tima. Priključivanje novih
                organizacija mora da prati zvanična odluka organizacije u kojoj je iskazana
                jasna želja da se podrži razvoj projekta Biologer i u kojoj su definisani
                načini na koje organizacija može doprineti daljem razvoju ovog projekta.
                Takođe, organizacija se može priključiti dostavljanjem zvaničnog pisma
                podrške u kome je jasno navedena namera podrške projektu Biologer i
                načinima na koji organizacija može doprineti daljem razvoju Biologer zajednice.
                Možete preuzeti <a href="{{ asset('docs/letter-of-support-sr.docx') }}" download="Pismo_podrske_Biologer.docx">nacrt pisma podrške</a>
                i usaglasiti ga sa ciljevima, misijom i vizijom vaše organizacije.
            </p>

            <p>
                Organizacije mogu da delegiraju svog predstavnika u Projektni tim,
                a konačnu odluku o uključenju novih članova u Projektni tim donosi 2/3
                postojećih članovi Projektnog tima.
            </p>

            <p>
                Nakon dobijanja saglasnosti Projektnog tima, partnerske organizacije
                će biti istaknute na ovoj stranici, čime Organizacija zvanično postaju
                deo Biologer zajednice.
            </p>

            <h2>Organizacije koje podržavaju razvoj Biologera</h2>

            <p>Osnivačke organizacije:</p>

            <p>Ostale organizacije:</p>
        </div>
    </section>
@endsection
