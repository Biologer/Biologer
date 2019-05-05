<?php

return [
    'id' => 'ID',
    'actions' => 'Акције',
    'created_at' => 'Направљено',

    'tables' => [
        'from_to_total' => 'Приказује се :from-:to од укупно :total',
    ],

    'sexes' => [
        'male' => 'Мужјак',
        'female' => 'Женка',
    ],

    'transfer' => [
        'available' => 'Доступне',
        'chosen' => 'Орабране',
    ],

    'login' => [
        'email' => 'Е-пошта',
        'password' => 'Лозинка',
        'forgot_password' => 'Заборавили сте лозинку?',
        'remember_me' => 'Запамти ме',
    ],

    'register' => [
        'first_name' => 'Име',
        'last_name' => 'Презиме',
        'institution' => 'Институција',
        'email' => 'Е-пошта',
        'password' => 'Лозинка',
        'password_confirmation' => 'Поновите лозинку',
        'verification_code' => 'Верификациони код',
        'accept' => 'Слажем се са <a href=":url" title="Политика приватности" target="_blank">Политиком приватности</a>',
    ],

    'forgot_password' => [
        'email' => 'Е-пошта',
    ],

    'reset_password' => [
        'email' => 'Адреса е-поште',
        'password' => 'Лозинка',
        'password_confirmation' => 'Потврдите лозинку',
    ],

    'users' => [
        'first_name' => 'Име',
        'last_name' => 'Презиме',
        'institution' => 'Институција',
        'roles' => 'Улоге',
        'curated_taxa' => 'Таксони које уређује',
        'email' => 'Е-пошта',
        'search' => 'Тражи',
    ],

    'taxa' => [
        'rank' => 'Таксономска категорија',
        'name' => 'Назив',
        'parent' => 'Родитељски таксон',
        'author' => 'Аутор',
        'native_name' => 'Народни назив',
        'description' => 'Опис',
        'fe_old_id' => '(стара) FaunaEuropea ID',
        'fe_id' => 'FaunaEuropea ID',
        'restricted' => 'Tаксон са органиченим подацима',
        'allochthonous' => 'Таксон је алохтон',
        'invasive' => 'Таксон је инвазиван',
        'stages' => 'Стадијуми',
        'conservation_legislations' => 'Законска заштита',
        'conservation_documents' => 'Остала документа',
        'red_lists' => 'Црвене листе',
        'add_red_list' => 'Додај црвену листу',
        'search_for_taxon' => 'Тражи таксон...',
    ],

    'field_observations' => [
        'taxon' => 'Таксон',
        'original_identification' => 'Оригинална идентификација',
        'search_for_taxon' => 'Тражи таксон...',
        'date' => 'Датум',
        'year' => 'Година',
        'month' => 'Месец',
        'day' => 'Дан',
        'photos' => 'Фотографије',
        'upload' => 'Отпреми',
        'map' => 'Мапа',
        'latitude' => 'Географска ширина',
        'longitude' => 'Географска дужина',
        'accuracy_m' => 'Прецизност/Полупречник (m)',
        'accuracy' => 'Прецизност',
        'elevation_m' => 'Надморска висина (m)',
        'elevation' => 'Надморска висина',
        'location' => 'Локација',
        'details' => 'Детаљи',
        'more_details' => 'Више детаља',
        'less_details' => 'Мање детаља',
        'note' => 'Белешка',
        'number' => 'Број',
        'project' => 'Пројекат',
        'project_tooltip' => 'Ако су подаци прикупљени у оквиру пројекта, овде упишите назив/број пројекта.',
        'habitat' => 'Станиште',
        'found_on' => 'Нађено на',
        'found_on_tooltip' => 'Можете попунити ово поље ако је врста нађена на домаћину (нпр. латински назив биљке хранитељке гусенице), измет (нпр. измет козе за скарабеје), стрвина (за тврдокрилце стрвинаре), итд.',
        'sex' => 'Пол',
        'stage' => 'Стадијум',
        'time' => 'Време',
        'observer' => 'Уочио',
        'identifier' => 'Идентификовао',
        'found_dead' => 'Јединка нађена мртва?',
        'found_dead_note' => 'Белешке о мртвој јединки',
        'data_license' => 'Лиценца податка',
        'image_license' => 'Лиценца слика',
        'default' => 'Подразумевано',
        'choose_a_stage' => 'Одаберите стадијум',
        'choose_a_value' => 'Одаберите вредност',
        'click_to_select' => 'Кликнитекако бисте одабрали...',
        'status' => 'Статус',
        'types' => 'Тип налаза',
        'types_placeholder' => 'Одаберите тип налаза',
        'dataset' => 'Сет података',
        'mgrs10k' => 'MGRS 10K',

        'male' => 'Мужјак',
        'female' => 'Женка',

        'statuses' => [
            'approved' => 'Одобрено',
            'unidentifiable' => 'Немогућа идентификација',
            'pending' => 'На чекању',
        ],

        'save_tooltip' => 'Чува тренутни налаз и враћа вас у листу  налаза. Можете користити и пречицу Ctrl+Enter на тастатури.',
        'save_more_tooltip' => 'Чува тренутни налаз, али вам омогућава да унесете још података са истог места. Можете користити и пречицу Ctrl+Shift+Enter на тастатури.',

        'include_lower_taxa' => 'Укључујући ниже таксоне',

        'submitted_using' => 'Послато преко',
    ],

    'view_groups' => [
        'name' => 'Назив',
        'parent' => 'Виша група',
        'description' => 'Опис',
        'taxa' => 'Таксони',
        'image' => 'Слика',
        'only_observed_taxa' => 'Само опажени таксони',
    ],

    'exports' => [
        'title' => 'Извоз',
        'processing' => 'Извоз у току... Ово може потрајати.',
        'only_checked' => 'Извези само чекиране',
        'apply_filters' => 'Примени филтере',
        'with_header' => 'Са називима колона',
        'finished' => 'Готово! Можете преузети извезену датотеку.',
        'columns' => 'Колоне',
    ],

    'imports' => [
        'choose_columns' => 'Одабери колоне',
        'select_csv_file' => 'Одабери CSV датотеку',
        'available' => 'Доступне',
        'chosen' => 'Одабране',
        'import' => 'Увези',
        'row_number' => 'Број реда',
        'error' => 'Грешка',
        'has_heading' => 'Први ред садржи називе колона',
        'columns' => 'Колоне',
        'user' => 'За корисника',
    ],

    'announcements' => [
        'title' => 'Наслов',
        'message' => 'Текст',
        'private' => 'Само за чланове',
        'publish' => 'Објави',
    ],
];
