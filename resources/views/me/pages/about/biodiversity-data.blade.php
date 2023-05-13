@extends('layouts.main', ['title' => 'Podaci o biodiverzitetu'])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>Podaci o biodiverzitetu</h1>

            <p class="has-text-justified">
                Termin biodiverzitet prvi put se pominje početkom osamdesetih godina
                i u širem smislu označava sveobuhvatnu varijabilnost biljaka, životinja,
                gljiva i mikroorganizama na planeti Zemlji. Prema Konvenciji o biološkoj
                raznovrsnosti, održanoj u Rio de Ženeiru 1992. godine, biodiverzitet je
                definisan kao „varijabilnost među živim organizmima, uključujući između ostalog,
                kopnene, morske i druge vodene ekosisteme čiji su oni dio; ovo uključuje
                diverzitet unutar vrsta, između vrsta i između ekosistema“. Kako još uvek
                ne postoji opšteprihvaćena definicija biodiverziteta, možemo reći i da
                biodiverzitet označava sveukupnost gena (genetički diverzitet), vrsta
                (specijski diverzitet) i ekosistema (ekosistemski diverzitet) na Zemlji.
                Glavni faktori ugrožavanja biodiverziteta u vrijemenu u kojem živimo su
                izmjena i fragmentacija staništa, pretjerana eksploatacija resursa,
                različite vrste zagađivanja, kao i naseljavanje invazivnih i alohtonih vrsta.
            </p>

            <p class="has-text-justified">
                Prikupljanje podataka o biološkoj raznovrsnosti jedne zemlje osnovni je
                korak ka valorizaciji prirodnog bogatstva i planiranju mjera zaštite biodiverziteta.
                Projekat Biologer je namjenjen upravo tome. Biologer zajednica radi na
                prikupljanju podataka o biodiverzitetu, što je značajno, kako za poznavanje
                rasprostranjenja taksona, tako i za procjenu prirodnih vrijednosti područja.
                Kako bi prikupljeni podaci bili verodostojni i upotrebljivi, definisana su
                tri osnovna koraka: unos, verifikacija i prikaz podatka.
            </p>

            <p class="has-text-justified">
                Unos je nalaz taksona, koji se sastoji od niza podataka prikupljenih na terenu,
                iz naučne zbirke ili stručne literature. Jedan unos može sadržati podatke poput:
                naziva taksona, geografske širine i dužine, fotografije nalaza, nadmorske
                visine na kojoj je zabilježen takson i još mnogo toga. Podatke unosi Korisnik
                direktno u bazu ili preko Android aplikacije pod jednom od dostupnih licenci.
                Korisnik bira željenu licencu pri registraciji na platformu, u podešavanjima i/ili
                prilikom pojedinačnog unosa podatka. Savjetujemo Korisnicima da izbegavaju
                zatvorene licence, pošto time umanjuju upotrebljivost nalaza.
            </p>

            <p class="has-text-justified">
                Svi podaci uneseni u bazu dolaze do drugog koraka, verifikacije nalaza od
                strane Urednika. U cilju pouzdane verifikacije nalaza poželjno je da
                Korisnici dostave što više informacija o nalazima sa terena. Urednici
                odobravaju nalaze ili ih označavaju kao nalaze koje nije moguće identifikovati.
                Korisnik u svakom trenutku može dopuniti nalaz ukoliko posjeduje dodatne
                podatke koji mogu doprinijeti identifikaciji taksona.
            </p>

            <p class="has-text-justified">
                Treći korak jeste kartografski prikaz podataka ostalim Korisnicima baze i
                trećim licima. Prikaz podataka regulisan je ranije navedenim licencama.
                Precizne informacije o svakom pojedinačnom podatku imaju: Korisnik koji je
                uneo podatak, Urednički tim date taksonomske grupe i Administratori.
                Za podatke čiji je prikaz ograničen (Djelimično otvorena i Zatvorena licenca)
                Urednici i Administratori u dogovoru sa Korisnikom donose odluku o načinu
                korišćenja takvih podataka ukoliko se za to ukaže potreba.
            </p>
        </div>
    </section>
@endsection
