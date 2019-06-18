<?php

return [
    'home' => [
        'browse' => 'Browse data',
        'android_link' => 'Android App',
        'android_title' => 'Download Android App',
        'welcome' => 'Biologer is simple and free software designed for collecting data on biological diversity.',
        'stats' => 'Community ":community" has :userCount users that have submitted :observationCount observations',

        'announcements' => [
            'title' => 'Latest news',
            'see_all' => 'See all',
        ],
    ],

    'announcements' => [
        'title' => 'Announcements',
        'no_announcements' => 'No announcements',
        'read' => 'Read news',
    ],

    'field_observations_import' => [
        'short_info' => 'If you would like to import data from spreadsheet, it '.
            'must be saved as CSV file. After selecting the file, you need to '.
            'reorder the columns in Biologer so that it matches the order in the table '.
            'and to actually select which columns you would like to import. The list '.
            'of taxa should follow the taxonomy of Biologer and the list of values for '.
            'each column (eg. stages, sex, license) must be given based on the values in English.',
    ],

    'taxa' => [
        'observations' => 'observation|observations|observations',

        'number_of_observations_per_mgrs10k_field' => 'Number of observations per MGRS 10k field',
        'mgrs10k_field' => 'MGRS 10k Field',
        'number_of_observations' => 'Number of Observations',
        'present_in_literature' => 'Present in literature',
    ],

    'stats' => [
        'user' => 'User',
        'curator' => 'Curator',
        'observations_count' => 'Observations Count',
        'identifications_count' => 'Identifications Count',
        'year' => 'Year',
        'group' => 'Group',
        'top_10_users' => 'Top 10 Users',
        'top_10_curators' => 'Top 10 Curators',
        'observations_count_by_group' => 'Observations Count by Group',
        'observations_count_by_year' => 'Observations Count by Year (for last 10 years)',
    ],
];
