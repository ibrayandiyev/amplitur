<?php
$_retorno_objeto = json_decode($retorno_objeto);
?>
<pre>
====================================
Pedido: <?=(isset($_retorno_objeto->Pedido)?$_retorno_objeto->Pedido:"-")?> 
Valor: <?=(isset($_retorno_objeto->Valor)?$_retorno_objeto->Valor:"-")?> 
tipPag: <?=(isset($_retorno_objeto->tipPag)?$_retorno_objeto->tipPag:"-")?> 
sitPag: <?=(isset($_retorno_objeto->sitPag)?$_retorno_objeto->sitPag:"-")?> 
dtPag: <?=(isset($_retorno_objeto->dtPag)?$_retorno_objeto->dtPag:"-")?> 
sitPagMsg: <?=(isset($_retorno_objeto->sitPagMsg)?$_retorno_objeto->sitPagMsg:"-")?> 
====================================
</pre>
