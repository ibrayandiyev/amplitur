<?php
$_retorno_objeto = $retorno_objeto;
?>
<pre>
====================================
Transação: <?=$transacao?> 
Valor: <?=$valor_mensagem?> 
Tid: <?=$_retorno_objeto->getTid();?> 
Status: <?=$_retorno_objeto->getStatus();?> 
Número Autorização: <?=$_retorno_objeto->getNumAutor();?> 
Código Retorno: <?=$_retorno_objeto->getCodRet();?> 
Número Pedido: <?=$_retorno_objeto->getNumPedido();?> 
Parcelas: <?=$_retorno_objeto->getParcelas();?> 
Retorno: <?=$_retorno_objeto->getMsgRet();?> 

====================================
</pre>
