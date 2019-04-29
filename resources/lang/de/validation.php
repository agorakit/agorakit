<?php
return [
    'accepted'             => 'Die :attribute muss gültig sein.',
    'active_url'           => 'Die :attribute ist keine gültige URL.',
    'after'                => 'Das :attribute muss ein Datum nach dem :date sein.',
    'alpha'                => 'Das :attribute darf nur Buchstaben enthalten.',
    'alpha_dash'           => 'Das :attribute darf nur Buchstaben, Ziffern und Bindestriche enthalten.',
    'alpha_num'            => 'Das :attribute darf nur Buchstaben und Ziffern enthalten.',
    'array'                => 'Die :attribute muss eine Array sein.',
    'before'               => 'Das :attribute muss ein Datum vor dem :date sein.',
    'between'              => [
        'array'   => 'Die :attribute muss zwischen :min und :max Einträge enthalten.',
        'file'    => 'Die :attribute muss zwischen :min und :max KB groß sein.',
        'numeric' => 'Die :attribute muss zwischen :min und :max sein.',
        'string'  => 'Die :attribute muss zwischen :min und :max Zeichen sein.'
    ],
    'boolean'              => 'Das :attribute/-Feld muss True oder False sein.',
    'confirmed'            => 'Die :attribute/-Bestätigung stimmt nicht überein.',
    'date'                 => 'Das :attribute ist kein gültiges Datum.',
    'date_format'          => 'Das :attribute stimmt nicht mit dem :format/-überein.',
    'different'            => 'Das :attribute und :other müssen unterschiedlich sein.',
    'digits'               => 'Das :attribute muss :digits Zeichen lang sein.',
    'digits_between'       => 'Das :attribute muss zwischen :min und :max Zeichen lang sein.',
    'distinct'             => 'Getrennt',
    'email'                => 'Die :attribute muss eine gültige E-Mail-Adresse sein.',
    'exists'               => 'Die ausgewählte :attribute ist nicht gültig.',
    'filled'               => 'Das :attribute/-Feld ist erforderlich.',
    'image'                => 'Die :attribute muss ein Bild sein.',
    'in'                   => 'Die ausgewählte :attribute ist ungültig.',
    'in_array'             => 'In Array',
    'integer'              => 'Die :attribute muss eine Ganzzahl sein.',
    'ip'                   => 'Die :attribute muss eine gültige IP-Adresse sein.',
    'json'                 => 'Der :attribute muss ein gültiger JSON-string sein.',
    'max'                  => [
        'array'   => 'Die :attribute darf nicht mehr als :max Einträge enthalten.',
        'file'    => 'Die :attribute darf nicht mehr als :max KB groß sein.',
        'numeric' => 'Die :attribute darf nicht größer als :max sein.',
        'string'  => 'Die :attribute darf nicht länger als :max Zeichen sein.'
    ],
    'mimes'                => 'Die :attribute muss eine Datei im :values/-Format sein.',
    'min'                  => [
        'array'   => 'Die :attribute muss mindestens :min Einträge enthalten.',
        'file'    => 'Die :attribute muss mindestens :min KB betragen.',
        'numeric' => 'Die :attribute muss mindestens :min sein.',
        'string'  => 'Die :attribute muss mindestens :min Zeichen enthalten.'
    ],
    'not_in'               => 'Die ausgewählte :attribute ist ungültig.',
    'numeric'              => 'Die :attribute muss eine Zahl sein.',
    'present'              => ':attribute muss vorhanden sein',
    'regex'                => 'Das Format von :attribute ist ungültig.',
    'required'             => 'Das :attribute/-Feld ist erforderlich.',
    'required_if'          => 'Das :attribute/-Feld wird dann erfolderlich, wenn :other :value beträgt.',
    'required_unless'      => 'Erforderlich, sofern',
    'required_with'        => 'Das :attribute/-Feld wird dann erforderlich, wenn :values vorhanden ist.',
    'required_with_all'    => 'Das :attribute/-Feld wird dann erforderlich, wenn :values vorhanden sind.',
    'required_without'     => 'Das :attribute/-Feld wird dann erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das :attribute/-Feld wird dann erforderlich, wenn keine von :values vorhanden sind.',
    'same'                 => ':attribute und :other müssen miteinander übereinstimmen.',
    'size'                 => [
        'array'   => 'Die :attribute muss Einträge mit Größe :size enthalten.',
        'file'    => 'Die :attribute muss :size KB betragen.',
        'numeric' => 'Die :attribute muss :size groß sein.',
        'string'  => 'Die :attribute muss :size Zeichen enthalten.'
    ],
    'string'               => 'Die :attribute muss eine Reihung sein.',
    'timezone'             => 'Die :attribute muss eine gültige Zone sein.',
    'unique'               => 'Die :attribute existiert bereits im System.',
    'url'                  => 'Das Format von :attribute ist ungültig.',
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message'
        ],
        'group_id'       => [
            'required' => 'Required'
        ],
        'password'       => [
            'confirmed' => 'Bestätigt',
            'min'       => 'Min'
        ],
        'path'           => [
            'required' => 'Required'
        ],
        'user_id'        => [
            'required' => 'Required'
        ]
    ],
    'attributes'           => [
        'address'               => 'Adresse',
        'age'                   => 'Alter',
        'available'             => 'Zugänglich',
        'city'                  => 'Stadt',
        'content'               => 'Inhalt',
        'country'               => 'Land',
        'date'                  => 'Datum',
        'day'                   => 'Tag',
        'description'           => 'Beschreibung',
        'email'                 => 'E-Mail-Adresse',
        'excerpt'               => 'Zitat',
        'first_name'            => 'Vorname',
        'gender'                => 'Geschlecht',
        'group_id'              => 'Gruppennummer',
        'hour'                  => 'Stunde',
        'last_name'             => 'Nachname',
        'minute'                => 'Minute',
        'mobile'                => 'Handynummer',
        'month'                 => 'Monat',
        'name'                  => 'Name',
        'password'              => 'Passwort',
        'password_confirmation' => 'Bestätigung des Passworts',
        'path'                  => 'Leitung',
        'phone'                 => 'Telefon',
        'second'                => 'Zweiter',
        'sex'                   => 'Geschlecht',
        'size'                  => 'Größe',
        'time'                  => 'Uhrzeit',
        'title'                 => 'Titel',
        'user_id'               => 'Nutzer-ID',
        'username'              => 'Nutzername',
        'year'                  => 'Jahr'
    ]
];