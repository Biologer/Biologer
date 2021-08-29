@extends('layouts.main', ['title' => __('navigation.closed_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Zatvorena licenca</h1>
        <p class="subtitle is-2 has-text-centered">Licenca za podatke sa ograničenim pravom pristupa</p>

        <section class="section has-text-justified">
            <p class="mb-4">
                Ova licenca bliže određuje pravila i uslove korištenja podataka
                iz Biologer baze podataka, koje korisnici ne žele da dijele sa drugima.
                Podaci su skriveni i ne prikazuju se na mapi.
            </p>

            <p class="mb-4">
                Odabirom ove licence ograničava se vidljivost unesenih podataka ostalim
                korisnicima baze. Uneseni podaci biće vidljivi samo autoru nalaza,
                taksonomskim ekspertima koji verifikuju nalaze i administratorima
                web stranice. Podaci pod ovom licencom neće biti prikazivani na
                kartama rasprostranjenosti taksona, niti će drugi korisnici moći da ih
                izvezu iz baze podataka.
            </p>

            <p class="mb-4">
                Izborom ove licence autori zadržavaјu sva prava nad unetim podacima.
                Administratori Biologera i taksonomski eksperti zadržavaјu pravo
                na upotrebu podataka unetih pod ovom licencom i to za potrebe
                izrade dokumenata od nacionalnog značaјa, potrebe zaštite prirode
                (Crvene knjige, Crvene liste, reviziјa zakona, definisanje granidža zaštićenih područјa i sl.)
                i za potrebe statističke obrade podataka (npr. zarad zaštite i očuvanja vrsta i njihovih staništa),
                ali bez obјavljivanja izvornog podatka. U slučaјu korišćena podataka za naučne publikaciјe,
                moguće јe upotrebiti podatak u sumarnom prikazu, bez detalja o originalnom nalazu.
                U slučaјu da postoјi potreba da se izvorni podatak pod ovom licencom obјavi u celosti,
                Administratori Biologera i taksonomski eksperti moraјu to učiniti uz saglasnost autora podatka.
            </p>

            <p class="mb-4">
                Zbog ograničene mogućnosti provjere ovako unesenih podataka i nemogućnosti
                detaljnog prikaza podataka, razvojni tim Biologera ne preporučuju
                odabir ove licence!
            </p>
        </section>
    </div>
@endsection
