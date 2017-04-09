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

    'accepted'              => 'The :attribute must be accepted.',
    'active_url'            => 'The :attribute is not a valid URL.',
    'after'                 => 'The :attribute must be a date after :date.',
    'alpha'                 => 'The :attribute may only contain letters.',
    'alpha_dash'            => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'             => 'The :attribute may only contain letters and numbers.',
    'array'                 => 'The :attribute must be an array.',
    'before'                => 'The :attribute must be a date before :date.',
    'between'               => [
        'array'     => 'The :attribute must have between :min and :max items.',
        'file'      => 'The :attribute must be between :min and :max kilobytes.',
        'numeric'   => 'The :attribute must be between :min and :max.',
        'string'    => 'The :attribute must be between :min and :max characters.',
    ],
    'boolean'               => 'The :attribute field must be true or false.',
    'confirmed'             => 'Le champ :attribute de confirmation n\'est pas identique.',
    'date'                  => 'The :attribute is not a valid date.',
    'date_format'           => 'The :attribute does not match the format :format.',
    'different'             => 'The :attribute and :other must be different.',
    'digits'                => 'The :attribute must be :digits digits.',
    'digits_between'        => 'The :attribute must be between :min and :max digits.',
    'distinct'              => 'Le champ :attribute a une valeur dupliquée.',
    'email'                 => 'The :attribute must be a valid email address.',
    'exists'                => 'The selected :attribute is invalid.',
    'filled'                => 'The :attribute field is required.',
    'image'                 => 'The :attribute must be an image.',
    'in'                    => 'The selected :attribute is invalid.',
    'in_array'              => 'Le champ :attribute n\'existe pas dans :other.',
    'integer'               => 'The :attribute must be an integer.',
    'ip'                    => 'The :attribute must be a valid IP address.',
    'json'                  => 'The :attribute must be a valid JSON string.',
    'max'                   => [
        'array'     => 'The :attribute may not have more than :max items.',
        'file'      => 'The :attribute may not be greater than :max kilobytes.',
        'numeric'   => 'The :attribute may not be greater than :max.',
        'string'    => 'The :attribute may not be greater than :max characters.',
    ],
    'mimes'                 => 'The :attribute must be a file of type: :values.',
    'min'                   => [
        'array'     => 'The :attribute must have at least :min items.',
        'file'      => 'The :attribute must be at least :min kilobytes.',
        'numeric'   => 'The :attribute must be at least :min.',
        'string'    => 'Le champ :attribute doit avoir au moins :min caractères.',
    ],
    'not_in'                => 'The selected :attribute is invalid.',
    'numeric'               => 'The :attribute must be a number.',
    'present'               => 'Le champ :attribute doit être présent.',
    'regex'                 => 'The :attribute format is invalid.',
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
    'string'                => 'The :attribute must be a string.',
    'timezone'              => 'The :attribute must be a valid zone.',
    'unique'                => 'Le champ :attribute est déjà utilisé.',
    'url'                   => 'The :attribute format is invalid.',

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
        'group_id'          => [
            'required'  => 'Required',
        ],
        'password'          => [
            'confirmed' => 'Confirmé',
            'min'       => 'Min',
        ],
        'path'              => [
            'required'  => 'Required',
        ],
        'user_id'           => [
            'required'  => 'Required',
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
        'group_id'              => 'Numéro de groupe',
        'hour'                  => 'Heure',
        'last_name'             => 'Nom',
        'minute'                => 'Minute',
        'mobile'                => 'Portable',
        'month'                 => 'Mois',
        'name'                  => 'Nom',
        'password'              => 'Mot de passe',
        'password_confirmation' => 'Confirmation du mot de passe',
        'path'                  => 'Chemin',
        'phone'                 => 'Téléphone',
        'second'                => 'Seconde',
        'sex'                   => 'Sexe',
        'size'                  => 'Taille',
        'time'                  => 'Heure',
        'title'                 => 'Titre',
        'user_id'               => 'User Id',
        'username'              => 'Pseudo',
        'year'                  => 'Année',
    ],
];
