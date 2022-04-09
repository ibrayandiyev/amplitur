<div class="box__conteudo corpo-texto">
	<h3>Boleto(s) Bancário(s)</h3>
	<p>
		{{ __('frontend.financeiro.boleto_imprima_links_itau') }}
	</p>
	<ul>
		@foreach($bills as $bill)
		<li>
			<form action="{{ route(getRouteByLanguage('frontend.my-account.bookings.generateBilletBill'), ['booking' => $bill->booking_id, 'bookingBill' => $bill->bill_id]) }}" id="shopline_{{ $key }}" target="_blank" method="post" accept-charset="utf-8">
				@csrf
				<input type="hidden" name="booking_id" value="{{ $bill->booking_id }}">
				<input type="hidden" name="booking_bill_id" value="{{ $bill->bill_id }}">
				<button type="submit" class="icone pagar">
					@if($loop->first)
						{{ __('frontend.geral.entrada')}} ({{$bill->currency}} {{ $bill->total}})
					@else
						{{$loop->iteration}}ª {{__('frontend.geral.parcela')}} ({{$bill->currency}} {{ $bill->total}})
					@endif
				</button>
			</form>
		</li>
		@endforeach
	</ul>
</div>
<!-- /.box__conteudo -->
