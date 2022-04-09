<?php

namespace Libs\Itau;

use SimpleXMLElement;

class Itau
{
	private $numeroPedido; 
	private $valor; 
	private $nomeSacado;
	private $codigoInscricao;
	private $numeroInscricao; 
	private $enderecoSacado; 
	private $bairroSacado; 
	private $cepSacado; 
	private $cidadeSacado; 
	private $estadoSacado; 
	private $dataVencimento;
	private $errors;
	
	private $urlItauConsulta							= "https://shopline.itau.com.br/shopline/consulta.aspx";
	public static $chave								= "HK7V3J2L42MQI5DW";					// Chave do Itau, cadastrado dentro do Home Banking.
	public static $codEmp								= "J0801936590001400000018867";			//Código da Empresa, cadastrado dentro do Home Banking.
	public static $urlRetorno							= "booking/shopline_return";	// Url de retorno do site.
	
	public static $retornoItauXml						= 1;
	public static $retornoItauHtml						= 0;
	
	public static $tipoPagamentoNaoEscolhido			= "00";	//Tipo de pagamento Escolhido pelo Comprador: Numérico com 02 posições: 00 para não escolhido, 01 para pagamento à vista (TEF ou CDC)
	public static $tipoPagamentoAVista					= "01";	// 01 Transferencia
	public static $tipoPagamentoBoleto					= "02"; // 02 para boleto
	public static $tipoPagamentoCC						= "03"; // 03 para cartão de crédito
	
	public static $_situacaoPagamento					= array("00" => "Pagamento efetuado", "01" => "Situação de pagamento não finalizada (tente novamente)", "02" =>  "Erro no processamento da consulta (tente novamente)", "03" => "pagamento não localizado (consulta fora de prazo ou pedido não registrado no banco))",
														"04" => "Boleto emitido com sucesso", "05" => "Pagamento efetuado, aguardando compensação", "06" => "pagamento não compensado");
	public static $flagPagamentoEfetuado				= "00";
	private $xml_retorno								= null;	// Xml de retorno.

	public static $MENSAGEM_BOLETO_ITAU_PAGO			= "Boleto bancário {parcela}- PAGO";
	
	
	/*
	 * Método antigo, não precisaremos talvez disso.
	 * Rodrigo - 20/06/2016
	 */
	function setAll($numeroPedido, $valor, $nomeSacado, $codigoInscricao, $numeroInscricao, 
		$enderecoSacado, $bairroSacado, $cepSacado, $cidadeSacado, $estadoSacado, $dataVencimento){
		$cepSacado = str_replace("-", "", $cepSacado);
	 	$this->numeroPedido = $this->setLenght($numeroPedido, 8);
		$this->valor = $this->setLenght($valor, 11);
		$this->nomeSacado = $this->setLenght($nomeSacado, 30);
		$this->codigoInscricao = $this->setLenght($codigoInscricao, 2);
		$this->numeroInscricao = $this->setLenght($numeroInscricao, 14);
		$this->enderecoSacado = $this->setLenght($enderecoSacado, 40);
		$this->bairroSacado = $this->setLenght($bairroSacado, 15);
		$this->cepSacado = $this->setLenght($cepSacado, 8);
		$this->cidadeSacado = $this->setLenght($cidadeSacado, 15);
		$this->estadoSacado = $this->setLenght($estadoSacado, 2);
		$this->dataVencimento = $this->setLenght($dataVencimento, 8);
		$this->errors = array();
	 }
	
	 /*
	  * Método antigo, não precisaremos talvez disso.
	  * Rodrigo - 20/06/2016
	  */
	private function setLenght($paramString, $paramInt)
	{
		$str = $paramString . "";
		if (strlen($str) < $paramInt) {
			return $str;
		}
		return substr($str, 0, $paramInt);
	}
	
	/*
	 * Método antigo, não precisaremos talvez disso.
	 * Rodrigo - 20/06/2016
	 */
	function validar(){
		if ((strlen($this->numeroPedido) < 1) || (strlen($this->numeroPedido) > 8)) {
			array_push($this->errors, "Erro: número do pedido inválido.");
		}
		if (!is_numeric($this->numeroPedido)) {			
			array_push($this->errors, "Erro: numero do pedido não é numérico.");
		}
		if ((strlen($this->valor) < 1) || (strlen($this->valor) > 11)) {
			array_push($this->errors, "Erro: valor da compra inválido.");
		}
		$i = strpos($this->valor, ',');
		if ($i !== FALSE) {
			$str3 = substr($this->valor, ($i + 1));
			if (!is_numeric($str3)) {
				array_push($this->errors, "Erro: valor decimal não é numérico.");
			}
			if (strlen($str3) != 2) {
				array_push($this->errors, "Erro: valor decimal da compra deve possuir 2 posições após a virgula.");
			}
		} else {
			if (!is_numeric($this->valor)) {
				array_push($this->errors, "Erro: valor da compra não é numérico.");
			}
			if (strlen($this->valor) > 8) {
				array_push($this->errors, "Erro: valor da compra deve possuir no máximo 8 posições antes da virgula.");
			}
		}
		if (($this->codigoInscricao != "02") && ($this->codigoInscricao != "01") && ($this->codigoInscricao != "")) {
			array_push($this->errors, "Erro: código de inscrição inválido.");
		}
		if (($this->numeroInscricao != "") && (!is_numeric($this->numeroInscricao)) && (strlen($this->numeroInscricao) > 14)) {
			array_push($this->errors, "Erro: número de inscrição inválido. ".$this->numeroInscricao);
		}
		if (($this->cepSacado != "") && ((!is_numeric($this->cepSacado)) || (strlen($this->cepSacado) != 8))) {
			array_push($this->errors, "Erro: cep inválido. ".$this->cepSacado);
		}
		if (($this->dataVencimento != "") && ((!is_numeric($this->dataVencimento)) || (strlen($this->dataVencimento) != 8))) {
			array_push($this->errors, "Erro: data de vencimento inválida. ".$this->dataVencimento);
		}
	}

	function getErrors(){
		return $this->errors;
	}
	
	/*
	 * Método antigo, não precisaremos talvez disso.
	 * Rodrigo - 20/06/2016
	 */
	function gerarDados(){
	  	$params = array(
	  		'codEmp'	=> "J0801936590001400000018867",
	  		'chave' => "Hk7V3j2L42mqi5Dw",
			'numeroPedido' => $this->numeroPedido,
			'valor' => $this->valor,
			'nomeSacado' => $this->nomeSacado,
			'codigoInscricao' => $this->codigoInscricao,
			'numeroInscricao' => $this->numeroInscricao,
			'enderecoSacado' => $this->enderecoSacado,
			'bairroSacado' => $this->bairroSacado,
			'cepSacado' => $this->cepSacado,
			'cidadeSacado' => $this->cidadeSacado,
			'estadoSacado' => $this->estadoSacado,
			'dataVencimento' => $this->dataVencimento,
			'urlRetorno' => '',
			'observacao' => '',
			'observacaoAdicional1' => '',
			'observacaoAdicional2' => '',
			'observacaoAdicional3' => ''
		);
		$url = "http://amplitur-online.com.br/shopline?".http_build_query($params);
die("chamada do amplitur-online - Java");
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
		$dc = curl_exec($ch);
		die("dc:". $url);
		curl_close($ch);
		return $dc;
	}
	
	public function getUrlConsultaItau(){
		return $this->urlItauConsulta;
	}
	
	function curlExists(){
		return function_exists('curl_version');
	}
	
	function getXmlRetorno(){
		return $this->xml_retorno;	// Xml já tratado, ou é inválido.
	}
	
	/*
	 * Método para consultar uma situação de pagamento do itau.
	 * Rodrigo - 20/06/2016
	 * tipoRetorno = 0 : html, 1: xml.
	 */
	function consultaServidorItau($dados = "", $tipoRetorno=-1){
		$_post_array['DC'] = $dados;
		$url 	= $this->getUrlConsultaItau() ;
		try{
			if(!$this->curlExists()){
				
				$data 	= http_build_query($_post_array);
				// use key 'http' even if you send the request to https://...
				$options = array(
						'http' => array(
								'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
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
				
				$fields_string 	= http_build_query($_post_array);
				
				//set the url, number of POST vars, POST data
				$defaults = array(
						CURLOPT_POST => 1,
						CURLOPT_HEADER => 0,
						CURLOPT_URL => $url,
						CURLOPT_FRESH_CONNECT => 1,
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_FORBID_REUSE => 1,
						CURLOPT_TIMEOUT => 10,
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_POSTFIELDS => http_build_query($_post_array)
				);
				curl_setopt_array($ch, ($defaults));
				//execute post
				if( ! $result = curl_exec($ch))
			    {
			        $error = curl_error($ch);
			        
			        return false; /* Handle error */
			    } 
				if ($result === FALSE) { return false; /* Handle error */ }
				curl_close($ch);
				if($tipoRetorno == 1){
					$this->xml_retorno = new SimpleXMLElement($result, LIBXML_NOCDATA);
				}
				return $result;
			}
		}catch (\Exception $e) {
			return false;
		}
	}
}
?>
