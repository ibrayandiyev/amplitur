<?php

namespace App\Enums;

abstract class Transactions
{
    public const STATUS_SUCCESS         = 'success';
    public const STATUS_FAIL            = 'fail';
    public const STATUS_APPROVED        = 'approved';

    public const OPERATION_PAYMENT      = 'payment';
    public const OPERATION_REFUND       = 'refund';
    public const OPERATION_CANCEL       = 'cancel';
    public const OPERATION_FAIL         = 'fail';
    public const OPERATION_DEFAULT      = 'default';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString($selection=null): string
    {
        $string = '';

        if($selection == 'status'){
            $string .= self::STATUS_APPROVED . ',';
            $string .= self::STATUS_FAIL . ',';
            $string .= self::STATUS_SUCCESS;
        }
        if($selection == 'operation'){
            $string .= self::OPERATION_PAYMENT . ',';
            $string .= self::OPERATION_REFUND . ',';
            $string .= self::OPERATION_CANCEL . ',';
            $string .= self::OPERATION_FAIL . ',';
            $string .= self::OPERATION_DEFAULT;
        }

        return $string;
    }

}
