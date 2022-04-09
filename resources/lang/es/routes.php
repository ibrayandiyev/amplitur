<?php

return [
    // app/lang/es/routes.php
    'about'   => 'sobre',
    'contact' => 'contacto',
    'booking' => [
        'prefix' => 'reservas',
        'confirm' => [
            'post' => 'confirmar',
        ],
        'payment' => [
            'post' => 'pago',
        ],
        'process' => [
            'get' => 'proceso',
        ],
        'finish' => [
            'get' => 'finalizado/{booking}',
        ]
    ],
    'currency' => [
        'prefix' => '',
        'currency' => [
            'get' => 'divisa/{currency}',
        ],
    ],
    'default' =>  [
        'prefix' => '',
        'register' => [
            'get' => 'haga-su-cuenta',
            'post' => 'haga-su-cuenta',
        ],
        'newsletter' =>[
            'get' => 'newsletter',
            'post' => 'newsletter',
        ],
        'recover-account' =>[
            'get' => 'olvido-su-cuenta',
        ],
        'recover-login' =>[
            'get' => 'olvido-su-login',
        ],
        'recover-password' =>[
            'post' => 'olvido-su-clave',
        ],
        'verify-account' =>[
            'get' => 'verificar-su-cuenta',
        ],
        'view-recover-password' =>[
            'get' => 'view-recover-password',
        ],
        'do-recover-password' =>[
            'post' => 'do-recover-password',
        ]
    ],
    'language' => [
        'prefix' => '',
        'language' => [
            'get' => 'lengua/{language}',
        ],
    ],
    'myaccount' =>[
        'prefix' => 'mi-cuenta',
        'default' => [
            'get'   => '',
        ],
        'register' => [
            'get' => '',
            'get-register'          => 'mi-registro',
            'get-register-change'   => 'mi-registro/cambiar',
            'post-register-change'  => 'mi-registro/cambiar',
            'get-register-password-change'  => 'mi-registro/cambiarpwd',
            'post-register-password-change'  => 'mi-registro/cambiarpwd',
        ],
        'reservation' => [
            'approved-payment'  => 'reservas/approved-payment/{booking}/{bookingBill}',
            'do-payment'        => 'reservas/do-payment/{booking}/{bookingBill}',
            'failed-payment'    => 'reservas/failed-payment/{booking}/{bookingBill}',
            'get-active'        => 'viajes-activas',
            'get-passed'        => 'viajes-pasadas',
            'get-reservation'   => 'viajes/{booking}',
            'get-contract'      => 'viajes/{booking}/contrato',
            'get-invoice'       => 'reservas/{booking}/invoice/{bookingBill}',
            'get-change-payment'=> 'viajes/{booking}/cambiar-metodo-pago',
            'post-change-payment'=> 'viajes/{booking}/cambiar-metodo-pago',
            'post-billet-generation'=> 'viajes/{booking}/generar-factura/{bookingBill}',
            'vouchers'  => [
                'get-voucher'           => 'booking/voucher/{bookingVoucher}',
                'get-voucher-file'      => 'booking/voucher-file/{bookingVoucherFile}',
            ]
        ]
    ],
    'package' => [
        'prefix' => 'paquetes',
        'default' => [
            'getservprin'       => '/pacotes/getservprinajax',
            'bookable-products' => '/package/bookable-products',
            'longtrip-accommodation-details' => '/pacotes/accommodation-details',
            'getformapagajax'   => '/pacotes/getformapagajax',
            'getservadicajax'   => '/pacotes/getservadicajax',
            'grupoajax'         => '/pacotes/grupoajax',
            'aplicapromocodeajax' => '/package/apply-promo',
            'numpassajax'       => '/pacotes/numpassajax',
            'savesessionajax'   => '/package/save-session',
            'updatebooking'     => '/pacotes/updateBooking',
        ],
        'details' => [
            'list' => 'list',
            'get' => 'detalles/{package}/{slug}',
            'post' => 'detalles/{package}/{slug}',
            'exclusive' => [
                'get' => 'exclusive/{token}',
            ]
        ],
        'search' => ['get' => 'buscar'],
    ],
    'pages' => [
        'contact'           => 'contato',
        'privacy_policy'    => 'politica-de-privacidad',
        'terms_use'         => 'condiciones-de-uso',
    ],
    'prebooking' => [
        'prefix' => 'pre-reserva',
        'prebooking' => [
            'get' => '{event}/{slug}',
            'post' => '{event}/{slug}',
        ]
    ]
];
