<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => 'El campo :attribute no es una URL válida.',
    'after'                => 'El campo :attribute debe ser un fecha posterior al :date.',
    'after_or_equal'       => 'El campo :attribute debe ser un fecha posterior o igual al :date.',
    'alpha'                => 'El campo :attribute solo puede contener letras.',
    'alpha_dash'           => 'El campo :attribute solo puede contener letras, números y guiones.',
    'alpha_num'            => 'El campo :attribute solo puede contener letras y números.',
    'array'                => 'El campo :attribute debe ser una matriz.',
    'before'               => 'El campo :attribute debe ser un fecha anterior :date.',
    'before_or_equal'      => 'El campo :attribute debe ser un fecha anterior o igual al :date.',
    'between'              => [
        'numeric' => 'El campo :attribute debe ser entre :min y :max.',
        'file'    => 'El campo :attribute debe ser entre :min y :max kilobytes.',
        'string'  => 'El campo :attribute debe ser entre :min y :max caracteres.',
        'array'   => 'El campo :attribute debe tener entre :min y :max artículos.',
    ],
    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'            => 'El campo :attribute de confirmación no coincide.',
    'date'                 => 'El campo :attribute no es una fecha válida.',
    'date_equals'          => 'El campo :attribute debe ser un fecha igual a :date.',
    'date_format'          => 'El campo :attribute no coincide con el formato :format.',
    'different'            => 'Los campos :attribute y :other debe ser diferente.',
    'digits'               => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between'       => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => 'El campo :attribute tiene dimensiones de imagen no válidas.',
    'distinct'             => 'El campo :attribute campo tiene un valor duplicado.',
    'email'                => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with'            => 'El campo :attribute debe terminar con uno de los siguientes: :values',
    'exists'               => 'El campo :attribute seleccionado no es válido.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute debe tener un valor.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file'    => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'string'  => 'El campo :attribute debe ser mayor que :value caracteres.',
        'array'   => 'El campo :attribute debe contener más de :value artículos.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser mayor o igual al :value.',
        'file'    => 'El campo :attribute debe ser mayor o igual al :value kilobytes.',
        'string'  => 'El campo :attribute debe ser mayor o igual al :value caracteres.',
        'array'   => 'El campo :attribute debe contener :value artículos ou mais.',
    ],
    'image'                => 'El campo :attribute debe ser una imagem.',
    'in'                   => 'El campo :attribute seleccionado no es válido.',
    'in_array'             => 'El campo :attribute no existe em :other.',
    'integer'              => 'El campo :attribute debe ser un número inteiro.',
    'ip'                   => 'El campo :attribute debe ser una dirección de IP válido.',
    'ipv4'                 => 'El campo :attribute debe ser una dirección IPv4 válido.',
    'ipv6'                 => 'El campo :attribute debe ser una dirección IPv6 válido.',
    'json'                 => 'El campo :attribute debe ser una string JSON válida.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file'    => 'El campo :attribute debe ser menor que :value kilobytes.',
        'string'  => 'El campo :attribute debe ser menor que :value caracteres.',
        'array'   => 'El campo :attribute debe contener menos de :value artículos.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser menor o igual al :value.',
        'file'    => 'El campo :attribute debe ser menor o igual al :value kilobytes.',
        'string'  => 'El campo :attribute debe ser menor o igual al :value caracteres.',
        'array'   => 'El campo :attribute no debe contener mais que :value artículos.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no puede ser superior a :max.',
        'file'    => 'El campo :attribute no puede ser superior a :max kilobytes.',
        'string'  => 'El campo :attribute no puede ser superior a :max caracteres.',
        'array'   => 'El campo :attribute no puede tener más de :max artículos.',
    ],
    'mimes'                => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser por lo menos :min.',
        'file'    => 'El campo :attribute debe tener por lo menos :min kilobytes.',
        'string'  => 'El campo :attribute debe tener por lo menos :min caracteres.',
        'array'   => 'El campo :attribute debe tener por lo menos :min artículos.',
    ],
    'not_in'               => 'El campo :attribute seleccionado no es válido.',
    'not_regex'            => 'El campo :attribute tiene un formato inválido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'password'             => 'La contraseña es incorrecta.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El campo :attribute tiene un formato inválido.',
    'regex_password'       => 'El campo :attribute tiene un formato no válido. Debe contener 8 caracteres, números, dígitos especiales (! $ #% @), letras mayúsculas y minúsculas. ',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other for :value.',
    'required_unless'      => 'El campo :attribute es obligatorio exceto quando :other for :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute valor es obligatorio cuando ninguno de los :values estão presentes.',
    'same'                 => 'Os campos :attribute e :other debe coincidir.',
    'size'                 => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file'    => 'El campo :attribute debe ser :size kilobytes.',
        'string'  => 'El campo :attribute debe ser :size caracteres.',
        'array'   => 'El campo :attribute debe contener :size artículos.',
    ],
    'starts_with'          => 'El campo :attribute debe comenzar con uno de los siguientes valores.: :values',
    'string'               => 'El campo :attribute debe ser una string.',
    'timezone'             => 'El campo :attribute debe ser una zona válida.',
    'unique'               => 'El campo :attribute ya está siendo utilizado.',
    'uploaded'             => 'Error al cargar el campo. :attribute.',
    'url'                  => 'El campo :attribute tiene un formato inválido.',
    'uuid' => 'El campo :attribute debe ser um UUID válido.',

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

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'address'    => 'dirección',
        'age'        => 'edad',
        'body'       => 'contenido',
        'city'       => 'ciudad',
        'country'    => 'país',
        'date'       => 'data',
        'day'        => 'día',
        'excerpt'    => 'resumen',
        'first_name' => 'primer nombre',
        'gender'     => 'género',
        'hour'       => 'hora',
        'last_name'  => 'apellido',
        'message'    => 'mensaje',
        'minute'     => 'minuto',
        'mobile'     => 'celular',
        'month'      => 'mes',
        'name'       => 'nombre',
        'password'   => 'contraseña',
        'phone'      => 'teléfono',
        'second'     => 'segundo',
        'sex'        => 'sexo',
        'state'      => 'estado',
        'subject'    => 'assunto',
        'text'       => 'texto',
        'time'       => 'hora',
        'title'      => 'título',
        'username'   => 'usuário',
        'year'       => 'año',
        'description' => 'descripción',
        'password_confirmation' => 'confirmación de contraseña',

//Registro de Cliente - Front

        'birthdate' => 'Fecha de Nacimiento',
        'language' => 'Idioma',
        'primary_documento' => 'Documento Principal',
        'address.address' => 'Dirección',
        'address.number' => 'Número de dirección',
        'address.city' => 'Ciudad',
        'address.state' => 'Estado / Província',
        'address.country' => 'País',
        'contacts.value.0' => 'Teléfono Principal',
        'contacts.value.1' => 'Teléfono Celular',
        'identity' => 'Documento Identidad',
        'address.neighborhood' => 'Barrio',
        'address.zip' => 'Código Postal',
        'company_name' => 'Nombre del Negocio',
        'legal_name' => 'Nombre Corporativo',
        'registry' => 'Número de registro / VAT',
        'bank_account.currency' => 'Moneda del Recibo',
        'contacts.responsible.0' => 'Nombre del responsable financiero',
        'contacts.responsible.1' => 'Nombre del responsable de Reservas',
        'contacts.value.2' => 'Teléfono y correo electrónico del responsable financiero',
        'contacts.value.3' => 'Teléfono y correo electrónico del responsable de reservass',
        'documents' => 'Prueba de documentación de la empresa',
        'bank_account.BRL.bank' => 'Nombre del Banco',
        'bank_account.BRL.agency' => 'Número de Agencia',
        'bank_account.BRL.account_type' => 'Tipo de cuenta: cuenta corriente o de ahorro',
        'bank_account.BRL.account_number' => 'Número de cuenta',
        'bank_account.USD.bank' => 'Número Swift/BIC',
        'bank_account.USD.wire' => 'Número Wire',
        'bank_account.USD.routing_number' => 'Número Routing',
        'bank_account.USD.account_number' => 'Numero de cuenta',
        'bank_account.EUR.bank' => 'Número de Swift/BIC',
        'bank_account.EUR.iban' => 'Numero de IBAN',
        'bank_account.GBP.iban' => 'Numero de IBAN',
        'bank_account.GBP.sort_code' => 'Sort Code xx-xx-xx',
        'bank_account.GBP.account_number' => 'Numero de cuenta',
    ],

];
