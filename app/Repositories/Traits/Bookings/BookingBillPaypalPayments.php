<?php

namespace App\Repositories\Traits\Bookings;

use App\Enums\Processor;
use App\Enums\ProcessStatus;
use App\Exceptions\Bookings\InvalidPaymentOperationException;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Services\PaymentGateway\CreditCard;
use App\Services\PaymentGateway\Providers\Cielo;
use App\Services\PaymentGateway\Providers\Paypal;

trait BookingBillPaypalPayments
{
    /**
     * [payBookingPaypalInstallments description]
     *
     * @param   Booking  $booking            [$booking description]
     * @param   array    $paymentAttributes  [$paymentAttributes description]
     *
     * @return  [type]                       [return description]
     */
    public function payBookingPaypalInstallments(Booking $booking, array $paymentAttributes)
    {
        if ($booking->payment_status != ProcessStatus::PENDING) {
            return;
        }
        $onePayment = false;
        
        $installment = (isset($paymentAttributes['installment'])?$paymentAttributes['installment']:1);

        $bookingBillGroups = $booking->bookingBills()
            ->where('status', ProcessStatus::PENDING)
            ->where('payment_method_id', $paymentAttributes['payment_method_id'])
            ->orderBy('installment')
            ->get()
            ;
        $processor_processed = "";
        foreach ($bookingBillGroups as $processor => $bookingBillGroup) {
            $paymentMethod  = $bookingBillGroup->paymentMethod;
            $bookingBill    = $bookingBillGroup; // we get the first booking bill because it have the reference for payment,
            if($processor_processed == $bookingBillGroup->processor){
                continue;
            }
            $processor_processed = $bookingBillGroup->processor;
            switch($processor){
                case Processor::PAYPAL:
                    $total          = $bookingBillGroup->total;
                    $description    = $booking->package->getTranslation('description', language());
                    $_post_array['PAYMENTREQUEST_0_AMT']        = number_format($total,2,".","");
                    $_post_array['PAYMENTREQUEST_0_ITEMAMT']    = number_format($total,2,".","");
                    $_post_array['PAYMENTREQUEST_0_INVNUM']     = $booking->id."-". $bookingBillGroup->installment;
                    $_post_array['L_PAYMENTREQUEST_0_NAME0']    = $booking->id."/". $bookingBillGroup->installment." : ". $booking->package->name;
                    $_post_array['L_PAYMENTREQUEST_0_DESC0']    = substr($description, 0, 120);

                    if(count($booking->bookingPassengerAdditionals)){
                        $counter    = 1;
                        foreach($booking->bookingPassengerAdditionals as $bookingPassengerAdditionals){
                            $name = $bookingPassengerAdditionals->additional->name .". ";
                            $_post_array['L_PAYMENTREQUEST_0_DESC'.$counter] 	= substr($name, 0,126);
                            $counter++;
                        }
                    }

                    $_post_array['L_PAYMENTREQUEST_0_AMT0'] = number_format($bookingBillGroup->total,2,".","");
                    $_post_array['L_PAYMENTREQUEST_0_QTY0'] = 1;//count($reserva['passageiros']);	// O número de passageiros seria a quantidade
                    $_post_array['HDRIMG']					= Paypal::$logo_amplitur_header;
                    $_post_array['LOCALECODE']				= language();
                    $_post_array['currency_id']				= $bookingBillGroup->currency_id;
                    
                    $onePayment = true; // This will control if it is only one time payment (like parcels buy)
                        
                    /** @var Paypal $service */
                    $service = app(Paypal::class);
                    $service->pay($booking, $bookingBill, $_post_array);
                    break;
            }
        }
        throw new InvalidPaymentOperationException();
    }

    /**
     * [payPaypal description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function payPaypal(BookingBill $bookingBill, CreditCard $creditCard)
    {
        /** @var Cielo $service */
        $service = app(Cielo::class);

        $service->pay($bookingBill->booking, $bookingBill, (int)($bookingBill->total * 100), 1, $bookingBill->booking->getClientName(), $creditCard);
    }

}