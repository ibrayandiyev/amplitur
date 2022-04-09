<?php

namespace App\Enums\PaymentGateway;

abstract class Paypal
{
    public const COMMAND_GENERATE_TOKEN							= 'token';
	public const COMMAND_AUTHORIZE							    = 'payments';
	public const COMMAND_ACCEPT								    = 'accept';
	public const COMMAND_CONSULT							    = 'requisicao-consulta';
	public const COMMAND_CANCELLATION						    = 'requisicao-cancelamento';
	public const COMMAND_INVOICE								= 'invoice';
	
	public const PAYPAL_STATUS_ALREADY_PAID						= 'PAYMENT_ALREADY_DONE';
	public const PAYPAL_STATUS_APPROVED						    = 'approved';				// Quando foi aprovada.
	public const PAYPAL_STATUS_COMPLETE						    = 'Completed';				// Quando foi aprovada.
	public const PAYPAL_MESSAGE_ALREADY_PAID			        = "Este pagamento já foi realizado no Paypal";
	public const PAYPAL_MESSAGE_CANCELED					    = "Esta transação de pagamento foi cancelada";
	public const PAYPAL_MESSAGE_APPROVED						= "Seu pagamento foi aprovado no Paypal";
	public const PAYPAL_MESSAGE_INVOICE_SENT				    = "Uma fatura foi enviada para seu email de cadastro.";
	
	public const PAYPAL_CHECKOUT_SUCCESS_ACK					= "Success";
	public const PAYPAL_CHECKOUT_SUCCESSW_ACK					= "SuccessWithWarning";
	public const PAYPAL_CHECKOUT_FAILURE_ACK					= "Failure";
	
	public const RETURN_CODE_DUPLICATE_INVOICE				    = 10412 ;
	

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::COMMAND_GENERATE_TOKEN . ',';
        $string .= self::COMMAND_AUTHORIZE . ',';
        $string .= self::COMMAND_ACCEPT . ',';
        $string .= self::COMMAND_CONSULT . ',';
        $string .= self::COMMAND_CANCELLATION . ',';
        $string .= self::COMMAND_AUTHORIZE;

        return $string;
    }

    /**
     * [toArray description]
     *
     * @return  array   [return description]
     */
    public static function toArray(): array
    {
        return explode(',', self::toString());
    }
}
