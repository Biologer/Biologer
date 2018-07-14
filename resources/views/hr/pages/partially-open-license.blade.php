@extends('layouts.main', ['title' => __('navigation.partially_open_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Delimično otvorena licenca</h1>
        <p class="subtitle is-2 has-text-centered">Licenca za podatke sa ograničenim geografskim prikazom</p>

        <section class="section has-text-justified">
            <p class="mb-4">
                Ova licenca bliže određuje pravila i uslove korišćenja podataka iz
                Biologer baze podataka, sa “ograničenim prikazom”. Podaci se
                prikazuju na mapi u vidu kvadrata veličine 10×10 km.
            </p>

            <p class="mb-4">
                Osnova ove licence je Krijejtiv komons licenca, autorstvo-nekomercijalno-deliti
                pod istim uslovima (CC BY-NC-SA 4.0). To znači da je dozvoljeno deljenje
                podataka sa ograničenim prikazom od strane trećih lica, pod uslovima
                navedenim u Krijejtiv komons <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" title="CC BY-NC-SA 4.0" target="_blank"> licenci</a>.
            </p>

            <p class="mb-4">
                Razlika u odnosu na potpuno otvorene podatke se jedino ogleda u
                preciznosti prikaza geografske lokacije nalaza. Ograničeni prikaz
                podataka omogućava prikaz lokacije u vidu kvadrata veličine 10×10 km,
                bez mogućnosti prikaza preciznije lokacije drugim korisnicima baze.
                Ostali podaci koji su dostavljeni uz nalaz će biti prikazani u celosti.
                Ovo se odnosi kako na prikaz podataka u Biologeru, tako i na podatke
                koji su dostupni za izvoz iz baze.
            </p>

            <p class="mb-4">
                Izborom ove licence autori zadržavaju sva prava na upotrebu precizne
                lokacije nalaza. Administratori Biologera i taksonomski eksperti
                zadržavaju pravo na upotrebu podataka unetih pod ovom licencom i
                to za potrebe izrade dokumenata od nacionalnog značaja (Crvene knjige,
                Crvene liste i sl.), kao i za potrebe statističke obrade podataka
                (npr. zarad zaštite i očuvanja taksona i njihovih staništa), ali bez
                objavljivanja tačne lokacije izvornog podatka. U slučaju da postoji
                potreba da se lokacija izvornog podatka koji je zabeležen pod ovom
                licencom objavi, administratori Biologera i taksonomski eksperti
                moraju to učiniti uz saglasnost autora podatka.
            </p>
        </section>
    </div>
@endsection
