<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Webhooks\ShoplineReturnRequest;
use App\Repositories\BookingBillRepository;
use App\Repositories\BookingRepository;
use Exception;
use Libs\Itau\Itau;
use Libs\Itau\Itaucripto;

class WebhookController extends Controller
{
    /**
     * @var BookingRepository
     */
    protected $repositoryBooking;

    /**
     * @var BookingBillRepository
     */
    protected $repositoryBookingBill;

    public function __construct(BookingRepository $repositoryBooking,
        BookingBillRepository $repositoryBookingBill)
    {
        $this->repositoryBooking        = $repositoryBooking;
        $this->repositoryBookingBill    = $repositoryBookingBill;
    }

    public function shopline_return(ShoplineReturnRequest $request)
    {
        try {
            //print_r($_POST);
		/*
		 * Exemplo do retorno:
		 * Array ( [__VIEWSTATE] => /wEPDwULLTE1MDM1MDg5NTgPZBYCAgMPFgIeBmFjdGlvbgU6aHR0cHM6Ly93d3cuYW1wbGl0dXIuY29tLmJyL2JldGEvcmVzZXJ2YXMvcmV0b3Jub19zaG9wbGluZWRk1+3hUZP3pjL17dqQ3UtYg3dICaU= [__EVENTVALIDATION] => /wEWBgL6nqGDDQLc757wDALGiujeCQK4j/S9CwL9zpaaBwKb1oO5BZtcPluKs3HidlOkWWToiYJuZxi+ [DC] => I208O41V8R107S44W163S206P130N67C8K40W65C206O93W55I251M56J69U108S135Y160B231P88O64Q158N91X33A221O236A71V51H113U200Q83E93Y120Y 
		 * [codEmp] => J0801936590001400000018867 [pedido] => 00013310 [chave] => HK7V3J2L42MQI5DW [tipPag] => 01 ) 
		 * 
		 * 
		 */
        $attributes = $request->all();

        $pedido             = $attributes['pedido'];	// Atenção, observar o retorno do pedido. Os dois primeiros digitos são a parcela do recebível e os outros 6 o RID!
		$parcela_recebivel  = substr($pedido, 0, 2);
		$rid = $reserva_id  = substr($pedido, 2, 6);;
		
		$dbreserva = $this->repositoryBooking->find($reserva_id);
		if($rid == "" || !$dbreserva) {
            throw new Exception("Booking missing.");
			return;
		}
		$bookingBill 		        = $this->repositoryBookingBill->getModel()->where('booking_id', '=', $rid)
            ->where("installment", "=", (int) $parcela_recebivel)
            ->where("ct", "=", (int) $parcela_recebivel)
            ->first();

        $dc			= $attributes['DC'];
		$chave		= $attributes['chave'];
		$codEmp		= $attributes['codEmp'];
		$tipPag		= $attributes['tipPag'];

		$cripto 			    = new Itaucripto();
		$data['cripto'] 	    = $cripto->decripto($dc, Itau::$chave);
		$data['numPedido']	    = $cripto->retornaPedido();
		$data['tipPag'] 	    = $cripto->retornaTipPag();
		$parcela_shopline		= $bookingBill->installment;		// Atenção, esse número é o que vai na frente do ID do pedido para o Itaú. Ex: RID=13310, parcela 1, o formato final será: 01013310
		$parcela_controle 		= $bookingBill->ct;
		$_dados = null;
		switch($data['tipPag']){
			case Itau::$tipoPagamentoBoleto:
				$complemento	= "Boleto - Aguardando pagamento - Parcela {$parcela_shopline}";
				break;
			case Itau::$tipoPagamentoAVista:
				$complemento	= "Pagamento à vista (TEF/CDC) - Em processamento - Parcela {$parcela_shopline}";
				break;
			case Itau::$tipoPagamentoCC:
				$complemento	= "Cartão de Crédito - Parcela {$parcela_shopline}.";
				break;
			default:
				$complemento = "Erro no Shopline. Reportar ao administrador do site.";
				break;
		}
		$_transaction = $this->repositoryBookingBill->payShopline($bookingBill);
		if(isset($_transaction['sitPag'])){
			switch($_transaction['sitPag']){
				case "00":
					session()->put('success', __('frontend.misc.shopline_return_success', ['payment_status' => __('frontend.misc.status_confirmada')]));
					break;
				default:
					session()->put('error', __('frontend.misc.shopline_return_error', ['payment_status' => __('frontend.misc.status_pendente')]));
					break;
			}
		}

		return redirect()->route(getRouteByLanguage('frontend.booking.finish'), ['booking' => $bookingBill->booking_id])
		;
            
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('frontend.index')->withError(__('messages.http.404'));
        }
    }
}
