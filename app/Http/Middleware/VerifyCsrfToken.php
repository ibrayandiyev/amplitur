<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/pacotes/getservprinajax',
        '/pacotes/getformapagajax',
        '/pacotes/getservadicajax',
        '/pt-br/pacotes/getformapagajax',
        '/es/pacotes/getformapagajax',
        '/pacotes/grupoajax',
        '/pacotes/aplicapromocodeajax',
        '/package/apply-promo',
        '/pt-br/pacotes/bookable-products',
        '/es/pacotes/bookable-products',
        '/pacotes/accommodation-details',
        '/pt-br/pacotes/accommodation-details',
        '/pacotes/savesessionajax',
        '/pacotes/updateBooking',
        '/pacotes/numpassajax',
        '/booking/shopline_return',
        '/*/package/bookable-products'
    ];
}
