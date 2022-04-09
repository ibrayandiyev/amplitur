<?php
$_retorno_json = json_decode($retorno_json);
?>
<pre>
==================================== 
Transação: <?=$transacao?> 
<?php
    // fiz essa validação porque nos testes teve um pedido que não retornou os dados, porém houve aprovação.
    // Rods 17/09/2019
    if(!isset($_retorno_json->tid)):
?>
    Transação sem dados adicionais de autorização. Verificar na Operadora se foi aprovado.
<?php return; endif;  ?>
Tid: <?=$_retorno_json->tid;?>  
Status: <?=$_retorno_json->status;?>  
Pan: <?=$_retorno_json->pan;?> 
Retorno: <?=$_retorno_json->retorno;?> 
====================================
<?php
if(isset($_retorno_json->{'dados-pedido'})):
?>
    Dados do Pedido: 
    ------------------------------------
    Número: <?=$_retorno_json->{'dados-pedido'}->numero;?> 
    Valor: <?=getValorCielo($_retorno_json->{'dados-pedido'}->valor);?> 
    Moeda: <?=$_retorno_json->{'dados-pedido'}->moeda;?> 
    Data/Hora: <?=date("d/m/Y H:i:s", strtotime($_retorno_json->{'dados-pedido'}->{"data-hora"}));?> 
    Descricao: <?=$_retorno_json->{'dados-pedido'}->descricao;?> 
    Idioma: <?=$_retorno_json->{'dados-pedido'}->idioma;?>  
    Taxa Embarque: <?=$_retorno_json->{'dados-pedido'}->{"taxa-embarque"};?> 
    ==================================== 
<?php
endif;
?>
<?php
if(isset($_retorno_json->{'forma-pagamento'})):
?>
    Forma de Pagamento: 
    ------------------------------------ 
    Bandeira: <?=$_retorno_json->{'forma-pagamento'}->bandeira;?> 
    Produto: <?=$_retorno_json->{'forma-pagamento'}->produto;?> 
    Parcelas: <?=$_retorno_json->{'forma-pagamento'}->parcelas;?> 
    ==================================== 
<?php
endif;
?>
<?php
if(isset($_retorno_json->autenticacao)):
?>
    Autenticação: 
    ------------------------------------ 
    Código: <?=$_retorno_json->autenticacao->codigo;?> 
    Mensagem: <?=$_retorno_json->autenticacao->mensagem;?> 
    Data/Hora: <?=date("d/m/Y H:i:s", strtotime($_retorno_json->autenticacao->{'data-hora'}));?> 
    Valor: <?=getValorCielo($_retorno_json->autenticacao->valor);?> 
    Eci: <?=$_retorno_json->autenticacao->eci;?> 
    ==================================== 
<?php
endif;
?>
<?php
if(isset($_retorno_json->autorizacao)):
?>
    Autorização:  
    ------------------------------------ 
    Código: <?=$_retorno_json->autorizacao->codigo;?> 
    Mensagem: <?=$_retorno_json->autorizacao->mensagem;?>  
    Data/Hora: <?=date("d/m/Y H:i:s", strtotime($_retorno_json->autorizacao->{'data-hora'}));?> 
    Valor: <?=getValorCielo($_retorno_json->autorizacao->valor);?> 
    lr: <?=$_retorno_json->autorizacao->lr;?> 
    arp: <?=(isset($_retorno_json->autorizacao->arp)?$_retorno_json->autorizacao->arp:null);?> 
    nsu: <?=(isset($_retorno_json->autorizacao->nsu)?$_retorno_json->autorizacao->nsu:null);?> 
    ==================================== 
<?php
endif;
?>
<?php
if(isset($_retorno_json->captura)):
?>
    Captura: 
    ------------------------------------ 
    Código: <?=$_retorno_json->captura->codigo;?> 
    Mensagem: <?=$_retorno_json->captura->mensagem;?> 
    Data/Hora: <?=date("d/m/Y H:i:s", strtotime($_retorno_json->captura->{'data-hora'}));?> 
    Valor: <?=getValorCielo($_retorno_json->captura->valor);?> 
    ==================================== 
<?php
endif;
?>
<?php
if(isset($_retorno_json->cancelamentos)):
    foreach($_retorno_json->cancelamentos as $cancelamento) : ?>
        cancelamentos: 
        ------------------------------------ 
        Código: <?=$cancelamento->codigo;?> 
        Mensagem: <?=$cancelamento->mensagem;?> 
        Data/Hora: <?=date("d/m/Y H:i:s", strtotime($cancelamento->{'data-hora'}));?> 
        Valor: <?=getValorCielo($cancelamento->valor);?> 
<?php
    endforeach;
    ?>
    ==================================== 
    <?php
endif;
?>
</pre>
