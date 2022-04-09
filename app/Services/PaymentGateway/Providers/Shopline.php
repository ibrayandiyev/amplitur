<?php

namespace App\Services\PaymentGateway\Providers;

use App\Models\Booking;
use App\Models\BookingBill;
use App\Services\PaymentGateway\BilletPayee;
use App\Services\PaymentGateway\BilletPayer;
use App\Services\PaymentGateway\PaymentGateway;
use Carbon\Carbon;
use Eduardokum\LaravelBoleto\Pessoa;
use Eduardokum\LaravelBoleto\Boleto\Banco\Itau;
use Eduardokum\LaravelBoleto\Boleto\Render\Pdf;
use Eduardokum\LaravelBoleto\Boleto\Render\Html;
use Libs\Itau\Itau as ItauItau;
use Libs\Itau\Itaucripto;

class Shopline extends PaymentGateway
{
    public $code = 'shopline';

    /**
     * [getBillet description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$booking description]
     * @param   BilletPayee  $payee        [$payee description]
     * @param   BilletPayer  $payer        [$payer description]
     * @param   int          $amount       [$amount description]
     *
     * @return  [type]                 [return description]
     */
    public function makeBillet(Booking $booking, BookingBill $bookingBill, BilletPayee $payee, BilletPayer $payer, int $amount): Itau
    {
        $settings = $this->getSettings($booking, $bookingBill, $payee, $payer, $amount);
        
        $billet = new Itau($settings);

        return $billet;
    }

    /**
     * [generateBilletPdf description]
     *
     * @param   Itau  $billet  [$billet description]
     *
     * @return  [type]           [return description]
     */
    public function generateBilletPdf(Itau $billet)
    {
        $pdf = new Pdf;
        $pdf->addBoleto($billet);

        return $pdf->gerarBoleto('D');
    }

    /**
     * [generateBilletHtml description]
     *
     * @param   Itau  $billet  [$billet description]
     *
     * @return  [type]           [return description]
     */
    public function generateBilletHtml(Itau $billet)
    {
        $html = new Html;
        $html->addBoleto($billet);
    
        return $html->gerarBoleto();
    }

    /**
     * [getSettings description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     * @param   BilletPayee  $payee        [$payee description]
     * @param   BilletPayer  $payer        [$payer description]
     * @param   int          $amount       [$amount description]
     *
     * @return  array                      [return description]
     */
    protected function getSettings(Booking $booking, BookingBill $bookingBill, BilletPayee $payee, BilletPayer $payer, int $amount): array
    {
        $payee = new Pessoa($payee->toArray());
        $payer = new Pessoa($payer->toArray());

        if ($bookingBill->installment) {
            $message = "Boleto referente à parcela {$bookingBill->installment} da compra Nº {$booking->id} feita através do site https://www.amplitur.com";
        } else {
            $message = "Boleto referente à compra N&ordm; {$booking->id} feita através do site https://www.amplitur.com";
        }

        return [
            'logo'                   => public_path('frontend/images/logo_amplitur_bradesco_original.png'),
            'dataVencimento'         => Carbon::now(),
            'valor'                  => $amount / 100,
            'multa'                  => false,
            'juros'                  => false,
            'numero'                 => $bookingBill->id,
            'numeroDocumento'        => $bookingBill->id,
            'pagador'                => $payee,
            'beneficiario'           => $payer,
            'carteira'               => env('SHOPLINE_WALLET'),
            'agencia'                => env('SHOPLINE_AGENCY'),
            'conta'                  => env('SHOPLINE_ACCOUNT'),
            'descricaoDemonstrativo' => [$message, '', ''],
            'instrucoes'             => [
                'APÓS O VENCIMENTO COBRAR MULTA DE 2% MAIS JUROS DE MORA DE 1% AO MÊS',
                '',
                '***O NÃO PAGAMENTO NOS PRAZOS INDICADOS ACARRETARÁ; EM CANCELAMENTO',
                'IMEDIATO DA COMPRA ***'
            ],
            'aceite'                 => '',
            'especieDoc'             => '',
        ];
    }

     /**
     * Handle return of Itau shopline
     *
	 * Rota para tratamento da consulta do Itau.
	 * Rodrigo - 20/06/2016
	 * pedido = vem no formato XXYYYYYY Onde XX é a parcela e o YYYYYY é o rid.
	 * parcela_controle = esse é o valor real da parcela e deve ser apresentado nos status para o cliente
     * @param   array  $attributes
     *
     * @return  array
	 */

	public function doItauProcess(Booking $booking, $somente_consulta = 0, $parcela_controle=0){
		$cripto             = new Itaucripto();
		$itau               = new ItauItau();	// Classe do Itau, não o Cripto!
		$pedido             = $booking->id;

		$itau->consultaServidorItau($cripto->geraConsulta(ItauItau::$codEmp, $pedido, ItauItau::$retornoItauXml, ItauItau::$chave), ItauItau::$retornoItauXml );

		$_dados = $itau->getXmlRetorno();
		
		$_retorno = null;
		if($_dados){
			foreach($_dados->PARAMETER->PARAM as $p) {
				if($p->attributes()){
					$a = $p->attributes();
					$_retorno[(string) $a['ID']] =  (string) $a['VALUE'];
				}
		
			}
		}
		
		return $_retorno;
	}
}