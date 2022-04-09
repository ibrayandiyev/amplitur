<?php

return [

// Geral

'geral' => [
    'hi' => 'Hola,',
    'att' => 'Atentamente,',
    'direitos' => " AMP Travels Ltd. © - Todos los Derechos Reservados {{ date('Y') }}",
],

//Client
'client' => [

    'geral' => [
        'created' => 'Su registro en el sitio web de AMP Travels',
        'validated' => 'Registro Validado',
    ],

    //Client - Booking

    'booking' => [

            'confirm_purchase' => 'Confirmación de Compra',
            'introduce_client' => '¡Gracias por usar el sistema AMP Travels !',
            'confirm_thanks' => 'Verifique los detalles de su (s) servicio (s) a continuación',
            'booking_code' => 'Booking Code',
            'passenger' => 'Pasajero',
            'important_notes' => 'Notas importantes',
            'track' => 'Seguimiento de compras',
            'keep_track' => 'Para rastrear su compra, imprimir documentos y vales, acceda a los detalles de su pedido en la sección',
            'my_account' => 'Mi Cuenta',
            'purchase_cancel' => 'La Compra puede ser CANCELADA en los siguientes casos:',
            'msg_cancel_1' => 'Cuotas impagas en sus fechas de vencimiento;',
            'msg_cancel_2' => 'Sin autorización de débito de tarjeta de crédito;',
            'msg_cancel_3' => 'Compras simultáneas con las mismas características de servicio y adicionales para las mismas personas;',
            'head_docs' => 'Documentación a enviar',
            'label_contract' => 'Contrato de Viaje',
            'msg_doc_1' => 'Los documentos anteriores deben enviarse escaneados al correo electrónico <a href="mailto:reservas@amplitur.com"> reservas@amplitur.com </a> lo antes posible para la liberación de los ) Voucher (s) de prestación de servicios,',
            'head_voucher'=> 'Vouchers y Servicios',
            'msg_voucher_1' => "ES OBLIGATORIO PRESENTAR EL VOUCHER DE EMBARQUE junto con el documento de identificación original con foto de los pasajeros para la Prestación del Servicio.",
            'msg_voucher_2' => 'El Voucher de embarque: solo se entregará HASTA 7 DÍAS antes del embarque y después de recibir toda la documentación (Contrato de viaje y / o Autorización de débito) debidamente cumplimentada y firmada.',
            'more_info' => 'Más información y Preguntas',
            'msg_info_1' =>"Acceda al enlace <a href='{{ route(getRouteByLanguage('frontend.pages.contact')) }}'> Preguntas frecuentes </a>, o por correo electrónico <a href = 'mailto : booking@amp-travels.com '> booking@amp-travels.com </a>.",

        ],

    //Client - Comunication

    'comun' => [

        'update' => 'Progreso de la Reserva',
        'update_msg' => 'Te informamos que hubo un avance en tu proceso de compra',
        'booking_code' => 'BOOKING CODE',
        'msg_click' => 'Haga clic en el enlace de abajo para ver el Booking Code',
        'purchase_acess' => 'Acceso de Compra',
    ],

    // Client - Registry

    'registry' => [

            'reg_head' => 'Su registro en el sitio web de AMP Travels ',
            'reg_thanks' => '¡Gracias por registrarse en nuestro sitio!',
            'reg_attention' => '<strong> Atención: </strong> su registro debe estar validado para que pueda realizar compras en el sitio web de AMP Travels .',
            'valid_link' => 'Haga clic en el enlace de abajo para realizar esta validación:',
            'link_valid' => 'Validación de Enlaces',
            'client_msg' => 'A continuación, los datos principales rellenados:',
            'name' => 'Nombre',
            'email' => 'Correo Electrónico',
            'login' => 'Login',
            'pass' => 'Contraseña',
            'info_pass' => 'contraseña oculta por seguridad',
            'login_msg' => 'Inicie sesión aquí con su nombre de usuario o correo electrónico para acceder a su cuenta y verificar sus reservas y registro.',
            'doubt_msg' => 'Si tiene alguna pregunta, por favor contáctenos.',
    ],

    //Client - Valid Registry

    'valid_registry' => [
            'head' => '¡Su registro fue validado exitosamente!',
            'head_msg' => 'Ahora puede realizar compras en el sitio web de AMP Travels ',
            'link_msg' => 'Haga clic en el enlace de abajo para acceder al sitio',
            'link_acess' => 'Acceder al Sitio',
            'doubt_msg' => 'Si tiene alguna pregunta, comuníquese con nosotros.',
            'not_validated' => 'Hubo un error al validar su registro, si el error persiste, contáctenos por correo electrónico <a href="mailto:booking@amp-travels.com">booking@amp-travels.com</a>',
    ],

    //Client - Recovery Username

    'recov_user' => [
        'head' => 'Recuperación de Usuario',
        'recov_msg' => 'Se solicitó a través del sitio web para recuperar el inicio de sesión de la cuenta con el correo electrónico',
        'recov_login' => 'Tu nombre de usuario es',
        'end_msg' => 'Si no ha solicitado este cambio de contraseña a través del sitio, ignore este correo electrónico.',
    ],

    //Client - Recovery Password

    'recov_pass' => [
        'head' => 'Recuperación de Contraseña',
        'password_changed' => 'Contraseña cambiada',
        'recov_msg' => 'Se solicitó la recuperación de contraseña para la cuenta a través del sitio web',
        'recov_pass' => 'Haga clic en el enlace de abajo para registrar una nueva contraseña para su cuenta. Este enlace será válido para el cambio de contraseña hasta',
        'change_pass_link' => 'Cambiar Contraseña',
        'end_msg' => 'Si no ha solicitado este cambio, ignore este correo electrónico.',
        'head_confirm' => 'Confirmación de cambio de contraseña',
        'recov_msg_confirm' => 'Confirmamos el cambio de contraseña a',
        'recov_pass_confirm' => 'En su nuevo acceso a nuestro sitio, utilice la nueva contraseña registrada.',
 ],

    //Client - Data Change

    'data_change' => [
        'head' => 'Cambio de datos de Registro',
        'data_msg' => 'Confirmamos que se han realizado cambios en sus datos de registro.',
        'end_msg' => 'Si no ha solicitado este cambio, contáctenos a través de nuestros canales de servicio.',
        'click_here' => 'Haga clic aquí',
    ],

],
//Backoffice
'backoffice' => [

    //Client
    'client' => [
        'created' => 'Nuevo cliente registrado ',
    ],

    //Booking - Backoffice

    'booking' => [

    'confirm_purchase' => 'Aviso de Venta',
    'booking_code' => 'Booking Code',
    'head_card_decript' => 'Pago - Tarjeta de crédito',
    'passenger' => 'Pasajero',
    'important_notes' => 'Notas importantes',
    ],
],

//Provider
'provider' => [

    'created' => 'Confirmación de registro de Provider',
    'validated' => 'Registro Validado',

    // Booking - Provider

    'booking' => [

    'provider_confirm_purchase' => 'Confirmación de Venta',
    'confirm_purchase' => '¡Felicitaciones, su oferta ha sido vendida!',
    'introduce_client' => '¡Gracias por usar el sistema AMP Travels !',
    'confirm_thanks' => 'Tenga en cuenta que su oferta para',
    'has_sold' => 'fue vendida.',
    'booking_code' => 'BOOKING CODE',
    'keep_track' => 'Para más detalles y para realizar un seguimiento de sus ventas, inserción de documentos y comprobantes, acceda a los detalles de su pedido en su área administrativa.',
    'my_account' => 'Mi Cuenta',
    'important_notes' => 'Notas Importantes',
    'purchase_cancel' => 'Recuerde que la VENTA puede CANCELARSE automáticamente en las siguientes situaciones:',
    'msg_cancel_1' => 'No hay confirmación de pago por parte del cliente;',
    'msg_cancel_2' => 'Si el cliente ejerce el derecho de "arrepentirse de la compra" dentro de los 7 (siete) días;',
    'msg_cancel_3' => 'Si NO es posible brindar el servicio como se anuncia, comuníquese con nuestro soporte URGENTEMENTE a través del correo electrónico <a href="mailto:reservas@amplitur.com"> reservas@amplitur.com </ a >, evitando sanciones.',
    'head_voucher'=> 'Vouchers y Servicios',
    'msg_voucher_1' => 'Los Vouchers de servicio deben cargarse en la reserva en un plazo máximo de 10 (días) antes de la prestación del servicio, con todas las pautas importantes para que el cliente pueda utilizar el servicio contratado.',
    'head_pags' => 'Pagos y Multas',
    'msg_pags_1' => 'En ausencia de cargar el (los) Vouchers (s), dentro del período descrito anteriormente, el Proveedor está sujeto a problemas;',
    'msg_pags_2' => 'Las transferencias de montos se realizan dentro de los 15 (quince) días posteriores a la finalización del servicio;',
    'msg_pags_3' => 'Más información sobre herramientas y transferencias, acceda al enlace XXXXX;', //RODRIGO INSERIR LINK
    'more_info' => 'Más información y Preguntas',
    'msg_info_1' =>"A través del correo electrónico de soporte <a href='mailto:reservas@amplitur.com'> reservas@amplitur.com </a>",
    ],

    'registry' => [

    'reg_head' => 'Su registro en el sitio web de AMP Travels ',
    'reg_thanks' => '¡Gracias por registrarse en nuestro sitio!',
    'reg_attention' => '<strong> Atención: </strong> su registro necesita ser validado para que pueda vender sus productos y servicios en el sitio web de AMP Travels .',
    'valid_link' => 'Haga clic en el enlace de abajo para realizar esta validación:',
    'link_valid' => 'Validar mi Registro',
    'link_valid_type' => 'Copie y pegue la dirección a continuación en su navegador para validar su cuenta',
    'client_msg' => 'A continuación, los datos principales rellenados:',
    'name' => 'Nombre',
    'email' => 'Correo electrónico',
    'login' => 'Login',
    'pass' => 'Contraseña',
    'info_pass' => 'contraseña oculta por seguridad',
    'login_msg' => 'Inicie sesión aquí con su nombre de usuario o correo electrónico para acceder a su cuenta y ver sus ofertas y ventas',
    'doubt_msg' => 'Si tiene alguna pregunta, por favor contáctenos.',
    ],

    'company' => [

        'created' => 'Confirmación de registro de Empresa',
        'reg_head' => 'Registro de la Empresa',
        'reg_thanks' => '¡El registro de su empresa fue Exitoso!',
        'reg_attention' => '<strong> Atención: </strong> se analizará tu registro y una vez validada la información podrás comercializar tus ofertas en nuestro portal.',
        'client_msg' => 'Los siguientes son los datos principales de la empresa registrada:',
        'name' => 'Nombre Corporativo',
        'name_fantasy' => 'Apelido',
        'address' => 'Dirección',
        'clique_aqui' => 'Haga clic aquí',
        'login_msg' => ' para acceder a su cuenta y ver sus ofertas y ventas',
        'doubt_msg' => 'Si tiene alguna pregunta, comuníquese con nosotros.',
        ],

        //Provider - Valid Registry

        'valid_registry' => [
            'head' => '¡Su Registro fue Validado!',
            'head_msg' => 'Ahora puedes registrar tu empresa, paquetes y ofertas para vender en nuestra plataforma.',
            'link_msg' => 'Haga clic en el enlace de abajo para acceder área de Login y su Panel de control',
            'link_acess' => 'Login Sitio',
            'doubt_msg' => 'Si tiene alguna pregunta, comuníquese con nosotros. ¡Buenas ventas!',
        ],

        //Provider - Recovery Password

        'recov_pass' => [
            'head' => 'Recuperación de Contraseña',
            'recov_msg' => 'Se solicitó la recuperación de contraseña para la cuenta a través del sitio web',
            'recov_pass' => 'Haga clic en el enlace de abajo para registrar una nueva contraseña para su cuenta. Este enlace será válido para el cambio de contraseña hasta',
            'minutes' => 'minutos',
            'change_pass_link' => 'Cambiar Contraseña',
            'link_valid_type' => 'Copie y pegue la dirección a continuación en su navegador para cambiar su contraseña',
            'end_msg' => 'Si no ha solicitado este cambio, ignore este correo electrónico.',
        ],

        //Client - Comunication

        'comun' => [

            'update' => 'Progreso de la Venta',
            'update_msg' => 'Te informamos que hubo un avance en tu proceso de venta',
            'booking_code' => 'BOOKING CODE',
            'msg_click' => 'Haga clic en el enlace de abajo para ver el Booking Code',
            'purchase_acess' => 'Acceso de Venta',
        ],

],
];
