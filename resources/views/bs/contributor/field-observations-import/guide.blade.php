@extends('layouts.dashboard', ['title' => __('navigation.field_observations_import')])

@section('content')
    <div class="box content">
        <h1>Uputstvo za uvoz terenskih podataka</h1>

        <div class="message mt-8">
            <div class="message-body">
                Uvoz podataka iz tabele u Biologer je kompleksan proces. Tom prilikom
                mogu da se potkradu greške koje nije jednostavno ispraviti. Unos podataka
                na ovaj način je opravdano ukoliko se radi o velikom setu podataka, koji nije
                jednostavno prekucati unutar web okruženja Biologera. Ipak, prije nego što se
                odlučite za uvoz podataka iz tabele razmislite o drugim mogućnostima i dobro
                proučite ovo uputstvo kako bi izbjegli neželjene komplikacije.
            </div>
        </div>

        <h2>Format tabele</h2>

        <p>
            Biologer radi sa tabelama koje su sačuvane u „.CSV“ formatu (tekstualna datoteka
            u kojoj su kolone razdvojene zarezom, a svaki red teksta je zaseban red u tabeli).
            Bilo koju tabelu možete sačuvati kao CSV datoteku, pošto većina programa za rad sa tabelama,
            statističkih paketa i GIS alata koristi ovaj format. Primjera radi iz programa LibreOffice
            dovoljno je da odete u meni Datoteka → Sačuvaj kao i izaberete „Text CSV (.csv)“. Takođe
            možete jednostavno naznačiti ekstenziju na kraju naziva datoteke (npr. „za_uvoz.csv“) i
            LibreOffice će automatski shvatiti kako želite da sačuvate tabelu.
        </p>

        <h2>Sadržaj tabele</h2>

        <p>
            Prije nego što uvezete podatke iz tabele morate pravilno formatirati njena
            polja kako bi Biologer prepoznao podatke. Prilikom uvoza podataka Biologer
            čita vrijednosti iz redova i kolona tabele, sprovodi osnovnu provjeru podataka
            i smješta podatke u odgovarajuća polja unutar baze podataka. Ukoliko ne formatirate
            polje kako treba, Biologer će vam prijaviti grešku i ukazati u kom redu tabele
            se greška nalazi. U najgorem slučaju Biologer neće prepoznati grešku već će
            podatak iz tabele smjestiti na pogrešno mjesto, nakon čega će biti neophodna
            intervencija našeg Projektnog tima.
        </p>

        <p>Evo nekoliko stvari koje najčešće morate uskladiti prije uvoza podataka:</p>

        <ol>
            <li>
                Naučni naziv taksona je potrebno uskladiti tako da odgovara nazivima
                taksona u Biologer bazi podataka. Ukoliko takson ne postoji u bazi podataka,
                morate najprije zatražiti od Urednika taksonomske grupe da ga doda.
            </li>

            <li>
                <b>Jedna od najčešćih grešaka je zamjena koordinata geografske širine i
                geografske dužine, zbog čega veliki broj nalaza može da završi na
                pogrešnoj strani Zemljine kugle.</b> Geografska širina (y osa) predstavlja
                rastojanje od Ekvatora, dok je geografska dužina (x osa) rastojanje od Griniča.
                Na području Istočne Evrope brojčane vrijednosti geografske širine su uvijek
                veće od vrijednosti geografske dužine, što vam može biti od pomoći.
                Koordinate geografske širine i dužine se uvijek daju u stepenima (npr. 43,1111).
                Koordinate koje su date u stepenima, minutima i sekundama (npr. 43°10'10" ili 43°10,81')
                je potrebno pretvoriti u decimalni zapis. Isto važi i za koordinate koje su date
                u koordinatnom sistemu različitom od WGS84.
            </li>

            <li>
                Sve promjenljive koje se daju uz nalaz treba upisati koristeći engleske izraze iz Biologera.
                Na primjer za kolonu u kojoj upisujete pol jedinke dozvoljene su vrijednosti „male“
                (mužjak) i „female“ (ženka). Isto važi i za vrijednosti kolona u kojima definišete
                licence podataka i razvojne stadije jedinki.
            </li>

            <li>
                Nadmorska visina i preciznost koordinate se uvijek daju u metrima,
                pa je brojčane vrijednosti u kilometrima potrebno pretvoriti u metre.
            </li>
        </ol>

        <p>Primjer jedne proste tablele</p>

        <table>
            <thead>
                <tr>
                    <th>Vrsta</th>
                    <th>X</th>
                    <th>Y</th>
                    <th>Godina</th>
                    <th>Našao</th>
                    <th>Identifikovao</th>
                    <th>Licenca</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Papilio machaon</td>
                    <td>20,210</td>
                    <td>45,400</td>
                    <td>2014</td>
                    <td>Ivan Ivić</td>
                    <td>Miša Mišić</td>
                    <td>CC BY-SA 4.0</td>
                </tr>

                <tr>
                    <td>Aglais io</td>
                    <td>20,210</td>
                    <td>45,400</td>
                    <td>2000</td>
                    <td>Ivan Ivić</td>
                    <td>Miša Mišić</td>
                    <td>Closed</td>
                </tr>

                <tr>
                    <td>Pyrgus malvae</td>
                    <td>20,210</td>
                    <td>45,400</td>
                    <td>2015</td>
                    <td>Miša Mišić</td>
                    <td>Rade Radić</td>
                    <td>Partially open</td>
                </tr>
            </tbody>
        </table>

        <h2>Obavezna polja</h2>

        <p>
            Prilikom uvoza podataka, od korisnika se traži minimalan set podataka:
            naziv vrste, geografske koordinate, godina nalaza i licenca podataka.
            Iako nije obavezno, preporuka je da pored ovih polja upišete ko je našao i identifikovao vrstu.
        </p>

        <h2>Od tabele do baze</h2>

        <p>
            Ma koliko se trudili da ujednačimo naše tabele, ni jedna tabela sa
            podacima nikada neće biti ista. Zbog toga je neophodno reći Biologeru
            kako izgleda svaka tabela ponaosob! To se vrši jednostavnim izborom
            kolona i njihovog redoslijeda.
        </p>

        <p>
            Kliknite na dugme „Odaberi CSV datoteku“ i odaberite ranije pripremljenu
            tablelu sa vašeg računara. Ukoliko prvi red tabele sadrži nazive kolona,
            kao u našem primjeru, potrebno je da čekirate polje „Prvi red sadrži nazive kolona“.
            Nakon toga kliknite na dugme „Odaberi kolone“. Primijetit ćete da je spisak kolona
            nešto duži od onog koji sadrži vaša tabela, te da je pet kolona automatski
            označeno kao obavezne kolone.
        </p>

        <p>
            Ukoliko želimo da uvezemo podatke iz tabele koju smo dali kao primjer,
            moraćemo da označimo još dvije kolone: Uočio i Identifikovao. Jednostavno
            čekirajte još ove dvije kolone sa spiska i one će biti označene za uvoz iz tabele.
            Pošto Biologer ne može da zna redoslijed kolona u vašoj tabeli (s lijeva na desno),
            moraćemo još da premjestimo kolone u Biologeru (odozgo na dole) tako da odgovaraju tabeli.
            To možete uraditi jednostavnim prevlačenjem naziva kolona. U našem primjeru moramo rasporediti
            kolone sljedećim redoslijedom: Takson, Geografska dužina, Geografska širina, Godina, Uočio, Identifikovao, Licenca.
        </p>

        <p>
            Kada ste se uvjerili da je sve kako treba (i to 10 puta provjerili!),
            možete kliknuti na dugme Uvezi. Program će obraditi vašu tabelu i,
            ako je sve kako treba, nalazi iz tabele će biti pridodati vašim terenskim nalazima.
        </p>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
            <li><a href="{{ route('contributor.field-observations.index') }}">{{ __('navigation.my_field_observations') }}</a></li>
            <li><a href="{{ route('contributor.field-observations-import.index') }}">{{ __('navigation.field_observations_import') }}</a></li>
            <li class="is-active"><a>Uputstvo za uvoz</a></li>
        </ul>
    </div>
@endsection
