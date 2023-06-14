@extends('layouts.main', ['title' => __('navigation.privacy_policy')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Politika privatnosti</h1>

        <section class="section has-text-justified">
            <h2 class="subtitle is-2">Lični podaci</h2>

            <p class="mb-4">
                Podaci o korisniku predstavljaju: ime, prezime, adresu e-pošte,
                instituciju i GPS lokaciju u vidu geografskih koordinata mesta
                gdje je zabilježen određeni takson.
            </p>

            <p class="mb-4">
                Izborom licence za korišćenje podataka korisnik može da odredi šta
                želi da deli sa drugima. Biologer podržava otvoren softver i otvorene,
                naučno proverljive podatke, zbog čega ne savjetujemo izbor zatvorene
                licence. Dobra naučna praksa podrazumeva da podaci o nalazima budu
                provjerljivi, pa se uz svaki nalaz prikazuje ko je (ime, prezime i
                institucija), gdje (geografske koordinate ili UTM polje) i kada (datum)
                zabeležio određeni takson. Kako bi obezbjedili proverljivost, korisnici
                su dužni da tačno unesu svoje podatke, a unos lažnih podataka može
                dovesti do brisanja korisničkog naloga. Ukoliko ne želite da osobe
                izvan uredništva sajta (administratora i taksonomskih eksperata za
                određenu grupu) vide vaše lične podatke, možete odabrati zatvorenu
                licencu za podatke.
            </p>

            <p class="mb-4">
                Biologer se zalaže za očuvanje vaše privatnosti i neće distribuirati
                ni prodavati privatne podatke trećoj strani, osim uz dozvolu korisnika.
                Biologer zadržava pravo korišćenja ovih podataka u slučaju kršenja
                pravila sajta i nezakonitog korišćenja od strane korisnika.
            </p>

            <p class="mb-4">
                Biologer može, u skladu sa zakonom, prikupljati neke podatke o svojim
                korisnicima koji su dobijeni tokom korišćenja. Ove podatke Biologer
                može koristiti za prikazivanje statistike aktivnosti korisnika, za
                poboljšanje i prilagođavanje sajta i njegovog sadržaja. Kontakt podaci
                korisnika se u ovom slučaju, takođe, neće otkrivati.
            </p>

            <p class="mb-4">
                Prikupljenim ličnim podacima korisnika mogu pristupiti programeri i
                administratori sajta Biologer, državni organi i lica koja angažujemo
                da obrađuju podatke u naše ime u svrhe navedene u ovoj politici.
            </p>

            <p class="mb-4">
                Ako imate bilo kakva pitanja o Biologer sajtu ili Android aplikaciji,
                možete kontaktirati administratore na <a href="mailto:admin@biologer.org">admin@biologer.org</a>.
            </p>
        </section>

        <section class="section has-text-justified">
            <h2 class="subtitle is-2">Kolačići</h2>

            <p class="mb-4">
                Biologer koristi kolačiće (eng. „cookies“) za lakše praćenje aktivnosti
                korisnika. Određena podešavanja se čuvaju kao kolačići kako bi vam
                olakšali korišćenje sajta prilikom narednih poseta (npr. radi provere
                da li je korisnik već prijavljen). Kolačići ne narušavaju vašu bezbednost
                i koriste se samo radi lakšeg i sigurnijeg korišćenja sajta.
            </p>
        </section>
    </div>
@endsection
