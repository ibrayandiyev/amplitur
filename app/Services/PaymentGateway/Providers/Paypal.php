<?php

namespace App\Services\PaymentGateway\Providers;

use App\Enums\PaymentGateway\Paypal as EnumPaypal;
use App\Enums\ProcessStatus;
use App\Enums\Transactions;
use App\Exceptions\Bookings\BillApproveFailException;
use App\Exceptions\Bookings\InvalidPaymentOperationException;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Services\PaymentGateway\PaymentGateway;
use Exception;
use InvalidArgumentException;
use stdClass;

class Paypal extends PaymentGateway{
	private static $webservice_address  			= array("token" => "https://api.paypal.com/v1/oauth2/token",
													"payments" => "https://api.paypal.com/v1/payments/payment",
													"accept" => "https://api.paypal.com/v1/payments/payment/{payment_id}/execute/",
													"invoice" => "https://api-3t.paypal.com/nvp",
													"express" => "https://api-3t.paypal.com/nvp",
													"express_url" => "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=",// no final da string tem que vir o TOKEN
													"express_notify_url" => "http://www.amplitur.com/reservas/retorno_paypal/notify"// no final da string tem que vir o TOKEN

	);

	private static $webservice_address_sandbox 		= array("token" => "https://api.sandbox.paypal.com/v1/oauth2/token",
													"payments" => "https://api.sandbox.paypal.com/v1/payments/payment",
													"accept" => "https://api.sandbox.paypal.com/v1/payments/payment/{payment_id}/execute/",
													"invoice" => "https://api-3t.sandbox.paypal.com/nvp",
													"express" => "https://api-3t.paypal.com/nvp",
													"express_url" => "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=",// no final da string tem que vir o TOKEN
													"express_notify_url" => "http://amplitur/reservas/retorno_paypal/notify"// no final da string tem que vir o TOKEN

	);

	/*
	 * Sandbox Account
fewel_banxee-facilitator@yahoo.com

Access Token
access_token$sandbox$frbtt92gqynfqkz2$33a19997b474dc2da56dbfbfc9a9775b

Cliente: AQPJ1v4K3-wuEHSiNFHvJxTDfPvJEJmXxZviA4zEJXeyGFYdu-8xfT13Efjqeto5InrVXca4EVEDZ7TN
Secret: EOwq6J9RfgwQu_uULXU0Vjaedo8Jaxb_LEI8Bdx8OMGfxziapMsgyRmJST8lwd2YqymyFiFnoJCX6mqC
Expiry Date
03 Aug 2026

AMP Travels
Sandbox account
paypal-facilitator@amplitur.com
Client ID
Ad6F1LRnqHKv1xJPKCcY1NxnnOlKNCXb6zA5ecXpfbKb6zIJJ6ZLH15lLEHZNUIUwzTLczDT1c_U9Jx7
Secret
EIS_Khvs7kutc9E-IrZLQIpUYa8AlqwoAKTzJkRqkNFaG96gU8-JI5rgDYM6gQrPoFn34a72T7K2-lOz

	 */

	private static $debug							= 0;
	private static $environment_test				= 1;		// Faz o chaveamento se vamos pegar a url do webservice de teste ou não. 0 ativa o modo produção!

	private $shop_worldwide							= 0;	// Essa flag vai fazer a divisão de comrpas nacionais (BRL) e fora do Brasil (EUR/USD)

	private $client_id_sandbox						= "Ad6F1LRnqHKv1xJPKCcY1NxnnOlKNCXb6zA5ecXpfbKb6zIJJ6ZLH15lLEHZNUIUwzTLczDT1c_U9Jx7";
	private $secret_sandbox							= "EIS_Khvs7kutc9E-IrZLQIpUYa8AlqwoAKTzJkRqkNFaG96gU8-JI5rgDYM6gQrPoFn34a72T7K2-lOz";
	private $url_return_sandbox						= "frontend.my-account.bookings.reservation.approved-payment";
	private $url_cancel_return_sandbox				= "frontend.my-account.bookings.reservation.failed-payment";

	private $invoice_user							= 'paypal-facilitator_api1.amplitur.com.br';
	private $invoice_pwd							= 'WJKVZV97QVYVH4BU';
	private $invoice_signature						= 'AqECm7xGe9NblY4JNanwaBLoo1wtArC6yxj.mXAY96W8S9rO8P3n5odV';

	private $invoice_version						= '108';
	private $invoice_method							= 'MassPay';
	private $invoice_currencycode					= 'BRL';
	private $invoice_receivertype					= 'EmailAddress';
	private $invoice_emailsubject					= 'Assunto do email que o cliente receberá';

	// Compras no Brasil
	private $express_user							= 'paypal_api1.amplitur.com.br';
	private $express_pwd							= 'VWXWX429HRXDAVJL';
	private $express_signature						= 'AFcWxV21C7fd0v3bYYYRCpSSRl31APcBQoEnkrevEFQECFLfChgRsS.q';
	private $express_user_sandbox					= 'paypal-facilitator_api1.amplitur.com.br';
	private $express_pwd_sandbox					= 'WJKVZV97QVYVH4BU';
	private $express_signature_sandbox				= 'AqECm7xGe9NblY4JNanwaBLoo1wtArC6yxj.mXAY96W8S9rO8P3n5odV';
	// Fim Compras no Brasil

	// World wide
	private $express_user_ww						= 'backoffice-de_api1.amplitur.com';
	private $express_pwd_ww							= 'EM9B2E4QE74AHACC';
	private $express_signature_ww					= 'A6sbiQUl25UcbsF7EfcLxaUl3BJAAP4VuuTQRKNA5r7kAPY1a.Z-uU0d';

	private $express_user_ww_sandbox				= 'paypal-facilitator_api1.amplitur.com.br';
	private $express_pwd_ww_sandbox					= 'WJKVZV97QVYVH4BU';
	private $express_signature_ww_sandbox			= 'AqECm7xGe9NblY4JNanwaBLoo1wtArC6yxj.mXAY96W8S9rO8P3n5odV';
 	// Fim World wide


	private $express_version						= '108.0';
	private $express_method							= 'SetExpressCheckout';
	private $express_checkout_detail_method			= 'GetExpressCheckoutDetails';
	private $express_checkout_do_method				= 'DoExpressCheckoutPayment';

	private $express_action_sale					= 'SALE';

	private $express_currencycode					= 'BRL';
	private $_currencycode							= array(1=> 'BRL', 2=>'EUR', 3=>'LBR', 4=>'USD');

	public static $_idioma_paypal					= array(1 => 'pt_BR', 2 => 'en', 3 => 'es');	// idiomas para serem setados no parametro LOCALECODE
	public static $logo_amplitur_header				= "https://www.amp-travels.com/images/amp-travel-front-bgwhite2.png";	// logo para ser setada no parametro HDRIMG

	private $client_id								= " ";		//Número de afiliação da loja com a Cielo.
	private $secret									= "	";		//Chave de acesso da loja atribuída pela Cielo.
	private $url_retorno							= "frontend.my-account.bookings.reservation.approved-payment";
	private $url_cancelar_retorno					= "frontend.my-account.bookings.reservation.failed-payment";

	private $_data								    = null;
	private $xml_retorno						    = null;
	private $json_return						    = null;

	private $headers;

	function getWebserviceAddress($comando=""){
		if(Paypal::$environment_test == 1){
			return Paypal::$webservice_address_sandbox[$comando];
		}else{
			return Paypal::$webservice_address[$comando];
		}
	}

	function getFiliation(){
		if(Paypal::$environment_test == 1){
			return $this->client_id_sandbox;
		}else{
			return $this->client_id;
		}
	}

	function getReturnUrl(){
		if(Paypal::$environment_test == 1){
			return $this->url_return_sandbox;
		}else{
			return $this->url_retorno;
		}
	}

	function getExpressUser(){
		if(Paypal::$environment_test == 1){
			if($this->getWorldWideShop()){
				$this->express_user_ww_sandbox;
			}
			return $this->express_user_sandbox;
		}else{
			if($this->getWorldWideShop()){
				return $this->express_user_ww;
			}
			return $this->express_user;
		}
	}

	function getExpressPwd(){
		if(Paypal::$environment_test == 1){
			if($this->getWorldWideShop()){
				return $this->express_pwd_ww_sandbox;
			}
			return $this->express_pwd_sandbox;
		}else{
			if($this->getWorldWideShop()){
				return $this->express_pwd_ww;
			}
			return $this->express_pwd;
		}
	}

	function getExpressSignature(){
		if(Paypal::$environment_test == 1){
			if($this->getWorldWideShop()){
				return $this->express_signature_ww_sandbox;
			}
			return $this->express_signature_sandbox;
		}else{
			if($this->getWorldWideShop()){
				return $this->express_signature_ww;
			}
			return $this->express_signature;
		}
	}


	function getReturnUrlCancelar(){
		if(Paypal::$environment_test == 1){
			return $this->url_cancel_return_sandbox;
		}else{
			return $this->url_cancelar_retorno;
		}
	}

	function getKey(){
		if(Paypal::$environment_test == 1){
			return $this->secret_sandbox;
		}else{
			return $this->secret_filiacao;
		}
	}

	function setToken($token=""){
		$this->token 				= $token;
	}

	/**
	 * Método que seta que estamos fazendo uma compra internacional do Paypal,
	 * isso é importante porque muda endpoints e credenciais
	 */
	public function setWorldWideShop(){
		$this->shop_worldwide = 1;
	}

	public function getWorldWideShop(){
		return $this->shop_worldwide;
	}

	/**
     * [getToken description]
	 * Primeiro tem-se que gerar um token.
     *
     * @return  string    [return description]
     */
	function getToken(){
		$this->headers 				= array("Accept: application/json");
		$_token_data['grant_type'] 	= 	"client_credentials";
		$_token_data['curl_command']= 	EnumPaypal::COMMAND_GENERATE_TOKEN;

		$_token_data['defaults'][CURLOPT_RETURNTRANSFER] = true;
		$_token_data['defaults'][CURLOPT_USERPWD] = $this->getFiliation().":".$this->getKey();
		$_token_data['defaults'][CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
		$_dados = $this->GetAuthorized($_token_data);
		//print_r($_dados);
		return $_dados->access_token;
	}

	/**
     * [getPayment description]
	 * Aceita o pagamento
     *
     * @return  string    [return description]
     */
	function getPayment(
			$payment_id = null,
			$payer_id = null
	){
		$t = 0;
		$this->headers 					= array("Content-Type:application/json","Authorization: Bearer {$this->getToken()}");
		$this->_data['payment_id'] 		= $payment_id;
		$this->_data['payer_id'] 		= $payer_id;
		$this->_data['curl_command'] 	= EnumPaypal::COMMAND_ACCEPT;
		return $this->GetAuthorized($this->_data);
	}

	function getVoidTransaction(
			$valor 				= 0,	// Valor a ser cancelado. Caso não seja informado, será um cancelamento total.
			$tid 				= 0	// Identificador da transação
	){
		return false;
	}

	/**
     * [GetAuthorized description]
	 * Autorização do processo
	 * Rodrigo - 15/06/2016
     *
     * @return  string    [return description]
     */
	private function GetAuthorized($_post_array=null, $return_type=0){
		$curl_command = $_post_array['curl_command'];
		unset($_post_array['curl_command']);
		$json_return = $this->sendPost($_post_array, $curl_command);
		try{
			$this->json_return = json_decode($json_return);
			//print_r($this->json_return);
			if(isset($this->json_return->codigo)){
				$this->json_return->retorno 	= 'erro';
				$this->json_return->mensagem 	= trim($this->json_return['mensagem']);
			}else{
				$this->json_return->retorno 	= 'sucesso';
			}
		}
		catch(Exception $e){
			return false;
		}
		return $this->json_return;
	}

	/*
	 * Estorno de autorização de um passo.
	 * Rodrigo - 15/02/2016
	 */
	private function VoidTransaction($_post_array=null, $return_type=0){
		$xml_retorno = $this->sendPost($_post_array, EnumPaypal::COMMAND_CANCELLATION);
		try{
			$this->xml_retorno = new SimpleXMLElement($xml_retorno, LIBXML_NOCDATA);
			if(isset($this->xml_retorno->codigo)){
				$this->xml_retorno->addChild('retorno', 'erro');
				$this->xml_retorno->mensagem = trim($this->xml_retorno->mensagem);
			}else{
				$this->xml_retorno->addChild('retorno', 'sucesso');
			}
			$this->xml_retorno->addChild('retorno_json', json_encode($this->xml_retorno, JSON_UNESCAPED_UNICODE));
		}
		catch(Exception $e){
			return false;
		}
		if($return_type == 1){
			$json 	= json_encode($this->xml_retorno);
			$array 	= json_decode($json,TRUE);
			return $array;
		}
		return $this->xml_retorno;
	}

	/**
     * [getPaymentInvoice description]
     *
     * @return  string    [return description]
     */
	public function getPaymentInvoice($_post_array){
		$this->headers 				= array();
		$curl_command = EnumPaypal::COMMAND_INVOICE;
		$_post_array['USER'] 			= $this->invoice_user;
		$_post_array['PWD']  			= $this->invoice_pwd;
		$_post_array['SIGNATURE'] 		= $this->invoice_signature;
		$_post_array['METHOD'] 			= $this->invoice_method;
		$_post_array['VERSION'] 		= $this->invoice_version;
		$_post_array['CURRENCYCODE'] 	= $this->invoice_currencycode;
		$_post_array['RECEIVERTYPE'] 	= $this->invoice_receivertype;
		$_post_array['EMAILSUBJECT'] 	= $this->invoice_emailsubject;
		$responseNvp = new stdClass();
		try{
			$_retorno = urldecode($this->sendPost($_post_array, $curl_command));
			if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $_retorno, $matches)) {
				foreach ($matches['name'] as $offset => $name) {
					$responseNvp->$name = $matches['value'][$offset];
				}
			}
			$this->json_return = $responseNvp;
			if (isset($responseNvp->ACK) && $responseNvp->ACK == 'Success') {
				$this->json_return->retorno 	= 'sucesso';
			} else {
				$this->json_return->retorno 	= 'erro';
			}
		}
		catch(Exception $e){
			return false;
		}
		return $this->json_return;
	}

	/**
     * [getPaymentExpress description]
	 * Gera a primeira iteração com o paypal para depois iniciar o redirect.
     *
     * @return  string    [return description]
     */
	public function getPaymentExpress($_post_array){
		$this->headers 				= array();
		$curl_command 									= EnumPaypal::COMMAND_INVOICE;
		$_post_array['USER'] 							= $this->getExpressUser();
		$_post_array['PWD']  							= $this->getExpressPwd();
		$_post_array['SIGNATURE'] 						= $this->getExpressSignature();
		$_post_array['METHOD'] 							= $this->express_method;
		$_post_array['VERSION'] 						= $this->express_version;
		$_post_array['PAYMENTREQUEST_0_CURRENCYCODE'] 	= $this->express_currencycode;	// Default BRL
		if(isset($_post_array['currency_id'])){
			if(isset($this->_currencycode[$_post_array['currency_id']])){
				$_post_array['PAYMENTREQUEST_0_CURRENCYCODE'] 	= $this->_currencycode[$_post_array['currency_id']];
			}
		}
		unset($_post_array['currency_id']);
		$_post_array['PAYMENTREQUEST_0_PAYMENTACTION'] 	= $this->invoice_receivertype;
		$_post_array['EMAILSUBJECT'] 					= $this->invoice_emailsubject;
		$_post_array['BUTTONSOURCE'] 					= "BR_EC_EMPRESA";

		$responseNvp = new stdClass();
		try{
			$_retorno = urldecode($this->sendPost($_post_array, $curl_command));
			if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $_retorno, $matches)) {
				foreach ($matches['name'] as $offset => $name) {
					$responseNvp->$name = $matches['value'][$offset];
				}
			}
			$this->json_return = $responseNvp;
			if (isset($responseNvp->ACK) && $responseNvp->ACK == 'Success') {
				$this->json_return->retorno 	= 'sucesso';
			} else {
				$this->json_return->retorno 	= 'erro';
			}
		}
		catch(Exception $e){
			return false;
		}
		return $this->json_return;
	}

	/**
     * [trataPaymentExpressRedirect description]
     *
     * @return  string    [return description]
     */
	public function trataPaymentExpressRedirect($_data=null){
		if(isset($_data->TOKEN)){
			$url = $this->getWebserviceAddress("express_url");
			$url .= $_data->TOKEN;
			header('Location: ' . $url);
			die();
		}else{
			return false;	// deu algum erro...
		}

	}

	/**
	 * Faz uma validação se a "autorização" de compra é válida ou não. aqui o usuário já passou pela tela do paypal e voltou ao site.
	 * @param unknown $_post_array
	 */
	public function getExpressCheckoutDetails($_post_array){
		$this->headers 				= array();
		$curl_command 									= EnumPaypal::COMMAND_INVOICE;
		$_post_array['USER'] 							= $this->getExpressUser();
		$_post_array['PWD']  							= $this->getExpressPwd();
		$_post_array['SIGNATURE'] 						= $this->getExpressSignature();
		$_post_array['METHOD'] 							= $this->express_checkout_detail_method;
		$_post_array['VERSION'] 						= $this->express_version;

		$responseNvp = new stdClass();
		try{
			$_retorno = urldecode($this->sendPost($_post_array, $curl_command));
			if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $_retorno, $matches)) {
				foreach ($matches['name'] as $offset => $name) {
					$responseNvp->$name = $matches['value'][$offset];
				}
			}
			$this->json_return = $responseNvp;
			if (isset($responseNvp->ACK) && $responseNvp->ACK == 'Success') {
				$this->json_return->retorno 	= 'sucesso';
			} else {
				$this->json_return->retorno 	= 'erro';
			}
		}
		catch(Exception $e){
			return false;
		}
		return $this->json_return;
	}

	/**
	 * Faz a autorização final da compra.
	 * @param unknown $_post_array
	 */
	public function getDoExpressCheckoutPayment($_post_array){
		$this->headers 				= array();
		$curl_command 									= EnumPaypal::COMMAND_INVOICE;
		$_post_array['USER'] 							= $this->getExpressUser();
		$_post_array['PWD']  							= $this->getExpressPwd();
		$_post_array['SIGNATURE'] 						= $this->getExpressSignature();
		$_post_array['METHOD'] 							= $this->express_checkout_do_method;
		$_post_array['VERSION'] 						= $this->express_version;
		$_post_array_do['NOTIFYURL'] 					= $this->getWebserviceAddress("express_notify_url");;
		$_post_array['PAYMENTREQUEST_0_CURRENCYCODE'] 	= $this->express_currencycode;
		$_post_array['PAYMENTREQUEST_0_PAYMENTACTION'] 	= $this->express_action_sale;

		if(isset($_post_array['currency_id'])){
			if(isset($this->_currencycode[$_post_array['currency_id']])){
				$_post_array['PAYMENTREQUEST_0_CURRENCYCODE'] 	= $this->_currencycode[$_post_array['currency_id']];
			}
		}
		unset($_post_array['currency_id']);

		$responseNvp = new stdClass();
		try{
			$_retorno = urldecode($this->sendPost($_post_array, $curl_command));
			if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $_retorno, $matches)) {
				foreach ($matches['name'] as $offset => $name) {
					$responseNvp->$name = $matches['value'][$offset];
				}
			}
			$this->json_return = $responseNvp;
			if (isset($responseNvp->ACK) && $responseNvp->ACK == 'Success') {
				$this->json_return->retorno 	= 'sucesso';
			} else {
				$this->json_return->retorno 	= 'erro';
			}
		}
		catch(Exception $e){
			return false;
		}
		return $this->json_return;
	}

	/*
	 * Tratamos aqui se tem curl ou vai de file get contents
	 * Rodrigo - 16/06/2016
	 */
	private function sendPost($_post_array = null, $comando=null){
		$url 	= $this->getWebserviceAddress($comando) ;
		try{
			$_post_array_curl = $_post_array;// Para ser usado no curl
			unset($_post_array['defaults']);
			$data 	= http_build_query($_post_array);

			switch($comando){
				case EnumPaypal::COMMAND_ACCEPT:
					$url = str_replace("{payment_id}", $_post_array['payment_id'], $url);
					unset($_post_array['payment_id']);
					$data 	= http_build_query($_post_array);
				case EnumPaypal::COMMAND_AUTHORIZE:
					$data = json_encode($_post_array);
					break;
				case EnumPaypal::COMMAND_INVOICE:
					$data 	= http_build_query($_post_array);
					break;
			}

			if(!$this->curlExists()){
				// use key 'http' even if you send the request to https://...
				$options = array(
						'http' => array(
								'header'  => $this->headers,
								'method'  => 'POST',
								'content' => $data,
						),
				);
				$context  	= stream_context_create($options);
				$result 	= file_get_contents($url, false, $context);
				if ($result === FALSE) { return false; /* Handle error */ }
				return $result;
			}else{
    			//open connection
    			$ch = curl_init($url);

    			//set the url, number of POST vars, POST data
    			$defaults = array(
    					CURLOPT_HEADER => 0,
    					//CURLOPT_FOLLOWLOCATION => 1,
    					CURLOPT_POST => 1,
    					CURLOPT_HTTPHEADER => $this->headers,
    					CURLOPT_URL => $url,
    					//CURLOPT_FRESH_CONNECT => 1,
    					CURLOPT_RETURNTRANSFER => 1,
    					//CURLOPT_FORBID_REUSE => 1,
    					//CURLOPT_TIMEOUT => 30,
    					CURLOPT_SSL_VERIFYPEER => false,	// se der pau de certificado, coloca false aqui e tira as linhas: CURLOPT_SSL_VERIFYHOST e CURLOPT_SSL_VERIFYPEER
    					CURLOPT_SSL_VERIFYHOST => 2,
    					CURLOPT_CAINFO => getcwd(). "/application/models/paypal/paypal.crt",

    					//CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
    			);
    			if(isset($_post_array_curl['defaults'])){
    				foreach($_post_array_curl['defaults'] as $key => $dados){
    					$defaults[$key] = $dados;
    				}
    				unset($_post_array_curl['defaults']);
    			}
    			$defaults[CURLOPT_POSTFIELDS] = $data;

    			curl_setopt_array($ch, ($defaults));
				//execute post
				if( ! $result = curl_exec($ch))
			    {
			        $error = curl_error($ch);
			        return false; /* Handle error */
			    }
			    //print_r($result);
				if ($result === FALSE) { return false; /* Handle error */ }
				curl_close($ch);
				return $result;
			}
		}catch (\Exception $e) {
			return false;
		}

	}

	function curlExists(){
		return function_exists('curl_version');
	}

	/**
     * [pay description]
     *
     * @param   Booking      $booking       [$booking description]
     * @param   BookingBill  $bookingBill   [$bookingBill description]
     * @param   Array        $_data         [$_data description]
     *
     * @return  [type]                      [return description]
     */
    public function pay(Booking $booking, ?BookingBill $bookingBill = null, $_data=null)
    {
        if (!$bookingBill && $booking->payment_status == ProcessStatus::PAID) {
            throw new InvalidArgumentException();
        }

        if ($bookingBill && $bookingBill->isPaid()) {
            throw new InvalidArgumentException;
        }
		$amount_transaction	= $bookingBill->total;
        try {
			$_data['RETURNURL'] 	= route(getRouteByLanguage($this->getReturnUrl()), ['booking' => $booking, 'bookingBill' => $bookingBill]);
			$_data['CANCELURL'] 	= route(getRouteByLanguage($this->getReturnUrlCancelar()), ['booking' => $booking, 'bookingBill' => $bookingBill]);

			$data['paypal'] = $this->getPaymentExpress($_data);
			if(!$this->trataPaymentExpressRedirect($data['paypal'])){
				$payload = json_encode($data['paypal']);
				$reason  = isset($data['paypal']->L_SHORTMESSAGE0)?"Reason: ". $data['paypal']->L_SHORTMESSAGE0:null;
				throw new InvalidPaymentOperationException('Failed to comunicate with hub. '. $reason , 400, null, $payload);
			}
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

            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'fail', $amount_transaction, 404, Transactions::OPERATION_FAIL);
            bugtracker()->notifyException($ex);
            throw $ex;
        }

        return $transaction;
    }

	/**
     * [approve description]
     *
     * @param   Booking      $booking       [$booking description]
     * @param   BookingBill  $bookingBill   [$bookingBill description]
     * @param   Array        $_data         [$_data description]
     *
     * @return  [type]                      [return description]
     */
    public function approve(Booking $booking, ?BookingBill $bookingBill = null, $_data=null)
    {
        if (!$bookingBill && $booking->payment_status == ProcessStatus::PAID) {
            throw new InvalidArgumentException();
        }

        if ($bookingBill && $bookingBill->isPaid()) {
            throw new InvalidArgumentException('Bill already paid');
        }
		$this->code			= 400;
		$amount_transaction	= $bookingBill->total;
        try {
			$_post_array['TOKEN'] = $_data['token'];
			$result = $this->getExpressCheckoutDetails($_post_array);

			if(!$result){
				throw new Exception('Failed to comunicate with hub');
			}
			$payload								 		= json_encode($result);
			$_post_array_do['TOKEN'] 						= $result->TOKEN;
			$_post_array_do['PAYERID'] 						= $result->PAYERID;

			$_post_array_do['PAYMENTREQUEST_0_AMT'] 		= $result->PAYMENTREQUEST_0_AMT;
			$_post_array_do['PAYMENTREQUEST_0_ITEMAMT'] 	= $result->PAYMENTREQUEST_0_ITEMAMT;
			$_post_array_do['PAYMENTREQUEST_0_INVNUM'] 		= $result->PAYMENTREQUEST_0_INVNUM;
			$_post_array_do['L_PAYMENTREQUEST_0_NAME0'] 	= $booking->id .":". $result->L_PAYMENTREQUEST_0_NAME0 ;
			$_post_array_do['L_PAYMENTREQUEST_0_QTY0'] 		= $result->L_PAYMENTREQUEST_0_QTY0;
			$_post_array_do['L_PAYMENTREQUEST_0_TAXAMT0'] 	= $result->L_PAYMENTREQUEST_0_TAXAMT0;
			$_post_array_do['L_PAYMENTREQUEST_0_AMT0'] 		= $result->L_PAYMENTREQUEST_0_AMT0;
			$_post_array_do['L_PAYMENTREQUEST_0_DESC0'] 	= $result->L_PAYMENTREQUEST_0_DESC0;
			// enviando os dados de moeda
			$_post_array_do['currency_id']					= $bookingBill->currency_id;
			$result_do 		= $this->getDoExpressCheckoutPayment($_post_array_do);
			if(isset($result_do->ACK)){
				switch($result_do->ACK){
					case EnumPaypal::PAYPAL_CHECKOUT_SUCCESS_ACK:
					case EnumPaypal::PAYPAL_CHECKOUT_SUCCESSW_ACK:
						$this->code = 200;
						$this->setBookingBillAsPaid($bookingBill);
						break;
					default:
						throw new BillApproveFailException('fail', 400, null, json_encode($result_do));
						break;
				}
			}else{
				throw new Exception('Failed to comunicate with hub');
			}
			$transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'approved', $amount_transaction, $this->code, Transactions::OPERATION_PAYMENT);
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

            $transaction = $this->createPaymentTransaction($booking, $bookingBill, $payload, 'fail', $amount_transaction, 404, Transactions::OPERATION_FAIL);
            bugtracker()->notifyException($ex);
            throw $ex;
        }

        return $transaction;
    }
}
