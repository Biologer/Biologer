@extends('layouts.main', ['title' => 'Lokalna zajednica'])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>Lokalna zajednica</h1>

            <p>
                Naziv lokalne zajednice: {{ config('biologer.community.name') }}.<br>
                Država: {{ __(config('biologer.community.country')) }}.</br>
                Adresa: {{ config('biologer.community.address') }}.
            </p>

            <p class="has-text-justified">
                <b>Administratori</b> su lica koja upravljaju bazom podataka, imaju
                uvid u sve podatke u okviru lokalne Biologer platforme i zaduženi
                su za cjelokupnu organizaciju Lokalne zajednice. Početni tim Administratora
                odobrava Projektni tim prilikom pokretanja nove Lokalne zajednice.
                Novi Administratori se obično biraju iz redova Urednika, za šta je
                neophodna saglasnost 2/3 postojećih Administratora. Administratori
                imaju pravo da raspolažu svim podacima i da donose odluke o njihovom
                korišćenju (u skladu sa licencama koje izaberu Korisnici).
            </p>

            <p class="has-text-justified">
                Administratori Lokalne zajednice „{{ config('biologer.community.name') }}“ su:
            </p>

            <ul>
                @foreach ($admins as $admin)
                    <li>{{ $admin->full_name }}</li>
                @endforeach
            </ul>

            <p class="has-text-justified">
                <b>Urednici</b> su taksonomski eksperti za određene grupe organizama
                koji pregledaju pristigle podatke, odobravaju ih, po potrebi koriguju
                ili proglašavaju nemogućim za identifikaciju. Urednici su eksperti
                sa dugogodišnjim iskustvom u terenskom radu i dokazanim poznavanjem
                određenih grupa organizama. Urednike taksonomskih grupa u svakoj Lokalnoj
                zajednici biraju Administratori iz redova Korisnika. Dodavanje novih
                Urednika mora prihvatiti 2/3 postojećih Urednika određene taksonomske
                grupe, kao i 2/3 Administratora. Urednici imaju pravo da raspolažu
                podacima nad kojima su nadležni, i da donose odluke o njihovom korišćenju
                (u skladu sa licencama koje izaberu Korisnici).
            </p>

            <p class="has-text-justified">
                Uređivački tim Lokalne zajednice „{{ config('biologer.community.name') }}“ se sastoji od
                {{ $curators->count() }} ljudi koji pokrivaju {{ $taxonomicGroupsCount }} taksonomskih grupa, i to:
            </p>

            <ul>
                @foreach ($curators as $curator)
                    <li>{{ $curator->full_name }} - {{ $curator->curatedTaxa->pluck('name')->implode(', ') }}</li>
                @endforeach
            </ul>

            <p class="has-text-justified">
                <b>Korisnici</b> su svi članovi Lokalne zajednice, a status Korisnika
                se stiče prilikom registracije na veb platformu. U Korisnike se ubrajaju
                i Urednici i Administratori. Korisnici čine Biologer zajednicu i njihovim
                zalaganjem dolazimo do vrijednih podataka o rasprostranjenju taksona na određenom
                geografskom području.
            </p>

            <p class="has-text-justified">
                Biologer zajednica „{{ config('biologer.community.name') }}“ broji {{ $usersCount }} Korisnika,
                koji su prikupili {{ $observationsCount }} podataka o našoj biološkoj raznovrsnosti.
            </p>
        </div>
    </section>
@endsection
