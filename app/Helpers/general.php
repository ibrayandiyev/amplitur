<?php

if (!function_exists('convertFixUtf8')) {
    
    function convertFixUtf8($string){
        $string = utf8_encode(($string));
        return $string;
    }
}

if (!function_exists('emailBackoffice')) {
    /**
     * Get remote ip address considering cloudflare
     *
     * @return  string
     */
    function emailBackoffice()
    {
        $emailBackoffice = env('MAIL_BACKOFFICE', '');
        return $emailBackoffice;
    }
}

if (!function_exists('clearPromocode')) {
    /**
     * Get remote ip address considering cloudflare
     *
     * @return  string
     */
    function clearPromocode()
    {
        session()->put('promocode', null);
    }
}

if (!function_exists('validateDate')) {
    /**
     * Validate a date
     *
     * @return  string
     * @ref: https://www.codexworld.com/how-to/validate-date-input-string-in-php/
     */
    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

