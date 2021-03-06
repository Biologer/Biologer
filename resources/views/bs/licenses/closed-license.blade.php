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
                Izborom ove licence autori zadržavaju sva prava nad unesenim podacima.
                Administratori Biologera i taksonomski eksperti zadržavaju pravo
                na upotrebu podataka unesenih pod ovom licencom i to za potrebe
                izrade dokumenata od nacionalnog značaja (Crvene knjige, Crvene liste i sl.),
                kao i za potrebe statističke obrade podataka (npr. za potrebe zaštite i
                očuvanja taksona i njihovih staništa), ali bez objavljivanja izvornog
                podatka. U slučaju da postoji potreba da se izvorni podatak zabilježen
                pod ovom licencom objavi u cijelosti, administratori Biologera i
                taksonomski eksperti moraju to učiniti uz saglasnost autora podatka.
            </p>

            <p class="mb-4">
                Zbog ograničene mogućnosti provjere ovako unesenih podataka i nemogućnosti
                detaljnog prikaza podataka, razvojni tim Biologera ne preporučuju
                odabir ove licence!
            </p>
        </section>
    </div>
@endsection
