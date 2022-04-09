<?php

return [
    // app/lang/en/routes.php
    'about'   => 'about-us',
    'contact' => 'contact',
    'booking' => [
        'prefix' => 'booking',
        'confirm' => [
            'post' => 'confirm',
        ],
        'payment' => [
            'post' => 'payment',
        ],
        'process' => [
            'get' => 'process',
        ],
        'finish' => [
            'get' => 'finalizado/{booking}',
        ]
    ],
    'currency' => [
        'prefix' => '',
        'currency' => [
            'get' => 'currency/{currency}',
        ],
    ],
    'default' =>  [
        'prefix' => '',
        'register' => [
            'get' => 'sign-up',
            'post' => 'cadastre-seen',
        ],
        'newsletter' =>[
            'get' => 'newsletter',
            'post' => 'newsletter',
        ],
        'recover-account' =>[
            'get' => 'recover-account',
        ],
        'recover-login' =>[
            'get' => 'recover-login',
        ],
        'recover-password' =>[
            'post' => 'recover-password',
        ],
        'verify-account' =>[
            'get' => 'verify-account',
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
            'get' => 'idiom/{language}',
        ],
    ],
    'myaccount' =>[
        'prefix' => 'my-account',
        'default' => [
            'get'   => '',
        ],
        'register' => [
            'get' => '',
            'get-register'          => 'my-register',
            'get-register-change'   => 'my-register/change',
            'post-register-change'  => 'my-register/change',
            'get-register-password-change'  => 'my-register/changepwd',
            'post-register-password-change'  => 'my-register/changepwd',
        ],
        'reservation' => [
            'approved-payment'      => 'reservas/approved-payment/{booking}/{bookingBill}',
            'credit-card-payment'   => 'reservas/credit-card-payment/{booking}/{bookingBill}',
            'do-payment'        => 'reservas/do-payment/{booking}/{bookingBill}',
            'failed-payment'    => 'reservas/failed-payment/{booking}/{bookingBill}',
            'get-active'        => 'booking-active',
            'get-passed'        => 'booking-passed',
            'get-reservation'   => 'booking/{booking}',
            'get-contract'      => 'booking/{booking}/contract',
            'get-invoice'       => 'reservas/{booking}/invoice/{bookingBill}',
            'get-change-payment'=> 'booking/{booking}/change-payment',
            'post-change-payment'=> 'booking/{booking}/change-payment',
            'post-billet-generation'=> 'booking/{booking}/generate-bill/{bookingBill}',
            'vouchers'  => [
                'get-voucher'           => 'booking/voucher/{bookingVoucher}',
                'get-voucher-file'      => 'booking/voucher-file/{bookingVoucherFile}',
            ]
        ]
    ],
    'package' => [
        'prefix' => 'package',
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
            'get' => 'details/{package}/{slug}',
            'post' => 'details/{package}/{slug}',
            'exclusive' => [
                'get' => 'exclusive/{token}',
            ]
        ],
        'search' => ['get' => 'search'],
    ],
    'pages' => [
        'contact'           => 'contato',
        'privacy_policy'    => 'privacy-policy',
        'terms_use'         => 'terms-of-use',
    ],
    'prebooking' => [
        'prefix' => 'pre-booking',
        'prebooking' => [
            'get' => '{event}/{slug}',
            'post' => '{event}/{slug}',
        ]
    ]
];
