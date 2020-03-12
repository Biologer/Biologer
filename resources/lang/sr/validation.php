<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted' => 'Мора бити прихваћено.',
    'active_url' => 'Није валидан URL.',
    'after' => 'Мора бити датум после :date.',
    'after_or_equal' => 'Мора бити датум након или једнак :date.',
    'alpha' => 'Може садржати само слова.',
    'alpha_dash' => 'Може садржати само слова, бројеве и повлаке.',
    'alpha_num' => 'Може садржати само слова и бројеве.',
    'array' => 'Мора садржати неки низ ставки.',
    'before' => 'Мора бити датум пре :date.',
    'before_or_equal' => 'Мора бити датум пре или једнак :date.',
    'between' => [
        'numeric' => 'Мора бити између :min - :max.',
        'file' => 'Фајл мора бити величинеизмеђу :min - :max килобајта.',
        'string' => 'Мора бити између :min - :max карактера.',
        'array' => 'Мора бити између :min - :max ставки.',
    ],
    'boolean' => 'Мора бити тачно или нетачно',
    'confirmed' => 'Потврда поља attribute се не поклапа.',
    'date' => 'Није важећи датум.',
    'date_format' => 'Не одговара према формату :format.',
    'different' => 'Поља :attribute и :other морају бити различита.',
    'digits' => 'Поље :attribute мора садршату :digits шифри.',
    'digits_between' => 'Поље :attribute мора бити између :min и :max шифри.',
    'dimensions' => ':attribute нема исправне димензије слике.',
    'distinct' => 'Поље :attribute има дуплих вредности.',
    'email' => 'Формат поља :attribute није валидан.',
    'exists' => 'Одабрано поље :attribute није валидно.',
    'file' => ':attribute мора бити фајл.',
    'filled' => 'Поље :attribute не сме бити празно.',
    'gt' => [
        'numeric' => 'Поље :attribute мора бити веће од :value.',
        'file' => 'Поље :attribute мора бити веће od :value килобајта.',
        'string' => 'Поље :attribute мора бити дуже од :value знакова.',
        'array' => 'Поље :attribute мора садржати више од :value ставки.',
    ],
    'gte' => [
        'numeric' => 'Поље :attribute мора бити веће или једнако :value.',
        'file' => 'Поље :attribute мора бити веће или једнако :value килобајта.',
        'string' => 'Поље :attribute мора бити дуже или једнако :value знакова.',
        'array' => 'Поље :attribute мора садржати више од :value ставки или исти број ставки.',
    ],
    'image' => 'Поље :attribute мора бити слика.',
    'in' => 'Одабрано поље :attribute није валидно.',
    'in_array' => 'Вредност поља :attribute није садржана у :other.',
    'integer' => 'Поље :attribute мора бити број.',
    'ip' => 'Поље :attribute мора бити валидна IP адреса.',
    'ipv4' => 'Поље :attributeмора бити валидна IPv4 адреса.',
    'ipv6' => 'Поље :attribute мора бити валидна IPv6 адреса.',
    'json' => 'Поље :attribute мора бити валидан JSON.',
    'lt' => [
        'numeric' => 'Поље :attribute мора бити мање од :value.',
        'file' => 'Поље :attribute мора бити мање od :value килобајта.',
        'string' => 'Поље :attribute мора бити краће од :value знакова.',
        'array' => 'Поље :attribute мора садржати више од :value ставки.',
    ],
    'lte' => [
        'numeric' => 'Поље :attribute мора бити мање или једнако :value.',
        'file' => 'Поље :attribute мора бити мање или једнако :value килобајта.',
        'string' => 'Поље :attribute мора бити краће или једнако :value знакова.',
        'array' => 'Поље :attribute мора садржати мање од :value ставки или исти број ставки.',
    ],
    'max' => [
        'numeric' => 'Поље :attribute мора бити мање од :max.',
        'file' => 'Фајл :attribute мора бити мањи од :max килобајта.',
        'string' => 'Поље :attribute мора садржати мање од :max карактера.',
        'array' => 'Поље :attribute не сме имати више од :max ставки.',
    ],
    'mimes' => 'Поље :attribute мора бити фајл типа: :values.',
    'mimetypes' => 'Поље :attribute мора бити фајл типа: :values.',
    'min' => [
        'numeric' => 'Поље :attribute мора бити најмање :min.',
        'file' => 'Фајл :attribute мора бити велик најмање :min килобајта.',
        'string' => 'Поље :attribute мора садрзати најмање :min карактера.',
        'array' => 'Поље :attribute мора садржати најмање :min ставки.',
    ],
    'not_in' => 'Одабрани елемент поља :attribute није валидан.',
    'numeric' => 'Поље :attribute мора бити број.',
    'password' => 'Лозинка није исправна.',
    'present' => 'Поље :attribute мора бити присутно.',
    'regex' => 'Формат поља :attribute није валидан.',
    'required' => 'Поље :attribute је обавезно.',
    'required_if' => 'Поље :attribute је обавезно када поље :other садржи :value.',
    'required_unless' => 'Поље :attribute је обавезно када поље :other не задрже :values.',
    'required_with' => 'Поље :attribute је обавезно када је :values присутан.',
    'required_with_all' => 'Поље :attribute је обавезно када је :values приказано.',
    'required_without' => 'Поље :attribute је потребно када поље :values није присутан.',
    'required_without_all' => 'Поље :attribute је потребно када ниједно од следећих поља :values није присутно.',
    'same' => 'Поља :attribute и :other се морају поклапати.',
    'size' => [
        'numeric' => 'Поље :attribute Мора бити :size.',
        'file' => 'Фајл :attribute мора бити величине :size килобајта.',
        'string' => 'Поље :attribute мора имати :size карактера.',
        'array' => 'Поље :attribute мора садржати :size ставки.',
    ],
    'string' => 'Поље :attribute мора садржати слова.',
    'timezone' => 'Поље :attribute мора бити исправна времеска зона.',
    'unique' => ':attribute већ постоји.',
    'uploaded' => ':attribute није отпремљен.',
    'url' => 'Формат поља :attribute није исправан.',

    'captcha' => 'Поље :attribute мора бити валидан CAPTCHA код.',

    'photo_max' => 'Величина фајла фотографије не сме бити већа од :size.',

    'field_observation_cannot_be_approved' => 'Неки теренски налази не могу бити одобрени.',
    'day' => 'Дан није исправан.',
    'month' => 'Месец није исправан.',
    'year' => 'Година није исправна.',
    'decimal' => 'Поље :attribute мора бити децимални број.',
    'contains_non_empty' => 'Поље :attribute мора садржати барем једну попуњену опцију.',
    'unique_taxon_name' => 'Име таксона мора бити јединствено у оквиру главних група организама из живог света.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'in_extended' => 'Поље :attribute није валидно. Дозвољене вредности су: :options',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
    ],
];
