@extends('layouts.main', ['title' => 'Развој подржали'])

@section('content')
<section class="section content">
    <div class="container">
        <h1>Развој подржали</h1>

        <h2>Организације и фондације које су финансијски подржале развој пројекта Биологер</h2>

        <img src="https://www.rufford.org/sites/all/themes/rufford/img/rufford.jpg" alt="Rufford" class="image mx-auto mb-4">

        <p class="has-text-justified">
            Развој апликације је покренут средствима фондације
            <a href="https://www.rufford.org/">Рафорд, мали грантови</a>,
            преко пројекта бр. <a href="https://www.rufford.org/projects/ana_golubovi%C4%87_0">20507-B</a>
            и <a href="https://www.rufford.org/projects/milo%C5%A1_popovi%C4%87_0">24652-B</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/mava-foundation.jpg') }}" alt="Mava фондација" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/udruga-hyla.jpg') }}" alt="Удружење Hyla" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/biolosko-drustvo-sava-petrovic.png') }}" alt='Биолошко друштво "Др Сава Петровић"' class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Даљи развој апликације подржан је средствима фондације
            <a href="http://mava-foundation.org/">МАВА</a> преко
            <a href="http://www.hhdhyla.hr/projektii/aktualni-projekti">„Пројекта заштите биодиверзитета на кречњацима Динарског масива“</a>,
            који реализује удружење <a href="http://www.hhdhyla.hr/">Хила</a>.
            Развој софтвера за потребе пројекта спроводи
            <a href="http://bddsp.org.rs/">Биолошко друштво „Др Сава Петровић“</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/ministarstvo-prosvete-nauke-i-tehnoloskog-razvoja-srbija.png') }}" alt="Министарство просвете, науке и технолошког развоја Републике Србије" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/pmf-nis.jpg') }}" alt="Природно-математички факултет, Универзитет у Нишу" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/bioloski-fakultet-beograd.png') }}" alt="Биолошки факултет, Универзитет у Београду" class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Рад Милоша Поповића подржан је средствима Министарства просвете,
            науке и технолошког развоја Републике Србије, пројекат бр.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/305-173025-evolucija-u-heterogenim-sredinama-mehanizmi-adaptacija-biomonitoring-i-konzervacija-biodiverziteta">173025</a>,
            451-03-68/2020-14/200124 и 451-03-9/2021-14/200124,
            преко Департмана за биологију и екологиjу, Природно-математичког факултета, Универзитета у Нишу.
        </p>

        <p class="has-text-justified">
            Рад Ане Голубовић подржан је средствима Министарства просвете,
            науке и технолошког развоја Републике Србије, пројекат бр.
            <a href="http://www.ibiss.bg.ac.rs/index.php/sr-yu/projekti/item/308-173043-diverzitet-vodozamaca-i-gmizavaca-balkana-evolucioni-aspekti-i-konzervacija">173043</a>,
            преко Биолошког факултета, Универзитета у Београду.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/fond-za-zastitu-okolisa-federacije-bih.jpg') }}" alt="Фонд за заштиту околиша Федерације БиХ" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/udruzenje-biolog.jpg') }}" alt="Удружење БИО.ЛОГ" class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Биологер заједница у Босни и Херцеговини и развој иОС апликације су подржани од стране
            <a href="https://fzofbih.org.ba">Фонда за заштиту околиша Федерације БиХ</a>
            кроз пројекат „Биологер - онлајн база података о биодиверзитету БиХ“,
            реализован преко удружења <a href="https://www.biolog.ba/">БИО.ЛОГ</a>.
        </p>

        <div class="columns mb-4">
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/eko-sistem.png') }}" alt="ЕКО-СИСТЕМ" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/mladi-istrazivaci-srbije-cyrl.png') }}" alt="Млади истраживачи Србије" class="image mx-auto" style="max-height: 300px">
            </div>
            <div class="column flex is-flex-center">
                <img src="{{ asset('img/organisations/svedska-cyrl.png') }}" alt="Шведска" class="image mx-auto" style="max-height: 300px">
            </div>
        </div>

        <p class="has-text-justified">
            Развој читаве софтверске платформе је настављен у оквиру пројекта
            „Еко мрежа за будућност Србије“, као део активности програма
            <a href="https://ekosistem.mis.org.rs/">ЕКО-СИСТЕМ</a>
            који реализују <a href="https://www.mis.org.rs">Млади истраживачи Србије</a>,
            а подржава <a href="https://www.swedenabroad.se/en/">Шведска</a>.
        </p>

        <h2>Појединци који су допринели развоју пројекта Биологер</h2>

        <p>
            Милош Поповић – Вођа пројекта, организација портала и података, почетни графички дизајн, развој Андроид апликације.<br>
            Ана Голубовић – Организација портала.<br>
            Ненад Живановић – Софтверско решење за Биологер платформу, веб дизајн, организација портала и података.<br>
            Марко Николић – Легислатива Биологер заједнице.<br>
            Бранко Јовановић – Развој Андроид апликације.<br>
            Вања Лазић – Дизајн иконица за животињске групе.<br>
            Јожеф Дожаи - Допуна иконица за биљке и гљиве.<br>
            Борис Брадарић - Побољшање рада Андроид апликације на старијим уређајима.<br>
            Никола Поповић – Развој иОС апликације.<br>
            Никола Васић – Развој таксономске базе и БирдЛогер-а.
        </p>
    </div>
</section>
@endsection
