<?php
return [
    'accepted'             => 'El :attribute debe ser aceptado.',
    'active_url'           => 'El :attribute no es una URL válida.',
    'after'                => 'El :attribute debe ser una fecha posterior a :date.',
    'alpha'                => 'El :attribute sólo puede contener letras.',
    'alpha_dash'           => 'El :attribute sólo puede contener letras, números y guiones.',
    'alpha_num'            => 'El :attribute sólo puede contener letras y números.',
    'array'                => 'El :attribute debe ser una matriz.',
    'before'               => 'El :attribute debe ser una fecha antes de :date.',
    'between'              => [
        'array'   => 'El :attribute debe tener entre :min y :max elementos.',
        'file'    => 'El :attribute debe estar entre :min y :max kilobytes.',
        'numeric' => 'El :attribute debe estar entre :min y :max.',
        'string'  => 'El :attribute debe estar entre :min y :max caracteres.'
    ],
    'boolean'              => 'El :attribute debe ser verdadero o falso.',
    'confirmed'            => 'La :attribute confirmación no coincide.',
    'date'                 => 'El :attribute no es una fecha válida.',
    'date_format'          => 'El :attribute no coincide con el formato :format.',
    'different'            => 'El :attribute y :other debe ser diferente.',
    'digits'               => 'El :attribute debe ser :digits dígitos.',
    'digits_between'       => 'El :attribute debe estar entre :min y :max dígitos.',
    'distinct'             => 'Diferente',
    'email'                => 'El :attribute debe ser una dirección de correo electrónico válida.',
    'exists'               => 'La selección :attribute no es valida.',
    'filled'               => 'El :attribute es un campo obligatorio.',
    'image'                => 'El :attribute debe ser una imagen.',
    'in'                   => 'La selección :attribute no es valida.',
    'in_array'             => 'En orden',
    'integer'              => 'El :attribute debe ser un número entero.',
    'ip'                   => 'El :attribute debe ser una dirección IP válida.',
    'json'                 => 'El :attribute debe ser una cadena JSON válida.',
    'max'                  => [
        'array'   => 'El :attribute no puede tener más de :max elementos.',
        'file'    => 'El :attribute no puede ser mayor que :max kilobytes.',
        'numeric' => 'El :attribute no puede ser mayor que :max.',
        'string'  => 'La :attribute no puede ser mayor que :max caracteres.'
    ],
    'mimes'                => 'El :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'array'   => 'El :attribute debe tener al menos :min elementos.',
        'file'    => 'El :attribute debe ser al menos :min kilobytes.',
        'numeric' => 'El :attribute debe ser al menos :min.',
        'string'  => 'La :attribute debe ser al menos :min caracteres.'
    ],
    'not_in'               => 'La selección :attribute es inválida.',
    'numeric'              => 'El :attribute debe ser un número.',
    'present'              => ':attribute debe estar presente',
    'regex'                => 'El :attribute formato no es válido.',
    'required'             => 'El :attribute es un campo obligatorio.',
    'required_if'          => 'El :attribute campo es necesario cuando :other es :value.',
    'required_unless'      => 'Requerido a menos que',
    'required_with'        => 'El :attribute se requiere cuando :values está presente.',
    'required_with_all'    => 'El :attribute se requiere cuando :values está presente.',
    'required_without'     => 'El :attribute se requiere cuando :values no está presente.',
    'required_without_all' => 'El :attribute no se requiere cuando :values están presentes.',
    'same'                 => 'El :attribute y :other debe coincidir.',
    'size'                 => [
        'array'   => 'El :attribute debe contener :size elementos.',
        'file'    => 'El :attribute debe ser :size kilobytes.',
        'numeric' => 'El :attribute debe ser :size.',
        'string'  => 'La :attribute debe ser :size caracteres.'
    ],
    'string'               => 'El :attribute debe ser una cadena.',
    'timezone'             => 'La :attribute debe ser una zona válida.',
    'unique'               => 'El :attribute ya se ha tomado.',
    'url'                  => 'La :attribute formato inválido.',
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'mensaje personalizado'
        ],
        'body'           => [
            'required' => 'Obligatorio'
        ],
        'group_id'       => [
            'required' => 'Obligatorio'
        ],
        'password'       => [
            'confirmed' => 'Confirmado',
            'min'       => 'minino'
        ],
        'path'           => [
            'required' => 'Obligatorio'
        ],
        'user_id'        => [
            'required' => 'Obligatorio'
        ]
    ],
    'attributes'           => [
        'address'               => 'Dirección',
        'age'                   => 'Edad',
        'available'             => 'Disponible',
        'body'                  => 'Cuerpo',
        'city'                  => 'Ciudad',
        'content'               => 'Contenido',
        'country'               => 'País',
        'date'                  => 'Fecha',
        'day'                   => 'Día',
        'description'           => 'Descripción',
        'email'                 => 'Email',
        'excerpt'               => 'Extracto',
        'first_name'            => 'Nombre',
        'gender'                => 'Género',
        'group_id'              => 'Número de grupo',
        'hour'                  => 'Hora',
        'last_name'             => 'Apellido',
        'minute'                => 'Minuto',
        'mobile'                => 'Móvil',
        'month'                 => 'Mes',
        'name'                  => 'Nombre',
        'password'              => 'Contraseña',
        'password_confirmation' => 'Confirmación de contraseña',
        'path'                  => 'Trayectoria',
        'phone'                 => 'Teléfono',
        'second'                => 'Segundo',
        'sex'                   => 'Sexo',
        'size'                  => 'Tamaño',
        'time'                  => 'Hora',
        'title'                 => 'Título',
        'user_id'               => 'Identidad de usuario',
        'username'              => 'Nombre de usuario',
        'year'                  => 'Año'
    ]
];