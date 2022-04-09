<div class="box__conteudo corpo-texto">
	<h3>Cartão de Crédito</h3>
	<ul>
		@foreach($bills as $bill)
		<li>
			@if($loop->first && $firstPaymentBill == 1)
				{{$loop->iteration}}ª {{__('frontend.geral.parcela')}}
			@elseif($loop->first)
				{{__('frontend.geral.entrada')}}
			@else 
				{{$loop->iteration}}ª {{__('frontend.geral.parcela')}}
			@endif
			({{$bill->currency}} {{ $bill->total}})
		</li>
		@endforeach
	</ul>
</div>
<!-- /.box__conteudo -->
