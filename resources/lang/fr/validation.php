<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'              => 'Le :attribute doit être accepté.',
    'active_url'            => 'Le :attribute n\'est pas une URL valide.',
    'after'                 => 'Le :attribute doit être une date après :date.',
    'alpha'                 => 'Le :attribute ne peut contenir que des lettres.',
    'alpha_dash'            => 'Le :attribute ne peut contenir que des lettres, des chiffres et des tirets.',
    'alpha_num'             => 'Le :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                 => 'Le :attribute doit être un tableau.',
    'before'                => 'Le :attribute doit être une date avant :date.',
    'between'               => [
        'array'     => 'Le :attribute doit avoir entre :min et :max articles.',
        'file'      => 'Le :attribute doit être comprise entre :min et :max kilo-octets.',
        'numeric'   => 'Le :attribute doit être comprise entre :min et :max.',
        'string'    => 'Le :attribute doit être comprise entre :min et :max caractères.',
    ],
    'boolean'               => 'Le :attribute champ doit être vrai ou faux.',
    'confirmed'             => 'Le champ :attribute de confirmation n\'est pas identique.',
    'date'                  => 'Le :attribute n\'est pas une date valide.',
    'date_format'           => 'Le :attribute ne correspond pas au format :format.',
    'different'             => 'Le :attribute et :other doit être différent.',
    'digits'                => 'Le :attribute doit être :digits chiffres.',
    'digits_between'        => 'Le :attribute doit être comprise entre :min et :max chiffres.',
    'distinct'              => 'Le champ :attribute a une valeur dupliquée.',
    'email'                 => 'Le :attribute doit être une adresse email valide.',
    'exists'                => 'Sélectionnés :attribute n\'est pas valide.',
    'filled'                => 'Le :attribute champ est requis.',
    'image'                 => 'Le :attribute doit être une image.',
    'in'                    => 'Sélectionnés :attribute n\'est pas valide.',
    'in_array'              => 'Le champ :attribute n\'existe pas dans :other.',
    'integer'               => 'The :attribute must be an integer.',
    'ip'                    => 'The :attribute must be a valid IP address.',
    'json'                  => 'The :attribute must be a valid JSON string.',
    'max'                   => [
        'array'     => 'The :attribute may not have more than :max items.',
        'file'      => 'Le :attribute ne peut pas être supérieure à :max kilo-octets.',
        'numeric'   => 'The :attribute may not be greater than :max.',
        'string'    => 'The :attribute may not be greater than :max characters.',
    ],
    'mimes'                 => 'The :attribute must be a file of type: :values.',
    'min'                   => [
        'array'     => 'The :attribute must have at least :min items.',
        'file'      => 'Le :attribute doit être d\'au moins :min kilo-octets.',
        'numeric'   => 'Le :attribute doit être d\'au moins :min.',
        'string'    => 'Le champ :attribute doit avoir au moins :min caractères.',
    ],
    'not_in'                => 'The selected :attribute is invalid.',
    'numeric'               => 'Le :attribute doit être un nombre.',
    'present'               => 'Le champ :attribute doit être présent.',
    'regex'                 => 'Le :attribute format n\'est pas valide.',
    'required'              => 'Le champ :attribute est requis.',
    'required_if'           => 'The :attribute field is required when :other is :value.',
    'required_unless'       => 'Le champ :attribute est obligatoire sauf si :other est :values.',
    'required_with'         => 'The :attribute field is required when :values is present.',
    'required_with_all'     => 'The :attribute field is required when :values is present.',
    'required_without'      => 'The :attribute field is required when :values is not present.',
    'required_without_all'  => 'The :attribute field is required when none of :values are present.',
    'same'                  => 'Le champ :attribute doit être le même que :other.',
    'size'                  => [
        'array'     => 'The :attribute must contain :size items.',
        'file'      => 'The :attribute must be :size kilobytes.',
        'numeric'   => 'The :attribute must be :size.',
        'string'    => 'Le champ :attribute doit avoir :size caractères.',
    ],
    'string'                => 'Le :attribute doit être une chaîne de caractères.',
    'timezone'              => 'Le :attribute doit être une zone valide.',
    'unique'                => 'Le champ :attribute est déjà utilisé.',
    'url'                   => 'Le :attribute format n\'est pas valide.',

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

    'custom'    => [
        'attribute-name'    => [
            'rule-name' => 'custom-message',
        ],
    ],

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

    'attributes'    => [
        'address'               => 'Adresse',
        'age'                   => 'Age',
        'available'             => 'Disponible',
        'city'                  => 'Ville',
        'content'               => 'Contenu',
        'country'               => 'Pays',
        'date'                  => 'Date',
        'day'                   => 'Jour',
        'description'           => 'Description',
        'email'                 => 'E-mail',
        'excerpt'               => 'Extrait',
        'first_name'            => 'Prénom',
        'gender'                => 'Genre',
        'hour'                  => 'Heure',
        'last_name'             => 'Nom',
        'minute'                => 'Minute',
        'mobile'                => 'Portable',
        'month'                 => 'Mois',
        'name'                  => 'Nom',
        'password'              => 'Mot de passe',
        'password_confirmation' => 'Confirmation du mot de passe',
        'phone'                 => 'Téléphone',
        'second'                => 'Seconde',
        'sex'                   => 'Sexe',
        'size'                  => 'Taille',
        'time'                  => 'Heure',
        'title'                 => 'Titre',
        'username'              => 'Pseudo',
        'year'                  => 'Année',
    ],
];
