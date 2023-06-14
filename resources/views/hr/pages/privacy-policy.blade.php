@extends('layouts.main', ['title' => __('navigation.privacy_policy')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Politika privatnosti</h1>

        <section class="section content has-text-justified">
            <h2 class="subtitle is-2">Osobni podaci</h2>

            <p class="mb-4">
                Prema Općoj uredbi o zaštiti podataka (GDPR), koja je stupila na snagu 25. svibnja 2018. godine,
                korisnik daje suglasnost „Biologer zajednici“ da prikuplja njegove osobne podatke: ime, prezime,
                adresu e-pošte, instituciju, lokaciju u vidu geografskih koordinata mjesta gdje je zabilježena
                određena svojta te vrijeme kada je nalaz zabilježen.
            </p>

            <p class="mb-4">
                Navedeni podaci mogu se koristiti samo u svrhe i na načine koji su navedeni u ovom dokumentu i dodatno
                određeni <a href="{{ route('licenses.index') }}" target="_blank">licencama</a> koje je korisnik
                odabrao. Projektni tim Biologera može promijeniti politiku privatnosti pri čemu je dužan obavijestiti
                korisnike. Osobe odgovorne za provođenje Opće uredbe o zaštiti podataka su Administratori Biologer
                Lokalne zajednice:
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
                licence za korištenje podataka o nalazima taksona, koji mogu sadržavati
                i vaše osobne podatke. Odabirom licence korisnik može odrediti što želi dijeliti s drugima
                (pogledati <a href="{{ route('licenses.index') }}" target="_blank">stranicu sa licencama</a>).
                Korisnik može promijeniti licencu za nove podatke koje dostavlja
                Lokalnoj zajednici ili poslati zahtjev odgovornim osobama, Administratorima za promjenu licence već
                postojećih podataka. Biologer podržava otvoreni program i otvorene, znanstveno provjerljive podatke,
                zbog čega ne savjetujemo izbor zatvorene licence. Dobra znanstvena praksa podrazumijeva da podaci o
                nalazima budu provjerljivi pa se uz svaki nalaz prikazuje tko je (ime, prezime i institucija), gdje
                (geografske koordinate ili UTM polje) i kada (datum) zabilježio određenu svojtu. Ako korisnik želi
                ograničiti pristup svojim osobnim podacima osobama izvan uredništva (Administratorima i taksonomskim
                stručnjacima za određenu skupinu), može se odabrati zatvorena licenca.
            </p>

            <p class="mb-4">
                Biologer se obvezuje očuvati privatnost korisnika i poduzima sve tehničke i organizacijske mjere
                za zaštitu osobnih podataka, uz jamstvo da ih neće dijeliti ili prodavati trećoj strani bez
                dopuštenja korisnika.
            </p>

            <p class="mb-4">
                U skladu sa Općom uredbom o zaštiti osobnih podataka, korisnik može, pod određenim uvjetima, koristiti
                svoja zakonska prava za dobivanje potvrde o obradi podataka, uvid u svoje osobne podatke, ispravljanje
                ili dopunu osobnih podataka, traženje isključenja iz daljnje obrade podataka, blokiranje nezakonite
                obrade podataka, traženje brisanja svojih osobnih podataka i korisničkog računa bez negativnih
                posljedica, preuzimanje kopije osobnih podataka u svrhu prijenosa na drugu programsku platformu itd.
                Korisnik može podnijeti zahtjev osobama odgovornim za provedbu Opće uredbe o zaštiti osobnih podataka
                putem e-pošte. Prilikom podnošenja zahtjeva potrebno je navesti točno ime i prezime, instituciju i
                adresu e-pošte pod kojom je korisnik registriran u Lokalnoj zajednici.
            </p>

            <p class="mb-4">
                Biologer web platforma može prikupljati neke podatke o svojim korisnicima dobivene tijekom korištenja,
                u skladu sa zakonom. Ove podatke može koristi za prikaz statistike aktivnosti korisnika, za poboljšanje
                i prilagodbu web stranice i njezinog sadržaja. Kontakt podaci korisnika, osim imena i prezimena,  u
                ovom slučaju neće biti otkriveni.
            </p>

            <p class="mb-4">
                Prikupljenim osobnim podacima korisnika mogu pristupiti programeri i Administratori Lokalne zajednice
                Biologer i osobe angažirane za obradu podataka u svrhe navedene u ovoj politici.
            </p>
        </section>

        <section class="section has-text-justified">
            <h2 class="subtitle is-2">Uvjeti korištenja</h2>

            <p class="mb-4">
                Korisnici su dužni unijeti točne podatke o svom korisničkom računu, koji se prvenstveno odnose na puno
                ime i prezime osobe i adresu e-pošte. To je važno jer želimo da vaši podaci budu povezani s vašim
                računom i provjerljivi u budućnosti.
            </p>

            <p class="mb-4">
                Korisnici su dužni unijeti točne podatke o nalazima vrste s terena, a unošenje lažnih podataka smatra
                se najtežim prekršajem. Pogrešni nalazi o rasprostranjenosti vrste nikome ne idu u prilog i naš
                urednički tim nastoji takve podatke svesti na minimum.
            </p>

            <p class="mb-4">
                Korisnici su dužni poštivati mišljenja drugih i koristiti odgovarajući rječnik primjeren online
                komunikaciji.
            </p>

            <p class="mb-4">
                U slučaju nepoštivanja ovih uvjeta korištenja, ovlaštene osobe mogu upozoriti korisnika ili ga u
                konačnici isključiti iz rada Biologer zajednice. Biologer zadržava pravo korištenja korisničkih
                podataka u slučaju kršenja uvjeta korištenja i nezakonitog korištenja stranice od strane korisnika.
            </p>
        </section>

        <section class="section has-text-justified">
            <h2 class="subtitle is-2">Kolačići</h2>

            <p class="mb-4">
                Biologer koristi kolačiće (eng. „cookies“) za lakše praćenje aktivnosti korisnika. Određene postavke
                pohranjuju se kao kolačići kako bi vam olakšali korištenje stranice pri sljedećim posjetima (npr. radi
                provjere je li korisnik već prijavljen). Kolačići ne narušavaju vašu sigurnost i koriste se samo za
                lakše i sigurnije korištenje stranice.
            </p>
        </section>
    </div>
@endsection
