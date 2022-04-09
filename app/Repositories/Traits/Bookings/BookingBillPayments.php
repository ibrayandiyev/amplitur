<?php

namespace App\Repositories\Traits\Bookings;

use App\Enums\PaymentMethod as EnumsPaymentMethod;
use App\Enums\Processor;
use App\Enums\ProcessStatus;
use App\Enums\Transactions;
use App\Exceptions\Bookings\BillCannotRefundException;
use App\Exceptions\InvalidTotalCanceBookingBillException;
use App\Exceptions\InvalidTransactionIdCanceBookingBillException;
use App\Exceptions\PaymentErrorException;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Repositories\BookingBillRefundRepository;
use App\Services\PaymentGateway\CreditCard;
use App\Services\PaymentGateway\Providers\Cielo;
use App\Services\PaymentGateway\Providers\CreditCardOffline;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Libs\Itau\Itau;

trait BookingBillPayments
{
    /**
     * [pay description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function cancel(BookingBill $bookingBill, $_data=[])
    {
        if (!$bookingBill->canBeRefunded()) {
            throw new BillCannotRefundException();
            return;
        }

        $booking        = $bookingBill->booking;
        $value_refunded = 0;

        switch($bookingBill->processor){
            case Processor::CIELO:
                $bookingBillGroups = $booking->bookingBills()
                ->where('processor', Processor::CIELO)
                ->orderBy('installment')
                ->get()
                ;
                $total_paid = $bookingBillGroups->sum('total');
                if(!isset($_data['total']) || str_replace(",",".", $_data['total']) <=0){
                    throw new InvalidTotalCanceBookingBillException();
                }
                if(!isset($_data['transaction_id']) || $_data['transaction_id'] <=0){
                    throw new InvalidTransactionIdCanceBookingBillException();
                }
                $total_converted    = str_replace(",",".", $_data['total']);
                $value_refunded = $this->getRefundsBookingBills($bookingBill, Processor::CIELO);
                if(($value_refunded + $total_converted) > $total_paid){
                    throw new InvalidTotalCanceBookingBillException();
                }
                $transaction        = $bookingBill->transactions()->where("id", "=", $_data['transaction_id'])->first();
                if(!$transaction){
                    throw new InvalidTransactionIdCanceBookingBillException();
                }
                $_processor_data    = json_decode($transaction->payload);
                $paymentId          = basename($_processor_data->links[0]->Href);
                $tid                = $_processor_data->tid;
                $total              = moneyFloat($total_converted, Currency::where('code', 'BRL')->first(), $booking->currency);
                $total              = (int)($total * 100);
                /** @var Cielo $service */
                $service = app(Cielo::class);

                $transaction = $service->cancel($booking, $bookingBill, $paymentId, $total);

                if($transaction->status == Transactions::STATUS_SUCCESS){
                    $this->storeBookingBillRefunds($bookingBill, $total_converted);
                    if($this->getRefundsBookingBills($bookingBill) + $total_converted >= $total_paid){
                        foreach($bookingBillGroups as $bookingBill){
                            $this->setAsCanceled($bookingBill);
                        }
                    }
                }
            break;
            default;
            break;
        }

    }

    /**
     * [updateBookingPaymentStatus description]
     *
     * @param   Booking  $booking            [$booking description]
     * @param   array    $paymentAttributes  [$paymentAttributes description]
     *
     * @return  [type]                       [return description]
     */
    public function updateBookingPaymentStatus(Booking $booking)
    {
        if (!$booking->bookingBillPaymentPendings()) {
            return;
        }

        if ($booking->isPaid()) {
            return;
        }

        try {
            DB::beginTransaction();

            $this->setAsPaid($booking);

            DB::commit();
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * [payBookingCreditCardInstallments description]
     *
     * @param   Booking  $booking            [$booking description]
     * @param   array    $paymentAttributes  [$paymentAttributes description]
     *
     * @return  [type]                       [return description]
     */
    public function payBookingCreditCardInstallments(Booking $booking, array $paymentAttributes)
    {
        if ($booking->payment_status != ProcessStatus::PENDING && 
        $booking->payment_status != ProcessStatus::ON_GOING) {
            throw new Exception(__("frontend.booking.booking-cannot-be-paid"));
            return false;
        }

        $onePayment = false;
        $creditCard = new CreditCard(
            CreditCard::flag(str_replace(' ', '', $paymentAttributes['number'])),
            $paymentAttributes['holder'],
            $paymentAttributes['number'],
            $paymentAttributes['expirationDate'],
            $paymentAttributes['cvv']
        );
        $paymentMethod  = app(PaymentMethod::class)->find($paymentAttributes['payment_method_id']);

        $installment = null;
        switch($paymentMethod->code){
            case EnumsPaymentMethod::PM_CODE_CREDIT_CARD_RECURRENCE:
                $installment = (isset($paymentAttributes['installment'])?$paymentAttributes['installment']:1);
                break;
        }

        $bookingBillGroups = $booking->bookingBills()
            ->where('status', ProcessStatus::PENDING)
            ->where('payment_method_id', $paymentAttributes['payment_method_id'])
            ->orderBy('installment');

        if($installment != null){
            $bookingBillGroups = $bookingBillGroups->where("installment", $installment);
        }
        $bookingBillGroups = $bookingBillGroups->get();
        
        $processor_processed = "";
        foreach ($bookingBillGroups as $processor => $bookingBillGroup) {
            $paymentMethod  = $bookingBillGroup->paymentMethod;
            $bookingBill    = $bookingBillGroup; // we get the first booking bill because it have the reference for payment,
            if($processor_processed == $bookingBillGroup->processor){
                continue;
            }
            $processor_processed = $bookingBillGroup->processor;
            switch($processor){
                case Processor::CIELO:
                    // We choose from what the credit card type the charge mode would be set.
                    switch($paymentMethod->code){
                        default:
                        case EnumsPaymentMethod::PM_CODE_CREDIT_CARD:
                            $total = $bookingBillGroups->sum('total');
                            $total = moneyFloat($total, Currency::where('code', 'BRL')->first(), $booking->currency);
                            $total = ((int) ($total * 100));
                            $installments = count($bookingBillGroups);
                            $onePayment = true; // This will control if it is only one time payment (like parcels buy)
                            break;
                        case EnumsPaymentMethod::PM_CODE_CREDIT_CARD_RECURRENCE:
                            $total = $bookingBillGroup->total;
                            $total = moneyFloat($total, Currency::where('code', 'BRL')->first(), $booking->currency);
                            $total = ((int) ($total * 100));
                            $installments = $bookingBillGroup->installment;
                            break;
                    }
                    /** @var Cielo $service */
                    $service = app(Cielo::class);
                    $transaction = $service->pay($booking, $bookingBill, $total, $installments, $booking->getClientName(), $creditCard);
                    if($transaction->status == Transactions::STATUS_SUCCESS){
                        $_payload = json_decode($transaction->payload, true);;
                        $tid        = isset($_payload['tid'])?$_payload['tid']:"";
                        $nsu        = isset($_payload['authorizationCode'])?$_payload['authorizationCode']:"";
                        $message    = isset($_payload['returnMessage'])?$_payload['returnMessage']:"";
                        $this->addSuccessMessage(__("frontend.misc.cartao_aprovado", [
                            'message' => $message, 
                            'tid' => $tid, 
                            'nsu' => $nsu, 
                            'value' => moneyDecimal($transaction->amount)
                        ]
                        ));
                    }
                    break;
            }
            if($onePayment){
                foreach ($bookingBillGroups as $bk) {
                    $this->setAsPaid($bk);
                }
            }else{
                $this->setAsPaid($bookingBill);
            }
        }
    }

    /**
     * [payBookingCreditCardRecurrent description]
     *
     * @param   Booking  $booking            [$booking description]
     * @param   array    $paymentAttributes  [$paymentAttributes description]
     *
     * @return  [type]                       [return description]
     */
    public function payBookingCreditCardRecurrent(Booking $booking, array $paymentAttributes)
    {
        if ($booking->payment_status != ProcessStatus::PENDING) {
            return;
        }

        $creditCard = new CreditCard(
            CreditCard::flag(str_replace(' ', '', $paymentAttributes['number'])),
            $paymentAttributes['holder'],
            $paymentAttributes['number'],
            $paymentAttributes['expirationDate'],
            $paymentAttributes['cvv'],
        );

        $paymetMethodIds = PaymentMethod::whereIn('code', [
            'credit-card',
        ])
            ->get()
            ->pluck('id');

        $bookingBillGroups = $booking->bookingBills()
            ->where('status', ProcessStatus::PENDING)
            ->whereIn('payment_method_id', $paymetMethodIds)
            ->orderBy('expires_at')
            ->get()
            ->groupBy('processor');

        foreach ($bookingBillGroups as $processor => $bookingBillGroup) {
            $bookingBill = $bookingBillGroup->first();

            if ($bookingBill->expires_at > Carbon::now()) {
                continue;
            }

            $total = $bookingBill->getBrlTotal();
            $total = ((int) $total * 100);

            if ($processor == Processor::CIELO) {
                /** @var Cielo $service */
                $service = app(Cielo::class);

                $service->pay($booking, $bookingBill, $total, 1, $booking->getClientName(), $creditCard);
            }

            continue;
        }
    }

    /**
     * [payBookingCreditCardOffline description]
     *
     * @param   Booking  $booking            [$booking description]
     * @param   array    $paymentAttributes  [$paymentAttributes description]
     *
     * @return  [type]                       [return description]
     */
    public function payBookingCreditCardOffline(Booking $booking, array $paymentAttributes)
    {
        if ($booking->payment_status != ProcessStatus::PENDING) {
            return;
        }

        $creditCard = new CreditCard(
            CreditCard::flag(str_replace(' ', '', $paymentAttributes['number'])),
            $paymentAttributes['holder'],
            $paymentAttributes['number'],
            $paymentAttributes['expirationDate'],
            $paymentAttributes['cvv']
        );

        $paymetMethodIds = PaymentMethod::whereIn('code', [
                'credit-card',
            ])
            ->get()
            ->pluck('id');

        $bookingBillGroups = $booking->bookingBills()
            ->where('status', ProcessStatus::PENDING)
            ->whereIn('payment_method_id', $paymetMethodIds)
            ->get()
            ->groupBy('processor');

        foreach ($bookingBillGroups as $processor => $bookingBillGroup) {
            $total = $bookingBillGroup->sum('total');
            $total = moneyFloat($total, Currency::where('code', 'BRL')->first(), $booking->currency);
            $installments = count($bookingBillGroup);

            if ($processor == Processor::OFFLINE) {
                /** @var CreditCardOffline $service */
                $service = app(CreditCardOffline::class);

                $transaction = $service->pay($booking, null, $total, $installments, $booking->getClientName(), $creditCard);
                $this->bookingRepository->addEmailData([
                    'card_data' => true, 
                    'processor' => Processor::OFFLINE,
                    'credit-card-offline' => ['encrypted' => $transaction->encrypted]]);
            }

        }
    }

    /**
     * [pay description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     * @param   array|null   $attributes   [$attributes description]
     * 
     * @return  [type]                     [return description]
     */
    public function pay(BookingBill $bookingBill, ?array $attributes = null): BookingBill
    {
        if (!$bookingBill->canBePaid()) {
            return $bookingBill;
        }

        $this->setAsPaid($bookingBill);

        return $bookingBill->fresh();
    }

    /**
     * [payCielo description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function payCielo(BookingBill $bookingBill, CreditCard $creditCard)
    {
        /** @var Cielo $service */
        $service = app(Cielo::class);

        $service->pay($bookingBill->booking, $bookingBill, (int)($bookingBill->total * 100), 1, $bookingBill->booking->getClientName(), $creditCard);
    }


    /**
     * [payShopline description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function payShopline(BookingBill $bookingBill)
    {
        /** @var Shopline $service */
        $service = app(Shopline::class);
        $somente_consulta = 0;

        $_transaction   = $service->doItauProcess($bookingBill->booking, $bookingBill);

        $msgHistorico   = "";
		$complemento    = "Shopline - sem escolha do pagamento.";
		if(isset($_transaction) && $_transaction != null && $somente_consulta == 0){
			switch($_transaction['tipPag']){
				case Itau::$tipoPagamentoBoleto:
					$complemento	= "boleto";
					$msgHistorico	= "Boleto ";
					break;
				case Itau::$tipoPagamentoAVista:
					$complemento	= "avista";
					$msgHistorico	= "Pagamento à vista (TEF/CDC) ";
					break;
				case Itau::$tipoPagamentoCC:
					$complemento	= "cartaocredito";
					$msgHistorico	= "Cartão de Crédito ";
					break;
			}
		}

        if(isset($_transaction['sitPag']) && $_transaction['sitPag'] == Itau::$flagPagamentoEfetuado){
            $this->pay($bookingBill);
            $valor_transacao    = str_replace(",",".",$_transaction['Valor']);
			$msg_retorno        = null;
			switch($_transaction['sitPag']){
				case '03':
				case 3:
					break;
				case '04':
				case 4:
					if($msg_retorno == null){
						$msg_retorno = $_transaction['codAut'] ." - ". $_transaction['sitPagMsg'] ." parcela {$bookingBill->ct}";
					}
				default:
					if($msg_retorno == null){
						$msg_retorno = $_transaction['codAut'] ." - ". $_transaction['sitPagMsg'];
					}
                    $raw_data       = json_encode($_transaction);
					if($msg_retorno != " - "){
                        $mensagem = json_encode(array(
                            'tipo'	 			=> '-',
                            'processador'		=> 'shopline',
                            'num_pedido'	 	=> $bookingBill->booking_id,
                            'cod_retorno'		=> $_transaction['sitPag'],
                            'msg_retorno'		=> $msg_retorno,
                            'cod_conf_retorno'	=> $_transaction['sitPag'],
                            'user_id' 			=> "",
                            'pacote_id' 		=> "",
                            'parcelas' 				=> $bookingBill->installment,
                            'rid' 				=> $bookingBill->booking_id,
                            'istest' 			=> "",
                            'cod_pais' 			=> "BR",	
                            'formapag'			=> $complemento,
                            'raw_data'			=> $raw_data,
                            'valor_transacao'   => $valor_transacao
                        ));
					}
			}
        }else{
            $mensagem = __('frontend.financeiro.message_transaction_shopline') . $msgHistorico . " - ". __('frontend.financeiro.message_bill_not_paid');
        }
        $this->logging->bookingBillShopline($bookingBill->booking, $bookingBill, $mensagem);

        $this->bookingRepository->updateBookingPaymentStatus($bookingBill->booking);
        return $_transaction;
    }
    
    /**
     * [storeBookingBillRefunds description]
     *
     * @param   [type]  $booking        [$booking description]
     * @param   [type]  $value          [float
     *
     * @return  [type]                  [return description]
     */
    public function storeBookingBillRefunds($bookingBill, $value=0)
    {
        $bookingBillRefund = app(BookingBillRefundRepository::class)
            ->store([
                'booking_id'         => $bookingBill->booking_id,
                'booking_bill_id'    => $bookingBill->id,
                'user_id'           => $this->getAuthUser(),
                'value'             => $value,
                'refunded_at'       => Carbon::now(),
                'status'            => ProcessStatus::CONFIRMED,
            ]);

        return $bookingBillRefund;
    }
}