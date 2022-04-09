<?php
$_retorno_objeto = $retorno_objeto;
?>
<pre>
====================================
<?=lang('financeiro.paypal.transacao')?>: <?=$transacao?> 
<?=lang('financeiro.paypal.valor')?>: <?=$valor_mensagem?> 
<?=lang('financeiro.paypal.numero_autorizacao')?>: <?=$_retorno_objeto->getNumAutor();?> 
Status: <?=$_retorno_objeto->getStatus();?> 

<?=lang('financeiro.paypal.numero_pedido')?>: <?=$_retorno_objeto->getNumPedido();?> 
<?=lang('financeiro.paypal.mensagem')?>: <?=$_retorno_objeto->getMsgRet();?> 
====================================
</pre>
