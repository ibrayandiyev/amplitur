<?php

namespace App\Enums;

abstract class PaymentMethod
{
    public const PM_TYPE_INVOICE = 'invoice';
    public const PM_TYPE_CREDIT  = 'credit';
    public const PM_TYPE_PAYPAL  = 'paypal';
    public const PM_TYPE_DEBIT   = 'debit';
    public const PM_TYPE_BILLET  = 'billet';

    public const PM_CODE_CREDIT_CARD                = 'credit-card';
    public const PM_CODE_CREDIT_CARD_RECURRENCE     = 'credit-card-recurrence';
    public const PM_CODE_CASH                       = 'dinheiro';
    public const PM_CODE_CHECK                      = 'cheque';
    public const PM_CODE_ITAU_BILLET                = 'boleto-bancario-itau';
    public const PM_CODE_ITAU_TRANSFER              = 'transferencia-bancaria-itau';
    public const PM_CODE_INVOICE                    = 'invoice';


    public const PM_BILLET_DUEDAYS  = 5;


    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::PM_TYPE_INVOICE . ',';
        $string .= self::PM_TYPE_CREDIT . ',';
        $string .= self::PM_TYPE_DEBIT . ',';
        $string .= self::PM_TYPE_PAYPAL . ',';
        $string .= self::PM_TYPE_BILLET;

        return $string;
    }

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function toArray(): array
    {
        return explode(',', self::toString());
    }
}
