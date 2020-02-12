<?php

return [
    'id' => 'ID',
    'actions' => 'Actions',
    'created_at' => 'Created At',

    'tables' => [
        'from_to_total' => 'Showing :from-:to of :total',
    ],

    'sexes' => [
        'male' => 'Male',
        'female' => 'Female',
    ],

    'transfer' => [
        'available' => 'Available',
        'chosen' => 'Chosen',
    ],

    'login' => [
        'email' => 'Email',
        'password' => 'Password',
        'forgot_password' => 'Forgot password?',
        'remember_me' => 'Remember me',
    ],

    'register' => [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'institution' => 'Institution',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Repeat Password',
        'verification_code' => 'Verification Code',
        'accept' => 'I agree with the <a href=":url" title="Privacy Policy" target="_blank">Privacy Policy</a>',
    ],

    'forgot_password' => [
        'email' => 'Email',
    ],

    'reset_password' => [
        'email' => 'E-Mail Address',
        'password' => 'Password',
        'password_confirmation' => 'Confirm Password',
    ],

    'users' => [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'institution' => 'Institution',
        'roles' => 'Roles',
        'curated_taxa' => 'Curated Taxa',
        'email' => 'Email',
        'search' => 'Search',
    ],

    'taxa' => [
        'rank' => 'Rank',
        'name' => 'Name',
        'parent' => 'Parent',
        'author' => 'Author',
        'native_name' => 'Native Name',
        'description' => 'Description',
        'fe_old_id' => '(old) FaunaEuropea ID',
        'fe_id' => 'FaunaEuropea ID',
        'restricted' => 'Taxon data is restricted',
        'allochthonous' => 'Taxin is allochthonous',
        'invasive' => 'Taxon is invasive',
        'stages' => 'Stages',
        'conservation_legislations' => 'Conservation Legislations',
        'conservation_documents' => 'Other Conservation Documents',
        'red_lists' => 'Red Lists',
        'add_red_list' => 'Add red list',
        'search_for_taxon' => 'Search for taxon...',
        'yes' => 'Yes',
        'no' => 'No',

        'include_lower_taxa' => 'Include lower taxa',
    ],

    'field_observations' => [
        'taxon' => 'Taxon',
        'original_identification' => 'Original Identification',
        'search_for_taxon' => 'Search for taxon...',
        'date' => 'Date',
        'year' => 'Year',
        'month' => 'Month',
        'day' => 'Day',
        'photos' => 'Photos',
        'upload' => 'Upload',
        'map' => 'Map',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'accuracy_m' => 'Accuracy/Radius (m)',
        'accuracy' => 'Accuracy',
        'elevation_m' => 'Elevation (m)',
        'elevation' => 'Elevation',
        'location' => 'Location',
        'details' => 'Details',
        'more_details' => 'More Details',
        'less_details' => 'Less Details',
        'note' => 'Note',
        'number' => 'Number',
        'project' => 'Project',
        'project_tooltip' => 'If the data is gathered in the course of a project write the project name/number here.',
        'habitat' => 'Habitat',
        'found_on' => 'Found On',
        'found_on_tooltip' => 'You can fill this field if the species is observed on a host (i.e. latin name of the caterpillar host plant), dung (i.e. goat dung for scarabs), carrion (for carrion beetles), etc.',
        'sex' => 'Sex',
        'stage' => 'Stage',
        'time' => 'Time',
        'observer' => 'Observer',
        'identifier' => 'Identifier',
        'found_dead' => 'Found dead?',
        'found_dead_note' => 'Note on dead observation',
        'data_license' => 'Data License',
        'image_license' => 'Image License',
        'default' => 'Default',
        'choose_a_stage' => 'Choose a stage',
        'choose_a_value' => 'Choose a value',
        'click_to_select' => 'Click to select...',
        'status' => 'Status',
        'types' => 'Observation Type',
        'types_placeholder' => 'Select Observation Type',
        'dataset' => 'Dataset',
        'mgrs10k' => 'MGRS 10K',

        'statuses' => [
            'approved' => 'Approved',
            'unidentifiable' => 'Unidentifiable',
            'pending' => 'Pending',
        ],

        'save_tooltip' => 'Saves current observation and returns you to the list of your records. You can also use keyboard shortcut: Ctrl+Enter.',
        'save_more_tooltip' => 'Saves current observations, but allows you to enter more data from the same place. You can also use keyboard shortcut: Ctrl+Shift+Enter.',

        'include_lower_taxa' => 'Include lower taxa',

        'submitted_using' => 'Submitted Using',
    ],

    'view_groups' => [
        'name' => 'Name',
        'parent' => 'Parent',
        'description' => 'Description',
        'taxa' => 'Taxa',
        'image' => 'Image',
        'only_observed_taxa' => 'Only observed taxa',
    ],

    'exports' => [
        'title' => 'Export',
        'processing' => 'Exporting... This may take a while.',
        'only_checked' => 'Only export checked',
        'apply_filters' => 'Apply filters',
        'with_header' => 'With header',
        'finished' => 'Finished! You can now download you export.',
        'columns' => 'Columns',
        'types' => [
            'custom' => 'Custom',
            'darwin_core' => 'Darwin Core',
        ],
    ],

    'imports' => [
        'choose_columns' => 'Choose Columns',
        'select_csv_file' => 'Select CSV file',
        'available' => 'Available',
        'chosen' => 'Chosen',
        'import' => 'Import',
        'row_number' => 'Row Number',
        'error' => 'Error',
        'has_heading' => 'First row contains column titles',
        'columns' => 'Columns',
        'user' => 'For User',
        'approve_curated' => 'Approve Curated',
    ],

    'announcements' => [
        'title' => 'Title',
        'message' => 'Message',
        'private' => 'Private',
        'publish' => 'Publish',
    ],

    'publications' => [
        'type' => 'Type of Publication',
        'name' => 'Name',
        'symposium_name' => 'Symposium Name',
        'book_chapter_name' => 'Book Name',
        'paper_name' => 'Journal Title',
        'title' => 'Title',
        'year' => 'Year',
        'issue' => 'Issue',
        'publisher' => 'Publisher',
        'place' => 'Place',
        'page_count' => 'Page Count',
        'page_range' => 'Page Range',
        'authors' => 'Authors',
        'editors' => 'Editors',
        'attachment' => 'Attachment',
        'link' => 'Link',
        'doi' => 'DOI',
        'citation' => 'Citation',
        'citation_tooltip' => 'It will be auto-generated if left empty',
        'add_author' => 'Add Author',
        'add_editor' => 'Add Editor',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',

        'search' => 'Search',
    ],

    'literature_observations' => [
        'publication' => 'Publication',
        'is_original_data' => 'Is Original Data?',
        'original_data' => 'Original Data',
        'citation' => 'Citation',
        'cited_publication' => 'Cited Publication',
        'search_for_publication' => 'Search for publication',
        'taxon' => 'Taxon',
        'search_for_taxon' => 'Search for taxon',
        'date' => 'Date',
        'year' => 'Year',
        'month' => 'Month',
        'day' => 'Day',
        'elevation_m' => 'Elevation (m)',
        'elevation' => 'Elevation',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'mgrs10k' => 'MGRS 10k',
        'accuracy' => 'Accuracy',
        'accuracy_m' => 'Accuracy (m)',
        'location' => 'Location',
        'minimum_elevation' => 'Minimum Elevation',
        'minimum_elevation_m' => 'Minimum Elevation (m)',
        'maximum_elevation' => 'Maximum Elevation',
        'maximum_elevation_m' => 'Maximum Elevation (m)',
        'stage' => 'Stage',
        'choose_a_stage' => 'Choose a stage',
        'sex' => 'Sex',
        'choose_a_value' => 'Choose a value',
        'number' => 'Number',
        'note' => 'Note',
        'habitat' => 'Habitat',
        'found_on' => 'Found On',
        'found_on_tooltip' => 'You can fill this field if the species is observed on a host (i.e. latin name of the caterpillar host plant), dung (i.e. goat dung for scarabs), carrion (for carrion beetles), etc.',
        'time' => 'Time',
        'click_to_select' => 'Click to select',
        'project' => 'Project',
        'project_tooltip' => 'If the data is gathered in the course of a project write the project name/number here.',
        'dataset' => 'Dataset',
        'observer' => 'Observer',
        'identifier' => 'Identifier',
        'original_date' => 'Original Date',
        'original_locality' => 'Original Locality',
        'original_coordinates' => 'Original Coordinates',
        'original_elevation' => 'Original Elevation',
        'original_elevation_placeholder' => 'f.e. 100-200m',
        'original_identification' => 'Original Identification',
        'original_identification_validity' => 'Original Identification Validity',
        'other_original_data' => 'Other Original Data',
        'collecting_start_year' => 'Collecting Start Year',
        'collecting_start_month' => 'Collecting Start Month',
        'collecting_end_year' => 'Collecting End Year',
        'collecting_end_month' => 'Collecting End Month',
        'place_where_referenced_in_publication' => 'Place of Reference in Publication',
        'place_where_referenced_in_publication_placeholder' => 'i.e. Page 45, Figure 4 or Table 3',
        'georeferenced_by' => 'Georeferenced By',
        'georeferenced_date' => 'Georeferenced on Date',

        'add_new_publication' => 'Add New Publication',

        'verbatim_data' => 'Verbatim Data',

        'validity' => [
            'invalid' => 'Invalid',
            'valid' => 'Valid',
            'synonym' => 'Synonym',
        ],

        'save_tooltip' => 'Saves current observation and returns you to the list of records. You can also use keyboard shortcut: Ctrl+Enter.',
        'save_more_tooltip' => 'Saves current observations, but allows you to enter more data from the same place. You can also use keyboard shortcut: Ctrl+Shift+Enter.',

        'save_more_same_taxon' => 'Save (more, same taxon)',
        'save_more_same_taxon_tooltip' => 'Saves current observations, but allows you to enter more data from the same place and for the same taxon.',

        'include_lower_taxa' => 'Include lower taxa',
    ],

    'specimen_collections' => [
        'name' => 'Name',
        'code' => 'Code',
        'institution_name' => 'Institution Name',
        'institution_code' => 'Institution Code',
    ],

    'collection_observations' => [
        'collection' => 'Collection',
        'search_for_collection' => 'Search for collection',
        'taxon' => 'Taxon',
        'search_for_taxon' => 'Search for taxon',
        'date' => 'Date',
        'year' => 'Year',
        'month' => 'Month',
        'day' => 'Day',
        'elevation_m' => 'Elevation (m)',
        'elevation' => 'Elevation',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'mgrs10k' => 'MGRS 10k',
        'accuracy' => 'Accuracy',
        'accuracy_m' => 'Accuracy (m)',
        'location' => 'Location',
        'minimum_elevation' => 'Minimum Elevation',
        'minimum_elevation_m' => 'Minimum Elevation (m)',
        'maximum_elevation' => 'Maximum Elevation',
        'maximum_elevation_m' => 'Maximum Elevation (m)',
        'stage' => 'Stage',
        'choose_a_stage' => 'Choose a stage',
        'sex' => 'Sex',
        'choose_a_value' => 'Choose a value',
        'number' => 'Number',
        'note' => 'Note',
        'habitat' => 'Habitat',
        'found_on' => 'Found On',
        'found_on_tooltip' => 'You can fill this field if the species is observed on a host (i.e. latin name of the caterpillar host plant), dung (i.e. goat dung for scarabs), carrion (for carrion beetles), etc.',
        'time' => 'Time',
        'click_to_select' => 'Click to select',
        'project' => 'Project',
        'project_tooltip' => 'If the data is gathered in the course of a project write the project name/number here.',
        'dataset' => 'Dataset',
        'observer' => 'Observer',
        'identifier' => 'Identifier',
        'original_date' => 'Original Date',
        'original_locality' => 'Original Locality',
        'original_coordinates' => 'Original Coordinates',
        'original_elevation' => 'Original Elevation',
        'original_elevation_placeholder' => 'f.e. 100-200m',
        'original_identification' => 'Original Identification',
        'original_identification_validity' => 'Original Identification Validity',
        'other_original_data' => 'Other Original Data',
        'collecting_start_year' => 'Collecting Start Year',
        'collecting_start_month' => 'Collecting Start Month',
        'collecting_end_year' => 'Collecting End Year',
        'collecting_end_month' => 'Collecting End Month',
        'place_where_referenced_in_publication' => 'Place of Reference in Publication',
        'place_where_referenced_in_publication_placeholder' => 'i.e. Page 45, Figure 4 or Table 3',
        'georeferenced_by' => 'Georeferenced By',
        'georeferenced_date' => 'Georeferenced on Date',

        'add_new_collection' => 'Add New Collection',

        'verbatim_data' => 'Verbatim Data',

        'validity' => [
            'invalid' => 'Invalid',
            'valid' => 'Valid',
            'synonym' => 'Synonym',
        ],

        'save_tooltip' => 'Saves current observation and returns you to the list of records. You can also use keyboard shortcut: Ctrl+Enter.',
        'save_more_tooltip' => 'Saves current observations, but allows you to enter more data from the same place. You can also use keyboard shortcut: Ctrl+Shift+Enter.',

        'save_more_same_taxon' => 'Save (more, same taxon)',
        'save_more_same_taxon_tooltip' => 'Saves current observations, but allows you to enter more data from the same place and for the same taxon.',

        'include_lower_taxa' => 'Include lower taxa',
    ],

    'preferences' => [
        'general' => [
            'locale' => 'Preferred locale',
        ],

        'account' => [
            'delete_account' => 'Delete Account',
            'delete_observations' => 'Delete observations as well',
        ],
        'notifications' => [
            'notification' => 'Notification',
            'inapp' => 'In App',
            'mail' => 'Mail',

            'field_observation_approved' => 'Observation has been approved',
            'field_observation_edited' => 'Observation has been edited',
            'field_observation_moved_to_pending' => 'Observation has been moved to pending',
            'field_observation_marked_unidentifiable' => 'Observation has been marked as unidentifiable',
            'field_observation_for_approval' => 'New observation for approval',
        ],
    ],
];
