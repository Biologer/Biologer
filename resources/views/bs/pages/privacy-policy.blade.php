@extends('layouts.main', ['title' => __('navigation.privacy_policy')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Politika privatnosti</h1>

        <section class="section content has-text-justified">
            <h2 class="subtitle is-2">Lični podaci</h2>

            <p class="mb-4">
                Prema Opštoj uredbi o zaštiti podataka (GDPR), koja je stupila na snagu 25. maja 2018. godine,
                korisnik daje saglasnost „Biologer zajednici“ da prikuplja njegove lične podatke: ime, prezime,
                adresu e-pošte, naziv institucije, lokaciju u vidu geografskih koordinata mesta gde je zabeležen
                određeni takson i vremena kada je nalaz zabeležen. Biologer se zalaže za očuvanje privatnosti
                korisnika i preduzima sve tehničke i organizacione mere za zaštitu ličnih podataka, uz garanciju da
                ih neće distribuirati ni prodavati trećoj strani, osim uz dozvolu korisnika.
            </p>

            <p class="mb-4">
                Navedeni podaci mogu se koristiti samo u svrhe i na načine koji su navedeni u ovom dokumentu i bliže
                određeni <a href="{{ route('licenses.index') }}" target="_blank">licencama</a> koje korisnik izabere.
                Biologerov projektni tim može izmeniti politiku privatnosti
                korisnika, pri čemu je dužan da o tome obavesti svoje korisnike. Osobe koje su odgovorne za sprovođenje
                Opšte uredbe o zaštiti podataka su Administratori stranice Lokalne biologer zajednice:
            </p>

            <table>
                <thead>
                <tr>
                    <th>{{ trans('labels.users.full_name') }}</th>
                    <th>{{ trans('labels.users.institution') }}</th>
                    <th>{{ trans('labels.users.email') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $admin['full_name'] }}</td>
                        <td>{{ $admin['institution'] }}</td>
                        <td>{{ $admin['email'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p class="mb-4">
                <a href="{{ route('pages.about.local-community') }}" target="_blank">Lokalna zajednica</a> određuje
                licence za korišćenje podataka o nalazima taksona, koji mogu sadržati i vaše lične podatke. Izborom
                licence korisnik može da odredi šta želi da deli sa drugima (pogledajte
                <a href="{{ route('licenses.index') }}" target="_blank">stranicu sa licencama</a>). Korisnik može
                promeniti licencu za nove podatke koje dostavlja Lokalnoj zajednici, ili poslati zahtev za promenu
                licence već postojećih podataka Odgovornim osobama. Biologer podržava otvoren softver i otvorene,
                naučno proverljive podatke, zbog čega ne savetujemo izbor zatvorene licence. Dobra naučna praksa
                podrazumeva da podaci o nalazima budu proverljivi, pa se uz svaki nalaz prikazuje ko je (ime, prezime
                i institucija), gde (geografske koordinate ili UTM polje) i kada (kog datuma) zabeležio određeni
                takson. Pored toga, korisnicima je omogućen izbor zatvorenih licenci, čime se podaci o nalazima i
                lični podaci korisnika mogu sakriti (ali će biti dostupni administratorima i taksonomskim urednicima
                određene grupe).
            </p>

            <p class="mb-4">
                Biologer se zalaže za očuvanje privatnosti korisnika i preduzeće sve tehničke i organizacione mere da
                zaštiti lične podatke korisnika, uz garanciju da neće deliti ili prodavati sakrivene podatke
                korisnika trećoj strani bez dozvole korisnika. U skladu sa Opštom uredbom o zaštiti ličnih podataka,
                korisnik može koristiti svoja zakonska prava da dobije potvrdu o obradi podataka, izvrši uvid u svoje
                lične podatke, ispravi ili dopuni lične podatke, da se protivi daljoj, ili prekomernoj obradi
                podataka, da blokira nezakonitu obradu podataka, da zatraži brisanje svojih ličnih podataka i
                korisničkog naloga bez bilo kakvih negativnih posledica, da preuzme kopiju ličnih podataka u cilju
                prenosa na drugu softversku platformu i dr. Korisnik može podneti zahtev osobama Odgovornim za
                sprovođenje Opšte uredbe o zaštiti ličnih podataka, putem e-pošte. Prilikom podnošenja zahteva
                neophodno je navesti tačno ime i prezime, instituciju i adresu e-pošte pod kojom je korisnik
                registorvan u Lokalnoj zajednici.
            </p>

            <p class="mb-4">
                Biologer veb platforma može prikupljati neke podatke o svojim korisnicima koji su dobijeni tokom
                korišćenja, u skladu sa zakonom. Ove podatke Biologer može koristi za prikazivanje statistike
                aktivnosti korisnika, za poboljšanje i prilagođavanje sajta i njegovog sadržaja. Kontakt podaci
                korisnika, osim imena i prezimena, se u ovom slučaju neće otkrivati.
            </p>

            <p class="mb-4">
                Prikupljenim ličnim podacima korisnika mogu pristupiti Biologerovi programeri i Administratori Lokalne
                zajednice i lica koja budu angažovana da obrađuju podatke u svrhe navedene u ovoj politici.
            </p>
        </section>

        <section class="section has-text-justified">
            <h2 class="subtitle is-2">Uslovi korišćenja</h2>

            <p class="mb-4">
                Korisnici su dužni da unesu tačne podatke o svom korisničkom nalogu, što se pre svega odnosi na puno
                ime i prezime osobe i adresu e-pošte. Ovo je važno, zato što želimo da vaši podaci budu povezani sa
                vašim nalogom i proverljivi u budućnosti.
            </p>

            <p class="mb-4">
                Korisnici su dužni da unose tačne podatke o nalazima vrste sa terene, a unos lažnih podataka smatramo
                najstrožim prekršajem. Pogrešni nalazi o rasprostranjenju vrsta nikome ne idu u prilog i naš urednički
                tim se trudi da takve podatke svede na minimum.
            </p>

            <p class="mb-4">
                Korisnici su dužni da uvažavaju mišljenja drugih i da koriste primeren rečnik.
            </p>

            <p class="mb-4">
                U slučaju da se ne poštuju ovi uslovi korišćenja, ovlašćene osobe mogu opomenuti korisnika ili ga, u
                krajnjem slučaju, isključiti iz rada Biologer zajednice. Biologer zadržava pravo korišćenja podataka
                korisnika u slučaju kršenja uslova korišćenja sajta i nezakonitog korišćenja od strane korisnika.
            </p>
        </section>

        <section class="section has-text-justified">
            <h2 class="subtitle is-2">Kolačići</h2>

            <p class="mb-4">
                Biologer koristi kolačiće (eng. „cookies“) za lakše praćenje aktivnosti korisnika. Određena
                podešavanja se čuvaju kao kolačići kako bi vam olakšali korišćenje sajta prilikom narednih poseta
                (npr. radi provere da li je korisnik već prijavljen). Kolačići ne narušavaju vašu bezbednosti i
                koriste se samo radi lakšeg i sigurnijeg korišćenja sajta.
            </p>
        </section>
    </div>
@endsection
