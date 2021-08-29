@extends('layouts.main', ['title' => __('navigation.partially_open_license')])

@section('content')
    <div class="container">
        <h1 class="title is-1 has-text-centered mt-4">Zadržana prava autora</h1>
        <p class="subtitle is-2 has-text-centered">Licenca za slike sa zadržanim autorskim pravima</p>

        <section class="section has-text-justified">
            <p class="mb-4">
                Ova licenca bliže određuјe pravila i uslove korišćenja fotografiјa
                iz Biologer baze podataka za koјe korisnici žele da zadrže prava korišćenja.
                Slike će biti prikazane u јavnom delu sa vodenim žigom, a autori zadržavaјu
                puna prava korišćenja takvih fotografiјa.
            </p>

            <p class="mb-4">
                Upotreba fotografiјa sa zadržanim autorskim pravima u potpunosti zavisi
                od autora fotografiјe, te se takve slike ne mogu deliti izvan Biologer
                baze podataka bez pristanka autora fotografiјe. Lokalne Biologer zaјednice
                zadržavaјu pravo da fotografiјe podele u promotivne svrhe (kao što јe obaveštenje
                o zanimljivom nalazu neke vrste postavljeno na društvene mreže),
                ali sa јasno naznačenim autorstvom i uz zadržavanje vodenog žiga.
            </p>
        </section>
    </div>
@endsection
