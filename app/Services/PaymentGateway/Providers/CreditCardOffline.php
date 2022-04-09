<?php

namespace App\Services\PaymentGateway\Providers;

use App\Enums\ProcessStatus;
use App\Events\CreditCardOfflineEvent;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Services\PaymentGateway\CreditCard;
use App\Services\PaymentGateway\PaymentGateway;
use Defuse\Crypto\Crypto;
use Exception;
use InvalidArgumentException;

class CreditCardOffline extends PaymentGateway
{
    /**
     * @var string
     */
    protected $code = 'offline';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [pay description]
     *
     * @param   Booking      $booking       [$booking description]
     * @param   BookingBill  $bookingBill   [$bookingBill description]
     * @param   int          $amount        [$amount description]
     * @param   int          $installments  [$installments description]
     * @param   string       $customerName  [$customerName description]
     * @param   CreditCard   $creditCard    [$creditCard description]
     *
     * @return  [type]                      [return description]
     */
    public function pay(Booking $booking, ?BookingBill $bookingBill = null, float $amount, int $installments = 1, string $customerName, CreditCard $creditCard, bool $mustSaveCreditCardToken = false)
    {
        if (!$bookingBill && $booking->payment_status == ProcessStatus::PAID) {
            throw new InvalidArgumentException;
        }

        if ($bookingBill && $bookingBill->isPaid()) {
            throw new InvalidArgumentException;
        }

        if (!$creditCard->isValid()) {
            throw new InvalidArgumentException;
        }

        $payloadCreditCard = clone $creditCard;
        unset($payloadCreditCard->cvv);
        $payloadCreditCard->number  = substr(0, 4, $payloadCreditCard->number);
        
        $plaintext      = json_encode($creditCard);
        $password       = sprintf("%010s", $booking->id);
        $encrypted      = Crypto::encryptWithPassword($plaintext, $password);
        $creditCard->encrypted = $encrypted;

        unset($creditCard->encrypted);
        
        $payload        = json_encode($payloadCreditCard);

        try {
            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'approved', $amount, $this->code);
        } catch (Exception $ex) {
            if (empty($ex->payload)) {
                $payload = json_encode([
                    'exception' => get_class($ex),
                    'error' => $ex->getCode(),
                    'message' => $ex->getMessage(),
                ]);
            } else {
                $payload = $ex->payload;
            }

            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'fail', $amount, $this->code);
            bugtracker()->notifyException($ex);
            throw $ex;
        }
        $transaction->encrypted = $encrypted;
        return $transaction;
    }

    /**
     * [payTokenized description]
     *
     * @param   Booking      $booking       [$booking description]
     * @param   BookingBill  $bookingBill   [$bookingBill description]
     * @param   int          $amount        [$amount description]
     * @param   int          $installments  [$installments description]
     * @param   string       $customerName  [$customerName description]
     * @param   CreditCard   $creditCard    [$creditCard description]
     *
     * @return  [type]                      [return description]
     */
    public function payTokenized(Booking $booking, ?BookingBill $bookingBill = null, int $amount, int $installments = 1, string $customerName, CreditCard $creditCard)
    {
        $this->pay($booking, $bookingBill, $amount, $installments, $customerName, $creditCard, true);
    }

}