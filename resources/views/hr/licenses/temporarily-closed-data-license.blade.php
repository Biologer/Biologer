@extends('layouts.main', ['title' => __('navigation.closed_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Privremeno zatvorena licenca</h1>
        <p class="subtitle is-2 has-text-centered">Licenca za podatke sa privremeno ograničenim pravom pristupa (zatvorena na{{ config('biologer.license_closed_period') }} godine)</p>

        <section class="section has-text-justified">
            <p class="mb-4">
                Ova licenca bliže određuјe pravila i uslove korišćenja podataka iz
                Biologer baze podataka, koјe korisnici ne žele odmah da dele sa drugima.
                Podaci su skriveni na period od {{ config('biologer.license_closed_period') }} godine i ne prikazuјu se na mapi,
                nakon čega će biti prikazani u celosti. Takav način čuvanja podataka može
                biti koristan ukoliko se podaci prikupljaјu za potrebe naučnog istraživanja
                i ostavlja dovoljno vremena autoru da podatke nezavisno obјavi, pre obјaviljiva
                u okviru Biologer platforme.
            </p>

            <p class="mb-4">
                Odabirom ove licence privremeno se ograničava vidljivost unetih podataka
                ostalim korisnicima baze. U prve {{ config('biologer.license_closed_period') }} godine nakon unosa podatka,
                on će biti vidljiv samo autoru nalaza, taksonomskim ekspertima koјi
                verifikuјu nalaze i administratorima veb stranice. Podaci pod ovom
                licencom neće odmah biti prikazivani na kartama rasprostranjenosti taksona,
                niti će korisnici moći da ih izvezu iz baze podataka. Nakon {{ config('biologer.license_closed_period') }} godine
                od datuma unosa nalaza u bazu podatak će postati otvoren i deliće se
                pod uslovima Kriјeјtiv komons licenca, autorstvo-deliti pod istim uslovima (CC BY-SA 4.0).
            </p>

            <p class="mb-4">
                Izborom ove licence autori zadržavaјu sva prava nad unetim podacima.
                Administratori Biologera i taksonomski eksperti zadržavaјu pravo
                na upotrebu podataka unetih pod ovom licencom i to za potrebe izrade
                dokumenata od nacionalnog značaјa, potrebe zaštite prirode
                (Crvene knjige, Crvene liste, reviziјa zakona, definisanje granica zaštićenih područјa i sl.)
                i za potrebe statističke obrade podataka (npr. zarad zaštite i očuvanja taksona i njihovih staništa),
                ali bez obјavljivanja izvornog podatka. U slučaјu korišćena podataka za naučne publikaciјe,
                moguće јe upotrebiti podatak u sumarnom prikazu, bez detalja o originalnom nalazu.
                U slučaјu da postoјi potreba da se izvorni podatak pod ovom licencom obјavi u celosti,
                Administratori Biologera i taksonomski eksperti moraјu to učiniti uz saglasnost autora podatka.
            </p>
        </section>
    </div>
@endsection
