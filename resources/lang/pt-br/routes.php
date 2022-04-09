<?php

return [
    // app/lang/pt-br/routes.php
    'about'   => 'quem-somos',
    'contact' => 'contato',
    'booking' => [
        'prefix' => 'reservas',
        'confirm' => [
            'post' => 'confirmar',
        ],
        'payment' => [
            'post' => 'pagamento',
        ],
        'process' => [
            'get' => 'processo',
        ],
        'finish' => [
            'get' => 'finalizado/{booking}',
        ]
    ],
    'currency' => [
        'prefix' => '',
        'currency' => [
            'get' => 'moeda/{currency}',
        ],
    ],
    'default' =>  [
        'prefix' => '',
        'register' => [
            'get' => 'cadastre-se',
            'post' => 'cadastre-se',
        ],
        'newsletter' =>[
            'get' => 'newsletter',
            'post' => 'newsletter',
        ],
        'recover-account' =>[
            'get' => 'recuperar-conta',
        ],
        'recover-login' =>[
            'get' => 'recuperar-login',
        ],
        'recover-password' =>[
            'post' => 'recuperar-senha',
        ],
        'verify-account' =>[
            'get' => 'verificar-conta',
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
            'get' => 'idioma/{language}',
        ],
    ],
    'myaccount' =>[
        'prefix' => 'minha-conta',
        'default' => [
            'get'   => '',
        ],
        'register' => [
            'get' => '',
            'get-register'          => 'cadastro',
            'get-register-change'   => 'cadastro/alterar',
            'post-register-change'  => 'cadastro/alterar',
            'get-register-password-change'  => 'cadastro/alterarpwd',
            'post-register-password-change'  => 'cadastro/alterarpwd',
        ],
        'reservation' => [
            'approved-payment'  => 'reservas/approved-payment/{booking}/{bookingBill}',
            'do-payment'        => 'reservas/do-payment/{booking}/{bookingBill}',
            'failed-payment'    => 'reservas/failed-payment/{booking}/{bookingBill}',
            'get-active'        => 'reservas-ativas',
            'get-passed'        => 'reservas-passadas',
            'get-reservation'   => 'reservas/{booking}',
            'get-contract'      => 'reservas/{booking}/contrato',
            'get-invoice'       => 'reservas/{booking}/invoice/{bookingBill}',
            'get-change-payment'=> 'reservas/{booking}/alterar-metodo-pagamento',
            'post-change-payment'=> 'reservas/{booking}/alterar-metodo-pagamento',
            'post-billet-generation'=> 'reservas/{booking}/gerar-boleto/{bookingBill}',
            'vouchers'  => [
                'get-voucher'           => 'booking/voucher/{bookingVoucher}',
                'get-voucher-file'      => 'booking/voucher-file/{bookingVoucherFile}',
            ]
        ]
    ],
    'package' => [
        'prefix' => 'pacotes',
        'default' => [
            'getservprin'       => '/pacotes/getservprinajax',
            'bookable-products' => '/package/bookable-products',
            'longtrip-accommodation-details' => '/pacotes/accommodation-details',
            'getformapagajax'   => '/pacotes/getformapagajax',
            'getservadicajax'   => '/pacotes/getservadicajax',
            'grupoajax'         => '/pacotes/grupoajax',
            'aplicapromocodeajax' => '/package/apply-promo',
            'numpassajax'       => '/package/numpassajax',
            'savesessionajax'   => '/package/save-session',
            'updatebooking'     => '/pacotes/updateBooking',
        ],
        'details' => [
            'list' => 'list',
            'get' => 'detalhes/{package}/{slug}',
            'post' => 'detalhes/{package}/{slug}',
            'exclusive' => [
                'get' => 'exclusive/{token}',
            ]
        ],
        'search' => ['get' => 'buscar'],
    ],
    'pages' => [
        'contact'           => 'contato',
        'privacy_policy'    => 'politica-de-privacidade',
        'terms_use'         => 'termo-de-uso',
    ],
    'prebooking' => [
        'prefix' => 'prereserva',
        'prebooking' => [
            'get' => '{event}/{slug}',
            'post' => '{event}/{slug}',
        ]
    ]
];
