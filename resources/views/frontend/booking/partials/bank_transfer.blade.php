<?php if(isset($transferencias['terrestre'])): ?>
	<div class="box mb">
		<header class="box__header">
			<h2 class="box__titulo">{{ __('frontend.reservas.pagamento') }}</h2>
		</header>
		<div class="box__conteudo corpo-texto">
			<h3>{{__('frontend.geral.rodape_trf')}}</h3>
			<ul>
				<?php foreach($transferencias['terrestre'] as $key=>$row): ?>
				<?php
					// Nada de tapete.
					$valor 		= $row['valor'];
					$valor_taxa = $valor * ($row['taxa_servico']/100);
					$valor_final = $valor_taxa + $valor;
				?>
				<li>
					<?php
						if((isset($formapagt->processador) && $formapagt->processador == Financeiro_model::$PROCESSADOR_BRADESCO)
								|| (isset($row['processador']) && $row['processador'] == Financeiro_model::$PROCESSADOR_BRADESCO)):?>
						<?php
							$_transferencia = $this->financeiro->getTransferenciaBradesco($reserva->id, $row['id'], Financeiro_model::$TIPO_RETORNO_ARRAY);
							if(isset($_transferencia['status']['codigo']) && $_transferencia['status']['codigo'] == 0){
								if($key == 1){
									$descricao_pagamento = "Entrada - ". lang('reservas.pagar');
									?>
									<a class="icone pagar" href="<?=$_transferencia['transferencia']['url_acesso']?>" target='_blank'><?=$descricao_pagamento?></a>
									<?php
								}else{
									$descricao_pagamento = "$key ª Parcela ";
									?>
									<?php echo $descricao_pagamento; ?> (<?=$moeda?> <?=number_format($valor_final/$cotacao,2,',','')?>)
									<?php
								}


							}else{
								// Houve falha
								echo "<strong class='ultimas-unidades'>".  lang('financeiro.boleto_falha_gerar') ."</strong>";
							}

						?>
						<?php
						// Se for Shopline, geramos os dados para o post.
						elseif((isset($formapagt->processador) && $formapagt->processador == Financeiro_model::$PROCESSADOR_SHOPLINE)
								|| (isset($row['processador']) && $row['processador'] == Financeiro_model::$PROCESSADOR_SHOPLINE)
								):
						?>

						<?php if($key == 1): ?>
						<?=form_open("reservas/pagamento_shopline", array('id'=>'shopline_'.$key, 'target'=>'_blank'))?>
							<input type='hidden' name='rid' value='<?=$reserva->id?>' />
							<input type='hidden' name='rec' value='<?=$row['id']?>' />
						</form>
						<a class="icone pagar" href="#shopline_<?=$key?>" onclick='document.getElementById("shopline_<?=$key?>").submit()'>
							{{__('frontend.geral.entrada')}}
						(<?=$moeda?> <?=number_format($valor_final/$cotacao,2,',','')?>)</a>
						</a>
						<?php else: ?>
							<?=$key?>ª {{__('frontend.geral.parcela')}}
							(<?=$moeda?> <?=number_format($valor_final/$cotacao,2,',','')?>)
						<?php endif; ?>

						</a>
					<?php endif;?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<!-- /.box__conteudo -->
	</div>
	<!-- /.box -->
	<?php endif; ?>
