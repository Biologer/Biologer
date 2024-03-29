<?php

return [
    'home' => [
        'browse' => 'Pregledaj podatke',
        'android_link' => 'Android aplikacija',
        'android_title' => 'Preuzmite Android aplikaciju',
        'ios_link' => 'iOS aplikacija',
        'ios_title' => 'Preuzmite iOS aplikaciju',
        'welcome' => 'Biologer je jednostavan i slobodan softver osmišljen za prikupljanje podataka o biološkoj raznovrsnosti.',
        'stats' => 'Zajednica „:community“ broji :userCount korisnika, koji su prikupili :observationCount nalaza.',

        'announcements' => [
            'title' => 'Novosti',
            'see_all' => 'Pogledaj sve',
        ],
    ],

    'announcements' => [
        'title' => 'Objave',
        'no_announcements' => 'Nema objava',
        'read' => 'Pročitaj vijest',
    ],

    'field_observations_import' => [
        'short_info' => 'Ukoliko želite da uvezete podatke iz tabele, potrebno '.
            'je da ona bude sačuvana kao CSV datoteka. Nakon izbora datoteke, treba '.
            'da uskladite redoslijed kolona u Biologeru tako da on odgovara redoslijedu u '.
            'tabeli i da odaberete koje kolone želite da uvezete. Spisak taksona mora '.
            'da prati taksonomiju Biologer baze podataka, a spisak vrijednosti za svaku '.
            'kolonu (npr. stadij, pol, licenca) mora biti dat na osnovu vrijednosti '.
            'na engleskom jeziku.',
    ],

    'taxa' => [
        'observations' => 'nalaz|nalaza|nalaza',

        'number_of_observations_per_mgrs10k_field' => 'Broj nalaza po MGRS 10k polju',
        'mgrs10k_field' => 'MGRS 10k polje',
        'number_of_observations' => 'Broj nalaza',
        'present_in_literature' => 'Prisutno u literaturi',
    ],

    'stats' => [
        'user' => 'Korisnik',
        'curator' => 'Urednik',
        'observations_count' => 'Broj nalaza',
        'identifications_count' => 'Broj identifikacija',
        'year' => 'Godina',
        'group' => 'Grupa',
        'top_10_users' => 'Prvih 10 korisnika po broju unijetih nalaza',
        'top_10_curators' => 'Prvih 10 urednika po broju identifikacija',
        'observations_count_by_group' => 'Broj nalaza po grupama',
        'observations_count_by_year' => 'Broj nalaza po godinama (u poslednjih 10 godina)',
    ],
];
