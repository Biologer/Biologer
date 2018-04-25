@extends('layouts.main')

@section('content')
    <section class="section is-hidden-touch mb-8">
        <div class="container has-text-centered">
            <img src="{{ asset('img/logo.svg') }}" class="image banner-image mx-auto">
        </div>
    </section>

    <div class="container pb-8">
        <p class="is-size-4 mb-8">Добродошли на нашу веб страницу која прикупља податке о
            распрострањености врста на подручју Србије. Ова верзија програма је још
            увек у фази тестирања, па вас молимо да будете стрпљиви. Уколико приметите
            било какве проблеме или имате нове идеје, слободно нам их пошаљите.</p>

        <div class="columns">
            <div class="column is-full-mobile is-one-third is-offset-one-third">
                <a href="{{ route('groups') }}" class="button is-primary is-outlined is-fullwidth"><b>Прегледај</b></a>
            </div>
        </div>

        <div class="columns">
            <div class="column is-size-5">
                <p>Можете испробати:</p>

                <ul class="is-done">
                    <li>Регистрацију корисника</li>
                    <li>Унос података (кроз веб софтвер)</li>
                    <li>Превод програма на више језика</li>
                    <li>
                        Спремна је прва стабилна Андроид апликација (<a href="{{ config('biologer.android_app_url') }}" target="_blank">преузмите v{{ config('biologer.android_app_version') }}</a>).<br/>
                        <small>Уколико сте прешли са бета верзије најпре обришите податке из апликације.</small>
                    </li>
                </ul>
            </div>
            <div class="column is-size-5">
                <p>Планирамо да направимо:</p>

                <ul class="is-not-done">
                    <li>Приказ географских података</li>
                    <li>‎Извоз података</li>
                    <li>Систем обавештења</li>
                    <li>Систем комуникације између корисника</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
