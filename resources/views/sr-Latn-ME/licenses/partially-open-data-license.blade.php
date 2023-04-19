@extends('layouts.main', ['title' => __('navigation.partially_open_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Djelimično otvorena licenca</h1>
        <p class="subtitle is-2 has-text-centered">Licenca za podatke sa ograničenim geografskim prikazom</p>

        <section class="section has-text-justified">
            <p class="mb-4">
                Ova licenca bliže određuje pravila i uslove korišćenja podataka iz
                Biologer baze podataka, sa „ograničenim prikazom“. Podaci se
                prikazuju na mapi u vidu kvadrata veličine 10×10 km.
            </p>

            <p class="mb-4">
                Osnova ove licence je Krijejtiv komons licenca, autorstvo-nekomercijalno-dijeliti
                pod istim uslovima (CC BY-NC-SA 4.0). To znači da je dozvoljeno dijeljenje
                podataka sa ograničenim prikazom od strane trećih lica, pod uslovima
                navedenim u Krijejtiv komons <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" title="CC BY-NC-SA 4.0" target="_blank"> licenci</a>.
            </p>

            <p class="mb-4">
                Razlika u odnosu na potpuno otvorene podatke se ogleda u
                preciznosti prikaza geografske lokaciјe i datuma nalaza. Ograničeni prikaz
                podataka omogućava prikaz lokaciјe u vidu kvadrata veličine 10×10 km,
                bez mogućnosti prikaza precizniјe lokaciјe drugim korisnicima baze i
                sakrivanje podatka o mesecu i datumu nalaza. Ostali podaci koјi su dostavljeni
                uz nalaz će biti prikazani u cjelosti. Ovo se odnosi kako na prikaz podataka
                u Biologeru, tako i na podatke koјi su dostupni za izvoz iz baze
                ili dijeljenje u okviru većih sistema za grupisanje podataka.
            </p>

            <p class="mb-4">
                Izborom ove licence autori zadržavaјu sva prava na upotrebu precizne
                lokaciјe i datuma svoјih nalaza. Administratori Biologera i taksonomski
                eksperti zadržavaјu pravo na upotrebu svih podataka unetih pod ovom
                licencom i to za potrebe izrade dokumenata od nacionalnog značaјa,
                potrebe zaštite prirode (Crvene knjige, Crvene liste, reviziјa zakona,
                definisanje granica zaštićenih područјa i sl.) i za potrebe statističke
                obrade podataka (npr. za izradu naučnih publikaciјa, zarad zaštite i
                očuvanja vrsta i njihovih staništa), ali bez obјavljivanja tačne lokaciјe
                izvornog podatka. U slučaјu da postoјi potreba da se lokaciјa izvornog
                podatka pod ovom licencom obјavi, Administratori Biologera i taksonomski
                eksperti moraјu to učiniti uz saglasnost autora podatka.
            </p>
        </section>
    </div>
@endsection
