<?php

namespace App\Services\PaymentGateway\Providers;

use App\Enums\ProcessStatus;
use App\Enums\Transactions;
use App\Exceptions\PaymentErrorException;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\Transaction;
use App\Services\PaymentGateway\CreditCard;
use App\Services\PaymentGateway\PaymentGateway;
use Carbon\Carbon;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\Request\CieloRequestException;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Merchant;
use Exception;
use InvalidArgumentException;

class Cielo extends PaymentGateway
{
    /**
     * @var string
     */
    protected $code = 'cielo';

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @var Merchant
     */
    protected $merchant;

    /**
     * @var CieloEcommerce
     */
    protected $ecommerce;

    const STATUS_NOT_FINISHED = 0;
    const STATUS_AUTHORIZED = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_DENIED = 3;
    const STATUS_SUCCESSFUL = 4;
    const STATUS_VOIDED = 10;
    const STATUS_REFUNDED = 11;
    const STATUS_PENDING = 12;
    const STATUS_ABORTED = 13;
    const STATUS_SCHULED = 20;

    public function __construct()
    {
        parent::__construct();

        $this->environment = $this->getEnvironment();
        $this->merchant = $this->getMerchant();
        $this->ecommerce = new CieloEcommerce($this->merchant, $this->environment);
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
    public function pay(Booking $booking, ?BookingBill $bookingBill = null, int $amount, int $installments = 1, string $customerName, CreditCard $creditCard, bool $mustSaveCreditCardToken = false)
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

        $orderNumber = $bookingBill ? $bookingBill->getOrderNumber() : 'R' . $booking->id;

        $sale = $this->createSale($orderNumber, $amount, $installments, $customerName);
        $sale = $this->setPaymentDetails($sale, $creditCard, $mustSaveCreditCardToken);

        $amount_transaction     = $amount/100;
        try {
            $sale       = $this->ecommerce->createSale($sale);
            $payment    = $sale->getPayment();
            $payload    = json_encode($payment->jsonSerialize());
            
            if (!$this->isSuccessfull($payment)) {
                $ex = new PaymentErrorException('[CieloError] #' . $payment->getReturnCode() . ': ' . $payment->getReturnMessage(), $payment->getReturnCode());
                $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'denied', $amount_transaction, $this->code, Transactions::OPERATION_FAIL);
                $ex->payload = $payload;
                throw $ex;
            }

            if ($mustSaveCreditCardToken) {
                $creditCardToken = $sale->getPayment()->getCreditCard()->getCardToken();
                $this->saveCreditCardToken($this->code, $booking->client_id, $creditCardToken);
            }

            $mustCapture = true;

            if ($bookingBill && $mustSaveCreditCardToken) {
                $mustCapture = $bookingBill->expires_at->format('Y-m-d') == Carbon::now()->format('Y-m-d');
            }

            if ($mustCapture) {
                $paymentId = $payment->getPaymentId();
                $payment = $this->capture($paymentId);
                $payload = json_encode($payment->jsonSerialize());
                $status = $this->getTransactionStatus($payment);
                $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, $status, $amount_transaction, $this->code, Transactions::OPERATION_PAYMENT);

                if ($status == 'success' && $bookingBill) {
                    $this->setBookingBillAsPaid($bookingBill);
                }
            } else {
                $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'approved', $amount_transaction, $this->code, Transactions::OPERATION_PAYMENT);
            }
        } catch (CieloRequestException $ex) {
            $payload = json_encode([
                'exception' => get_class($ex),
                'error' => $ex->getCieloError()->getCode(),
                'message' => $ex->getCieloError()->getMessage(),
                'paymentId' => $paymentId ?? null,
            ]);

            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'fail', $amount_transaction, $this->code, Transactions::OPERATION_FAIL);
            bugtracker()->notifyException($ex);
            throw new Exception('[CieloError] ' . $ex->getCieloError()->getMessage(), $ex->getCieloError()->getCode());
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

            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'fail', $amount_transaction, $this->code, Transactions::OPERATION_FAIL);
            bugtracker()->notifyException($ex);
            throw $ex;
        }

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

    /**
     * [getSale description]
     *
     * @param   string   $paymentId  [$paymentId description]
     *
     * @return  Sale              [return description]
     */
    public function getSale(string $paymentId): Sale
    {
        return $this->ecommerce->getSale($paymentId);
    }

    public function capture(string $paymentId): Payment
    {
        return $this->ecommerce->captureSale($paymentId);
    }

    /**
     * [cancel description]
     *
     * @param   Booking      $booking       [$booking description]
     * @param   BookingBill  $bookingBill   [$bookingBill description]
     * @param   int          $paymentId     [$paymentId description]
     * @param   int          $amount        [$amount description]
     *
     * @return  Transaction                [return description]
     */
    public function cancel(Booking $booking, ?BookingBill $bookingBill = null, string $paymentId, $amount=0)
    {
        try {
            $transaction        = null;
            $amount_transaction = $amount/100;
            $cancel             = $this->ecommerce->cancelSale($paymentId, $amount);
            $payment            = $cancel;
            $payload            = json_encode($cancel->jsonSerialize());

            if (!$this->isCieloCanceled($payment)) {
                $ex = new PaymentErrorException('[CieloError] #' . $payment->getReturnCode() . ': ' . $payment->getReturnMessage(), $payment->getReturnCode());
                $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'denied', $amount_transaction, $this->code, Transactions::OPERATION_FAIL);
                $ex->payload = $payload;
                throw $ex;
            }

            $paymentId  = $payment->getPaymentId();
            $payload    = json_encode($payment->jsonSerialize());
            $status     = $this->getTransactionStatus($payment);
            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, $status, $amount_transaction, $this->code, Transactions::OPERATION_CANCEL);
            
        } catch (CieloRequestException $ex) {
            $message = ($ex->getCieloError()!=null)?$ex->getCieloError()->getMessage(): $ex->getMessage();
            $code    = ($ex->getCieloError()!=null)?$ex->getCieloError()->getCode(): $ex->getCode();
            $payload = json_encode([
                'exception' => get_class($ex),
                'error' => $code,
                'message' => $message,
                'paymentId' => $paymentId ?? null,
            ]);

            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'fail', $amount_transaction, $this->code, Transactions::OPERATION_FAIL);
            bugtracker()->notifyException($ex);
            throw new Exception('[CieloError] ' . $message, $code);
        }
        return $transaction;
    }

    /**
     * [createSale description]
     *
     * @param   string  $orderNumber  [$orderNumber description]
     *
     * @return  Sale                  [return description]
     */
    protected function createSale(string $orderNumber, int $amount, int $installments, string $customerName): Sale
    {
        $sale = new Sale($orderNumber);

        $sale->customer($customerName);
        $sale->payment($amount, $installments);

        return $sale;
    }

    /**
     * [setPaymentDetails description]
     *
     * @param   Sale        $sale                     [$sale description]
     * @param   CreditCard  $creditCard               [$creditCard description]
     * @param   bool        $mustSaveCreditCardToken  [$mustSaveCreditCardToken description]
     *
     * @return  Sale                                  [return description]
     */
    protected function setPaymentDetails(Sale $sale, CreditCard $creditCard, bool $mustSaveCreditCardToken = false): Sale
    {
        /** @var Payment $payment */
        $payment = $sale->getPayment();

        $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
            ->creditCard($creditCard->cvv, $creditCard->flag)
            ->setExpirationDate($creditCard->expirationDate)
            ->setCardNumber($creditCard->number)
            ->setHolder($creditCard->holder)
            ->setSaveCard($mustSaveCreditCardToken);

        $sale->setPayment($payment);

        return $sale;
    }

    /**
     * Get service environment instance
     *
     * @return  Environment
     */
    protected function getEnvironment()
    {
        if (env('APP_ENV', 'local') == 'local') {
            return Environment::sandbox();
        }

        if (env('APP_ENV', 'local') == 'production') {
            return Environment::production();
        }
    }

    /**
     * Get service merchant instance
     *
     * @return  Merchant
     */
    protected function getMerchant()
    {
        return new Merchant(env('CIELO_API_MERCHANT_ID'), env('CIELO_API_MERCHANT_KEY'));
    }
    
    /**
     * [getTransactionStatus description]
     *
     * @param   Payment  $payment  [$payment description]
     *
     * @return  string             [return description]
     */
    protected function getTransactionStatus(Payment $payment): string
    {
        return $this->isSuccessfull($payment) ? 'success' : 'fail'; 
    }

    /**
     * [isSuccessfull description]
     *
     * @param   Payment  $payment  [$payment description]
     *
     * @return  bool               [return description]
     */
    protected function isSuccessfull(Payment $payment): bool
    {
        return $payment->getStatus() == self::STATUS_CONFIRMED
            || $payment->getStatus() == self::STATUS_SUCCESSFUL
            || $payment->getStatus() == self::STATUS_AUTHORIZED
            || $payment->getStatus() == self::STATUS_VOIDED;
    }

    /**
     * [isCieloCanceled description]
     *
     * @param   Payment  $payment  [$payment description]
     *
     * @return  bool               [return description]
     */
    protected function isCieloCanceled(Payment $payment): bool
    {
        return $payment->getStatus() == self::STATUS_VOIDED
            || $payment->getStatus() == self::STATUS_CONFIRMED;
    }

    
}