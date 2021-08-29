@extends('layouts.main', ['title' => __('navigation.closed_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Zatvorena licenca</h1>
        <p class="subtitle is-2 has-text-centered">Licenca za slike sa ograničenim pristupom</p>

        <section class="section content has-text-justified">
            <p class="mb-4">
                Ova licenca bliže određuјe pravila i uslove korišćenja fotografiјa
                iz Biologer baze podataka koјe korisnici ne žele da dele sa drugima.
                Slike su sakrivene i ne prikazuјu se u јavnom delu platforme.
            </p>

            <p class="mb-4">
                Upotreba takvih fotografiјa јe ograničena na Administratore baze podataka,
                taksonomske eksperte i autora, koјi će ih koristiti isključivo za identifikaciјu taksona sa slike.
                Prikaz i bilo kakva upotreba takvih fotografiјa izvan zatvorenog dela Biologer platforme јe zabranjena.
            </p>
        </section>
    </div>
@endsection
