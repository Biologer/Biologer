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
        'email_confirmation' => 'Ponovite e-poštu',
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
        'full_name' => 'Ime i prezime',
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
        'ancestors_names' => 'Nazivi predaka',
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

        'include_lower_taxa' => 'Uključujući niže svojte',

        'atlas_codes' => 'Atlas kodovi',
        'uses_atlas_codes' => 'Koristi Atlas kodove',

        'synonyms' => 'Sinonimi',
        'add_synonym' => 'Dodaj sinonim',
        'synonym_name' => 'Unesi naziv sinonima',
        'synonym_author' => 'Unesi autora sinonima',
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
        'found_on_tooltip' => 'Možete popuniti ovo polje ako je vrsta nađena na domaćinu (npr. latinski naziv biljke hraniteljice gusjenice), izmetu (npr. izmet koze za skarabeje), strvini (za tvrdokrilce strvinare), supstrat, itd.',
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
        'atlas_code' => 'Atlas kod',

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
        'types' => [
            'custom' => 'Prolagođeno',
            'darwin_core' => 'Darwin Core',
        ],
        'group_name' => 'Naziv grupe',
        'group_ids' => 'ID Grupa',
        'stage_names' => 'Životni stadij',
        'stage_ids' => 'ID Životni stadij',
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
        'approve_curated' => 'Potvrdi nalaze za svojte koje uređujem',
    ],

    'announcements' => [
        'title' => 'Naslov',
        'message' => 'Tekst',
        'private' => 'Samo za članove',
        'publish' => 'Objavi',
    ],

    'publications' => [
        'type' => 'Tip publikacije',
        'name' => 'Naziv',
        'symposium_name' => 'Naziv simpozijuma',
        'book_chapter_name' => 'Naziv knjige',
        'paper_name' => 'Naziv časopisa',
        'title' => 'Naslov',
        'year' => 'Godina',
        'issue' => 'Broj/Izdanje',
        'publisher' => 'Izdavač',
        'place' => 'Mesto izdavanja',
        'page_count' => 'Broj stranica',
        'page_range' => 'Od-do stranice',
        'authors' => 'Autori',
        'editors' => 'Urednici',
        'attachment' => 'Prilog',
        'link' => 'Link',
        'doi' => 'DOI',
        'citation' => 'Citiranje',
        'citation_tooltip' => 'Ovo polje će se samo generisati ako ostane prazno',
        'add_author' => 'Dodaj autora',
        'add_editor' => 'Dodaj urednika',
        'first_name' => 'Ime',
        'last_name' => 'Prezime',

        'search' => 'Traži',
    ],

    'literature_observations' => [
        'publication' => 'Publikacija',
        'is_original_data' => 'Podatak izvorno iz ove publikacije?',
        'original_data' => 'Originalni podatak',
        'citation' => 'Citiranje',
        'cited_publication' => 'Citirana publikacija',
        'search_for_publication' => 'Traži publikaciju',
        'taxon' => 'Svojta',
        'search_for_taxon' => 'Traži svojtu',
        'date' => 'Datum',
        'year' => 'Godina',
        'month' => 'Mjesec',
        'day' => 'Dan',
        'latitude' => 'Geografska širina',
        'longitude' => 'Geografska dužina',
        'mgrs10k' => 'MGRS 10k',
        'accuracy' => 'Preciznost',
        'accuracy_m' => 'Preciznost (m)',
        'location' => 'Lokacija',
        'elevation' => 'Nadmorska visina',
        'elevation_m' => 'Nadmorska visina (m)',
        'minimum_elevation' => 'Minimalna nadmorska visina',
        'minimum_elevation_m' => 'Minimalna nadmorska visina (m)',
        'maximum_elevation' => 'Maksimalna nadmorska visina',
        'maximum_elevation_m' => 'Maksimalna nadmorska visina (m)',
        'stage' => 'Životni stadij',
        'choose_a_stage' => 'Odaberite životni stadij',
        'sex' => 'Spol',
        'choose_a_value' => 'Odaberite vrijednost',
        'number' => 'Broj',
        'note' => 'Bilješka',
        'habitat' => 'Stanište',
        'found_on' => 'Nađeno na',
        'found_on_tooltip' => 'Možete popuniti ovo polje ako je vrsta nađena na domaćinu (npr. latinski naziv biljke hraniteljice gusenice), izmet (npr. izmet koze za skarabeje), strvina (za tvrdokrilce strvinare), supstrat, itd.',
        'time' => 'Vrijeme nalaza',
        'click_to_select' => 'Kliknite da odaberete',
        'project' => 'Projekt',
        'project_tooltip' => 'Ako su podaci prikupljeni u okviru projekta, ovde upišite naziv/broj projekta.',
        'dataset' => 'Set podataka',
        'observer' => 'Opažaš',
        'identifier' => 'Determinator',
        'original_date' => 'Originalni datum',
        'original_locality' => 'Originalni lokalitet',
        'original_coordinates' => 'Originalne koordinate',
        'original_elevation' => 'Originalna nadmorska visina',
        'original_elevation_placeholder' => 'npr. 100-200m',
        'original_identification' => 'Originalna identifikacija',
        'original_identification_validity' => 'Validnost originalne identifikacije',
        'other_original_data' => 'Ostali originalni podaci',
        'collecting_start_year' => 'Godina početka sakupljanja',
        'collecting_start_month' => 'Mesec početka sakupljanja',
        'collecting_end_year' => 'Godina kraja sakupljanja',
        'collecting_end_month' => 'Mesec kraja sakupljanja',
        'place_where_referenced_in_publication' => 'Mesto gde je navdeno u publikaciji',
        'place_where_referenced_in_publication_placeholder' => 'npr. Stranica 45, Slika 4 ili Tabela 3',
        'georeferenced_by' => 'Georeferencirao',
        'georeferenced_date' => 'Datum georeferenciranja',

        'add_new_publication' => 'Dodajte novu publikaciju',

        'verbatim_data' => 'Podaci kako su navedeni u publikaciji',

        'validity' => [
            'invalid' => 'Neispravna',
            'valid' => 'Ispravna',
            'synonym' => 'Sinonim',
        ],

        'save_tooltip' => 'Čuva trenutni nalaz i vraća vas u listu nalaza. Možete koristiti i prečicu Ctrl+Enter na tipkovnici.',
        'save_more_tooltip' => 'Čuva trenutni nalaz, ali vam omogućava da unesete još podataka sa istog mjesta. Možete koristiti i prečicu Ctrl+Shift+Enter na tipkovnici.',

        'save_more_same_taxon' => 'Save (još, ista svojta)',
        'save_more_same_taxon_tooltip' => 'Čuva trenutni nalaz, ali vam omogućava da unesete još podataka sa istog mjesta i za istu svojtu.',

        'include_lower_taxa' => 'Uključujući niže svojte',
    ],

    'preferences' => [
        'account' => [
            'delete_account' => 'Obriši nalog',
            'delete_observations' => 'Obriši i unete nalaze',
        ],

        'notifications' => [
            'notification' => 'Notifikacija',
            'inapp' => 'Na sajtu',
            'mail' => 'Email',

            'field_observation_approved' => 'Nalaz je odobren',
            'field_observation_edited' => 'Nalaz je izmjenjen',
            'field_observation_moved_to_pending' => 'Nalaz je stavljen na čekanje',
            'field_observation_marked_unidentifiable' => 'Nalaz je označen kao da nije moguća identifikacija',
            'field_observation_for_approval' => 'Nov nalaz za pregled',
        ],

        'data_entry' => [
            'default_stage_adult' => 'Koristi "Odrasla jedinka" kao podrazumjevani stadij ako je dostupan za takson.',
        ],
    ],
];
