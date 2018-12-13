@extends('layouts.dashboard', ['title' => __('navigation.field_observations_import')])

@section('content')
    <div class="box content">
        <h1>Uputstvo za uvoz terenskih podataka</h1>

        <div class="message mt-8">
            <div class="message-body">
                Uvoz podataka iz tablice u Biologer je kompleksan proces. Tom prilikom
                mogu se potkrasti greške koje nije jednostavno ispraviti. Unos podataka
                na ovaj način je opravdan ukoliko se radi o velikom setu podataka,
                koji nije jednostavno izmijeniti unutar web okruženja Biologera.
                Ipak, prije nego što se odlučite za uvoz podataka iz tablice razmislite o drugim
                mogućnostima i dobro proučite ovo uputstvo kako biste izbjegli neželjene komplikacije.
            </div>
        </div>

        <h2>Format tablice</h2>

        <p>
            Biologer radi sa tablicama koje su sačuvane u „.CSV“ formatu (tekstualna datoteka
            u kojoj su stupci razdvojeni zarezom, a svaki red teksta je zaseban red u tablici).
            Bilo koju tablicu možete sačuvati kao CSV datoteku, pošto većina programa za rad
            sa tablicama, statističkih paketa i GIS alata koristi ovaj format. Na primjer iz
            programa LibreOffice dovoljno je da odete u izbornik "Datoteka → Sačuvaj kao"
            i izaberete „Text CSV (.csv)“. Također, možete jednostavno odabrati ekstenziju
            na kraju naziva datoteke (npr. „za_uvoz.csv“) i LibreOffice će automatski
            shvatiti kako želite sačuvati tablicu.
        </p>

        <h2>Sadržaj tablice</h2>

        <p>
            Prije nego što uvezete podatke iz tablice morate pravilno formatirati
            njena polja kako bi Biologer prepoznao podatke. Prilikom uvoza podataka
            Biologer čita vrijednosti iz redova i stupaca tablice, provodi osnovnu
            provjeru podataka i smješta podatke u odgovarajuća polja unutar baze podataka.
            Ukoliko ne formatirate polje kako treba, Biologer će vam prijaviti grešku i
            ukazati u kojem redu tablice se greška nalazi. U najgorem slučaju Biologer
            neće prepoznati grešku već će podatak iz tablice smjestiti na pogrešno mjesto,
            nakon čega će biti neophodna intervencija našeg Projektnog tima.
        </p>

        <p>Evo nekoliko stvari koje najčešće morate uskladiti prije uvoza podataka:</p>

        <ol>
            <li>
                Znanstveni naziv vrste je potrebno uskladiti tako da odgovara nazivima
                vrsta u Biologer bazi podataka. Ukoliko vrsta ne postoji u bazi podataka,
                morate najprije zatražiti od Urednika taksonomske skupine da ga doda.
            </li>

            <li>
                <b>Jedna od najčešćih greški je zamjena koordinata geografske širine i geografske dužine,
                zbog čega veliki broj nalaza može završiti na pogrešnoj strani Zemljine kugle.</b>
                Geografska širina (y os) predstavlja udaljenost od Ekvatora, dok je geografska dužina
                (x os) udaljenost od Greenwicha. Na području Istočne Evrope brojčane vrijednosti
                geografske širine su uvijek veće od vrijednosti geografske dužine, što vam može biti od pomoći.
                Koordinate geografske širine i dužine su uvijek u decimalama. Koordinate koje su u stupnjevima,
                minutama i sekundama (npr. 43°10'10" ili 43°10,81') je potrebno pretvoriti u decimalni zapis
                (npr. 43,1111). Isto vrijedi i za koordinate koje su zadane u koordinatnom sustavu različitom od WGS84.
            </li>

            <li>
                Sve promjenjive vrijednosti koje se upisuju uz nalaz treba upisati
                koristeći engleske izraze iz Biologera. Na primjer za stupac u koji upisujete
                spol jedinke dozvoljene su vrijednosti „male“ (mužjak) i „female“ (ženka).
                Isto važi i za vrijednosti stupaca u kojima određujete licence podataka i razvojne stadije jedinki.
            </li>

            <li>
                Nadmorska visina i preciznost koordinate su uvijek u metrima,
                pa je brojčane vrijednosti u kilometrima potrebno pretvoriti u metre.
            </li>
        </ol>

        <p>Primjer jednostavne tablice</p>

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
            Prilikom unosa podataka, od korisnika se traži minimalan set podataka:
            naziv vrste, geografske koordinate, godina nalaza i licenca podataka.
            Iako nije obavezno, preporuka je da pored ovih polja upišete tko je pronašao i odredio vrstu.
        </p>

        <h2>Od tablice do baze</h2>

        <p>
            Ma koliko se trudili da ujednačimo naše tablice, ni jedna tablica sa
            podacima nikad neće biti ista. Zbog toga je neophodno reći Biologeru
            kako izgleda svaka tablica posebno! To se vrši jednostavnim izborom
            stupaca i njihovog redoslijeda.
        </p>

        <p>
            Kliknite na gumb „Odaberi CSV datoteku“ i odaberite ranije pripremljenu
            tablicu sa vašeg računala. Ukoliko prvi red tablice sadrži nazive stupaca,
            kao u našem primjeru, potrebno je označiti polje „Prvi red sadrži nazive stupaca“.
            Nakon toga kliknite na gumb „Odaberi stupce“. Primijetit ćete da je popis stupaca
            nešto duži od onog kojeg sadrži vaša tablica, te da je pet stupaca automatski
            označeno kao obavezni stupci.
        </p>

        <p>
            Ako želimo uvesti podatke iz tablice koju smo dali kao primjer, morat ćemo
            označiti još dva stupca: Uočio i Odredio. Jednostavno označite još ova dva
            stupca sa popisa i oni će biti označeni za uvoz iz tablice. Pošto Biologer
            ne može znati redoslijed stupaca u vašoj tablici (s lijeva na desno), morat
            ćete još premjestiti stupce u Biologeru (odozgo prema dolje) tako da odgovaraju tablici.
            To možete učiniti jednostavnim premještanjem naziva stupaca. U našem primjeru moramo
            rasporediti stupce sljedećim redoslijedom: Vrsta, Geografska dužina, Geografska širina,
            Godina, Uočio, Odredio, Licenca.
        </p>

        <p>
            Kada ste se uvjerili da je sve kako treba (i to 10 puta provjerili!),
            možete kliknuti na gumb Uvezi. Program će obraditi vašu tablicu i, ako je sve kako treba,
            nalazi iz tablice će biti pridodani vašim terenskim nalazima.
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
