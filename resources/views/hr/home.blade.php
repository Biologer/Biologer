@extends('layouts.main')

@section('content')
    <section class="section is-hidden-touch mb-8">
        <div class="container has-text-centered">
            <img src="{{ asset('img/logo.svg') }}" class="image banner-image mx-auto">
        </div>
    </section>

    <div class="container pb-8">
        <p class="is-size-4 mb-8">Dobrodošli na našu web stranicu koja prikuplja podatke o rasprostranjenosti
            vrsta na području Hrvatske. Ova inačica programa još je uvek u fazi
            testiranja, pa Vas molimo da budete strpljivi. Ukoliko primetite bilo
            kakve probleme ili imate nove ideje, slobodno nas kontaktirate.</p>

        <div class="columns">
            <div class="column is-full-mobile is-one-third is-offset-one-third">
                <a href="{{ route('groups') }}" class="button is-primary is-outlined is-fullwidth"><b>Pregledaj</b></a>
            </div>
        </div>

        <div class="columns">
            <div class="column is-size-5">
                <p>Već smo napravili:</p>

                <ul class="is-done">
                    <li>Registraciju korisnika</li>
                    <li>Unos podataka (kroz web sučelje)</li>
                    <li>Prijevod programa na više jezika</li>
                    <li>
                        Spremna je prva stabilna Android aplikacija (<a href="{{ config('biologer.android_app_url') }}" target="_blank">preuzmite v{{ config('biologer.android_app_version') }}</a>).<br/>
                        <small>Ukoliko ste prešli sa beta verzije  obrišite podatke iz aplikacije.</small>
                    </li>
                </ul>
            </div>
            <div class="column is-size-5">
                <p>Planiramo napraviti:</p>

                <ul class="is-not-done">
                    <li>Prikaz geografskih podataka</li>
                    <li>Izvoz podataka</li>
                    <li>Sustav obavještavanja</li>
                    <li>Sustav komunikacije između korisnika</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
