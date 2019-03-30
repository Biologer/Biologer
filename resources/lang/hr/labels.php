<?php

return [
    'id' => 'ID',
    'actions' => 'Akcije',
    'created_at' => 'Napravljeno',

    'tables' => [
        'from_to_total' => 'Prikazuje se :from-:to od ukupno :total',
    ],

    'sexes' => [
        'male' => 'Mužjak',
        'female' => 'Ženka',
    ],

    'transfer' => [
        'available' => 'Dostupne',
        'chosen' => 'Odabrane',
    ],

    'login' => [
        'email' => 'E-pošta',
        'password' => 'Lozinka',
        'forgot_password' => 'Zaboravili ste lozinku?',
        'remember_me' => 'Zapamti me',
    ],

    'register' => [
        'first_name' => 'Ime',
        'last_name' => 'Prezime',
        'institution' => 'Institucija',
        'email' => 'E-pošta',
        'password' => 'Lozinka',
        'password_confirmation' => 'Ponovite lozinku',
        'verification_code' => 'Verifikacijski kod',
        'accept' => 'Slažem se sa <a href=":url" title="Politika privatnosti" target="_blank">Politikom privatnosti</a>',
    ],

    'forgot_password' => [
        'email' => 'E-pošta',
    ],

    'reset_password' => [
        'email' => 'Adresa e-pošte',
        'password' => 'Lozinka',
        'password_confirmation' => 'Potvrdite lozinku',
    ],

    'users' => [
        'first_name' => 'Ime',
        'last_name' => 'Prezime',
        'institution' => 'Institucija',
        'roles' => 'Uloge',
        'curated_taxa' => 'Svojte koje uređuje',
        'email' => 'E-pošta',
        'search' => 'Traži',
    ],

    'taxa' => [
        'rank' => 'Kategorija',
        'name' => 'Naziv',
        'parent' => 'Roditeljska svojta',
        'author' => 'Autor',
        'native_name' => 'Narodni naziv',
        'description' => 'Opis',
        'fe_old_id' => '(stara) FaunaEuropea ID',
        'fe_id' => 'FaunaEuropea ID',
        'restricted' => 'Svojta sa ograničenim podacima',
        'allochthonous' => 'Svojta je alohtona',
        'invasive' => 'Svojta je invazivna',
        'stages' => 'Stadiji',
        'conservation_legislations' => 'Zakonska zaštita',
        'conservation_documents' => 'Ostala dokumentacija',
        'red_lists' => 'Crveni popisi',
        'add_red_list' => 'Dodaj crveni popis',
        'search_for_taxon' => 'Traži svojtu...',
        'yes' => 'Da',
        'no' => 'Ne',
    ],

    'field_observations' => [
        'taxon' => 'Svojta',
        'original_identification' => 'Originalna identifikacija',
        'search_for_taxon' => 'Traži svojtu...',
        'date' => 'Datum',
        'year' => 'Godina',
        'month' => 'Mjesec',
        'day' => 'Dan',
        'photos' => 'Fotografije',
        'upload' => 'Učitaj',
        'map' => 'Karta',
        'latitude' => 'Geografska širina',
        'longitude' => 'Geografska dužina',
        'accuracy_m' => 'Preciznost/Polumjer (m)',
        'accuracy' => 'Preciznost',
        'elevation_m' => 'Nadmorska visina (m)',
        'elevation' => 'Nadmorska visina',
        'location' => 'Lokacija',
        'details' => 'Detalji',
        'more_details' => 'Više detalja',
        'less_details' => 'Manje detalja',
        'note' => 'Bilješka',
        'number' => 'Broj',
        'project' => 'Projekt',
        'project_tooltip' => 'Ako su podaci prikupljeni u okviru projekta, ovde upišite naziv/broj projekta.',
        'habitat' => 'Stanište',
        'found_on' => 'Nađeno na',
        'found_on_tooltip' => 'Možete popuniti ovo polje ako je vrsta nađena na domaćinu (npr. latinski naziv biljke hraniteljice gusjenice), izmetu (npr. izmet koze za skarabeje), strvini (za tvrdokrilce strvinare), itd.',
        'sex' => 'Spol',
        'stage' => 'Životni stadij',
        'time' => 'Vrijeme',
        'observer' => 'Opažač',
        'identifier' => 'Determinator',
        'found_dead' => 'Jedinka nađena mrtva?',
        'found_dead_note' => 'Bilješke o mrtvoj jedinki',
        'data_license' => 'Licenca podataka',
        'image_license' => 'Licenca slika',
        'default' => 'Zadano',
        'choose_a_stage' => 'Odaberite životni stadij',
        'choose_a_value' => 'Odaberite vrijednost',
        'click_to_select' => 'Kliknite kako biste odabrali...',
        'status' => 'Status',
        'types' => 'Tip nalaza',
        'types_placeholder' => 'Odaberite tip nalaza',
        'dataset' => 'Set podataka',
        'mgrs10k' => 'MGRS 10K',

        'male' => 'Mužjak',
        'female' => 'Ženka',

        'statuses' => [
            'approved' => 'Odobreno',
            'unidentifiable' => 'Nemoguća identifikacija',
            'pending' => 'Na čekanju',
        ],

        'save_tooltip' => 'Čuva trenutni nalaz i vraća vas u listu nalaza. Možete koristiti i prečicu Ctrl + Enter na tipkovnici.',
        'save_more_tooltip' => 'Čuva trenutni nalaz, ali vam omogućava da unesete još podataka s istog mjesta. Možete koristiti i prečicu Ctrl + Shift + Enter na tipkovnici.',

        'include_lower_taxa' => 'Uključujući niže svojte',

        'submitted_using' => 'Poslato preko',
    ],

    'view_groups' => [
        'name' => 'Naziv',
        'parent' => 'Viša grupa',
        'description' => 'Opis',
        'taxa' => 'Taksoni',
        'image' => 'Slika',
        'only_observed_taxa' => 'Samo opažene svojte',
    ],

    'exports' => [
        'title' => 'Izvoz',
        'processing' => 'Izvoz u toku... Ovo može potrajati.',
        'only_checked' => 'Izvezi samo čekirane',
        'apply_filters' => 'Primeni filtere',
        'with_header' => 'Sa nazivima kolona',
        'finished' => 'Gotovo! Možete preuzeti izvezen fajl.',
        'columns' => 'Kolone',
    ],

    'imports' => [
        'choose_columns' => 'Odaberi kolone',
        'select_csv_file' => 'Odaberi CSV fajl',
        'available' => 'Dostupne',
        'chosen' => 'Odabrane',
        'import' => 'Uvezi',
        'row_number' => 'Broj reda',
        'error' => 'Greška',
        'has_heading' => 'Prvi red sadrži nazive kolona',
        'columns' => 'Kolone',
        'user' => 'Za korisnika',
    ],

    'announcements' => [
        'title' => 'Naslov',
        'message' => 'Tekst',
        'private' => 'Samo za članove',
        'publish' => 'Objavi',
    ],
];
