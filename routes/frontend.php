<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Front-end Routes
|--------------------------------------------------------------------------
|
| Here is where you can register front-end routes
|
*/
/**
 * Route fixed with help of: https://stackoverflow.com/questions/25082154/how-to-create-multilingual-translated-routes-in-laravel
 */
$all_langs = config('app.all_langs');

Route::get('/', 'HomeController@index')->name('frontend.index');
Route::post(Lang::get('routes.package.default.aplicapromocodeajax',[], 'en')    , 'PackageShoppingController@applyPromoCode')->name('frontend.ajax.apply-promocode');
Route::get('refresh-csrf', 'Auth\LoginController@refreshCsrf')->name('frontend.renew-csrf-token');
Route::get('/clients/validate-token/{validation_token}', 'AuthController@validateToken')->name('frontend.default.validateToken');

/**
* Iterate over each language prefix
*/
if($all_langs)
foreach( $all_langs as $prefix ){

    if ($prefix == 'en') $prefix = '';

    /**
    * Register new route group with current prefix
    */
    Route::group(['prefix' => $prefix], function() use ($prefix) {

        // Now we need to make sure the default prefix points to default  lang folder.
        if ($prefix == '') $prefix = 'en';

        // AJAX Routes
        Route::post(Lang::get('routes.package.default.getservprin',[], $prefix)         , 'PackageShoppingController@getOfferProduct')->name($prefix.'.frontend.ajax.offer-product');
        Route::post(Lang::get('routes.package.default.bookable-products',[], $prefix)   , 'PackageShoppingController@getBookableOfferProduct')->name($prefix.'.frontend.ajax.bookable-products');
        Route::post(Lang::get('routes.package.default.longtrip-accommodation-details',[], $prefix), 'PackageShoppingController@getLongtripAccommodationDetails')->name($prefix.'.frontend.ajax.longtrip-accommodation-details');
        Route::post(Lang::get('routes.package.default.getformapagajax',[], $prefix)     , 'PackageShoppingController@getPaymentDetails')->name($prefix.'.frontend.ajax.payment-details');
        Route::post(Lang::get('routes.package.default.getservadicajax',[], $prefix)     , 'PackageShoppingController@getOfferAdditionals')->name($prefix.'.frontend.ajax.offer-additionals');
        Route::post(Lang::get('routes.package.default.grupoajax',[], $prefix)           , 'PackageShoppingController@getOffer')->name($prefix.'.frontend.ajax.offer');
        Route::post(Lang::get('routes.package.default.numpassajax',[], $prefix)         , 'PackageShoppingController@changePassengers')->name($prefix.'.frontend.ajax.change-passengers');
        Route::post(Lang::get('routes.package.default.updatebooking',[], $prefix)       , 'PackageShoppingController@updateBooking')->name($prefix.'.frontend.ajax.updatebooking');

        // Booking
        Route::group(['prefix' => Lang::get('routes.booking.prefix',[], $prefix)], function () use($prefix) {
            Route::get(Lang::get('routes.booking.process.get',[], $prefix)          , 'BookingController@summary')->name($prefix.".frontend.booking.summary");
            Route::get(Lang::get('routes.booking.payment.post',[], $prefix)         , 'BookingController@payment')->name($prefix.".frontend.booking.payment");
            Route::get(Lang::get('routes.booking.finish.get',[], $prefix)           , 'BookingController@finish')->name($prefix.".frontend.booking.finish");
            Route::post(Lang::get('routes.booking.confirm.post',[], $prefix)        , 'BookingController@store')->name($prefix.".frontend.booking.store");
        });

        // Currency & Language
        Route::get(Lang::get('routes.currency.currency.get',[], $prefix)            , 'CurrencyLanguageController@changeCurrency')->name($prefix.'.frontend.currency.change');
        Route::get(Lang::get('routes.language.language.get',[], $prefix)            , 'CurrencyLanguageController@changeLanguage')->name($prefix.'.frontend.language.change');

        // Default
        Route::get('/login'                                                         , 'AuthController@login')->name($prefix.'.frontend.auth.login');
        Route::post('/login'                                                        , 'Auth\LoginController@login')->name($prefix.'.frontend.auth.doLogin');
        Route::get('/logout'                                                        , 'Auth\LoginController@logout')->name($prefix.'.frontend.auth.doLogout');
        Route::get(Lang::get('routes.default.register.get',[], $prefix)             , 'AuthController@register')->name($prefix.'.frontend.auth.register');
        Route::post(Lang::get('routes.default.register.post',[], $prefix)           , 'AuthController@doRegister')->name($prefix.'.frontend.auth.doRegister');
        Route::get(Lang::get('routes.default.recover-account.get',[], $prefix)      , 'AuthController@recovery')->name($prefix.'.frontend.auth.recovery');
        Route::post(Lang::get('routes.default.recover-password.post',[], $prefix)   , 'AuthController@doRecoveryPassword')->name($prefix.'.frontend.auth.doRecoveryPassword');
        Route::post(Lang::get('routes.default.recover-login.post',[], $prefix)      , 'AuthController@doRecoveryUsername')->name($prefix.'.frontend.auth.doRecoveryUsername');
        Route::get(Lang::get('routes.default.verify-account.get',[], $prefix)       , 'AuthController@doVerifyAccount')->name($prefix.'.frontend.auth.doVerifyAccount');
        Route::get(Lang::get('routes.default.newsletter.get',[], $prefix)           , 'NewsletterController@success')->name($prefix.'.frontend.newsletter.success');
        Route::post(Lang::get('routes.default.newsletter.post',[], $prefix)         , 'NewsletterController@store')->name($prefix.'.frontend.newsletter.store');
        Route::get(Lang::get('routes.default.view-recover-password.get',[], $prefix)      , 'AuthController@viewNewPassword')->name($prefix.'.frontend.auth.view-recover-password');
        Route::post(Lang::get('routes.default.do-recover-password.post',[], $prefix)      , 'AuthController@doNewPassword')->name($prefix.'.frontend.auth.do-recover-password');


        // My account
        Route::group(['prefix' => Lang::get('routes.myaccount.prefix',[], $prefix)], function () use ($prefix) {
            Route::get(Lang::get('routes.myaccount.default.get',[], $prefix)                   , 'MyAccountController@index')->name($prefix.'.frontend.my-account.index');
            Route::get(Lang::get('routes.myaccount.register.get-register',[], $prefix)          , 'MyAccountController@show')->name($prefix.'.frontend.my-account.show');
            Route::get(Lang::get('routes.myaccount.register.get-register-change',[], $prefix)   , 'MyAccountController@edit')->name($prefix.'.frontend.my-account.edit');
            Route::post(Lang::get('routes.myaccount.register.post-register-change',[], $prefix) , 'MyAccountController@update')->name($prefix.'.frontend.my-account.update');
            Route::get(Lang::get('routes.myaccount.register.get-register-password-change',[], $prefix), 'MyAccountController@editPassword')->name($prefix.'.frontend.my-account.editPassword');
            Route::post(Lang::get('routes.myaccount.register.post-register-password-change',[], $prefix), 'MyAccountController@updatePassword')->name($prefix.'.frontend.my-account.updatePassword');
            Route::get(Lang::get('routes.myaccount.reservation.get-active',[], $prefix)         , 'MyAccountController@showActiveBookings')->name($prefix.'.frontend.my-account.bookings.active');
            Route::get(Lang::get('routes.myaccount.reservation.get-passed',[], $prefix)         , 'MyAccountController@showPastBookings')->name($prefix.'.frontend.my-account.bookings.past');
            Route::get(Lang::get('routes.myaccount.reservation.get-reservation',[], $prefix)    , 'MyAccountController@showBooking')->name($prefix.'.frontend.my-account.bookings.show');
            Route::get(Lang::get('routes.myaccount.reservation.get-contract',[], $prefix)       , 'MyAccountController@showBookingContract')->name($prefix.'.frontend.my-account.bookings.showContract');
            Route::get(Lang::get('routes.myaccount.reservation.get-invoice',[], $prefix)        , 'MyAccountController@showBookingInvoice')->name($prefix.'.frontend.my-account.bookings.showInvoice');
            Route::get(Lang::get('routes.myaccount.reservation.get-change-payment',[], $prefix)     , 'MyAccountController@changePaymentMethod')->name($prefix.'.frontend.my-account.bookings.changePaymentMethod');
            Route::post(Lang::get('routes.myaccount.reservation.post-change-payment',[], $prefix)   , 'MyAccountController@doChangePaymentMethod')->name($prefix.'.frontend.my-account.bookings.doChangePaymentMethod');
            Route::post(Lang::get('routes.myaccount.reservation.post-billet-generation',[], $prefix)    , 'MyAccountController@generateBilletBill')->name($prefix.'.frontend.my-account.bookings.generateBilletBill');
            Route::get(Lang::get('routes.myaccount.reservation.vouchers.get-voucher',[], $prefix)       , 'BookingVouchersController@voucher')->name($prefix.'.frontend.my-account.bookings.voucher');
            Route::get(Lang::get('routes.myaccount.reservation.vouchers.get-voucher-file',[], $prefix)  , 'BookingVouchersController@voucherFile')->name($prefix.'.frontend.my-account.bookings.voucherFile');
            Route::get(Lang::get('routes.myaccount.reservation.credit-card-payment',[], $prefix)    , 'PaymentController@creditCardPaymentForm')->name($prefix.'.frontend.my-account.bookings.credit-card-payment');
            Route::post(Lang::get('routes.myaccount.reservation.do-payment',[], $prefix)             , 'PaymentController@doPayment')->name($prefix.'.frontend.my-account.bookings.reservation.do-payment');
            Route::get(Lang::get('routes.myaccount.reservation.failed-payment',[], $prefix)         , 'PaymentController@failedPayment')->name($prefix.'.frontend.my-account.bookings.reservation.failed-payment');
            Route::get(Lang::get('routes.myaccount.reservation.approved-payment',[], $prefix)       , 'PaymentController@approvedPayment')->name($prefix.'.frontend.my-account.bookings.reservation.approved-payment');
        });

        Route::get(Lang::get('/{slug}',[], $prefix) , 'PageController@show')->name($prefix.'.frontend.pages.show');
        // Package
        Route::group(['prefix' => Lang::get('routes.package.prefix',[], $prefix)], function () use ($prefix)  {
            Route::get(Lang::get('routes.package.details.list',[], $prefix), 'PackageController@index')->name($prefix.'.frontend.packages.index');
            Route::get(Lang::get('routes.package.details.get',[], $prefix), 'PackageController@show')->name($prefix.'.frontend.packages.show');
            Route::get(Lang::get('routes.package.details.exclusive.get',[], $prefix), 'PackageController@token')->name($prefix.'.frontend.packages.exclusive.show');
            Route::post(Lang::get('routes.package.details.post',[], $prefix), 'PackageController@book')->name($prefix.'.frontend.packages.book');
            Route::get(Lang::get('routes.package.search.get',[], $prefix), 'PackageController@search')->name($prefix.'.frontend.packages.search');
        });

        // Prebooking
        Route::group(['prefix' => Lang::get('routes.prebooking.prefix',[], $prefix)], function () use ($prefix) {
            Route::get(Lang::get('routes.prebooking.prebooking.get',[], $prefix)    , 'PrebookingController@create')->name($prefix.'.frontend.prebookings.create');
            Route::post(Lang::get('routes.prebooking.prebooking.post',[], $prefix)  , 'PrebookingController@store')->name($prefix.'.frontend.prebookings.store');
        });

        // Pages Routing
        Route::get(Lang::get('routes.pages.contact',[], $prefix), 'PageController@show')->name($prefix.'.frontend.pages.contact');
        Route::get(Lang::get('routes.pages.privacy_policy',[], $prefix), 'PageController@show')->name($prefix.'.frontend.pages.privacy_policy');
        Route::get(Lang::get('routes.pages.terms_use',[], $prefix), 'PageController@show')->name($prefix.'.frontend.pages.terms_use');

    });
}

// Webhook - Call backs
Route::post('/booking/shopline_return', 'WebhookController@shopline_return')->name('frontend.webhook.shopline_return');


// Static Pages
//Route::get('/{slug}', 'PageController@show')->name('frontend.pages.show');
