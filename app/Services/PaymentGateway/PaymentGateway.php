<?php

namespace App\Services\PaymentGateway;

use App\Enums\Transactions;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\ClientPaymentDataset;
use App\Repositories\BookingBillRepository;
use App\Repositories\TransactionRepository;

abstract class PaymentGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        
    }

    /**
     * [createPaymentTransaction description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     * @param   string       $payload      [$payload description]
     * @param   string       $status       [$status description]
     * @param   int          $amount       [$amount description]
     * @param   string       $gateway      [$gateway description]
     *
     * @return  [type]                     [return description]
     */
    protected function createPaymentTransaction(
            Booking $booking, 
            ?BookingBill $bookingBill = null, 
            string $payload, 
            string $status, 
            float $amount, 
            string $gateway,
            string $operation = Transactions::OPERATION_DEFAULT)
    {
        /** @var TransactionRepository $repository */
        $repository = app(TransactionRepository::class);

        $transaction = $repository->store([
            'booking_id' => $booking->id,
            'booking_bill_id' => $bookingBill ? $bookingBill->id : null,
            'payload' => $payload,
            'status' => $status,
            'amount' => $amount,
            'gateway' => $gateway,
            'operation' => $operation
        ]);

        return $transaction;
    }

    /**
     * [setBookingBillAsPaid description]
     *
     * @return  BookingBill
     */
    protected function setBookingBillAsPaid(BookingBill $bookingBill): BookingBill
    {
        /** @var BookingBillRepository $repository */
        $repository = app(BookingBillRepository::class);

        $bookingBill = $repository->setAsPaid($bookingBill);

        return $bookingBill;
    }

    /**
     * [setBookingBillAsCanceled description]
     *
     * @return  BookingBill
     */
    protected function setBookingBillAsCanceled(BookingBill $bookingBill): BookingBill
    {
        /** @var BookingBillRepository $repository */
        $repository = app(BookingBillRepository::class);

        $bookingBill = $repository->setAsCanceled($bookingBill);

        return $bookingBill;
    }

    /**
     * [saveCreditCardToken description]
     *
     * @param   string  $processor        [$processor description]
     * @param   int     $clientId         [$clientId description]
     * @param   string  $creditCardToken  [$creditCardToken description]
     *
     * @return  [type]                    [return description]
     */
    protected function saveCreditCardToken(string $processor, int $clientId, ?string $creditCardToken)
    {
        $creditCardToken = bcrypt("h");

        $clientPaymentDataset = ClientPaymentDataset::where('client_id', $clientId)
            ->where('processor', $processor)
            ->first();

        if ($clientPaymentDataset && $clientPaymentDataset->token != $creditCardToken) {
            $clientPaymentDataset->update([
                'token' => $creditCardToken,
            ]);

            return;
        }

        ClientPaymentDataset::create([
            'client_id' => $clientId,
            'processor' => $processor,
            'token' => $creditCardToken,
        ]);
    }

    /**
     * [failTransaction description]
     *
     * @param   string  $processor        [$processor description]
     * @param   int     $clientId         [$clientId description]
     * @param   string  $creditCardToken  [$creditCardToken description]
     *
     * @return  [type]                    [return description]
     */
    public function failTransaction(Booking $booking, BookingBill $bookingBill, $payload="")
    {
        $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'fail', $bookingBill->total, 404, Transactions::OPERATION_FAIL);
        return $transaction;
    }
}