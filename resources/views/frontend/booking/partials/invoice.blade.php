<div class="box__conteudo corpo-texto">
	<h3>Invoice</h3>
	<p>
		{{ __('frontend.financeiro.invoice_imprima_links') }}
	</p>
	<ul>
		@foreach($bills as $bill)
		<li>
			<a class="icone boleto" href="{{ route('frontend.my-account.bookings.showInvoice', ['booking' => $bill->booking_id, $bill->bill_id])}}" rel="external">
			@if($loop->first)
            {{__('frontend.geral.entrada')}}
			@else
				{{$loop->iteration}}ª {{__('frontend.geral.parcela')}}
			@endif
			({{$bill->currency}} {{ $bill->total}})</a>
		</li>
		@endforeach
	</ul>
</div>
<!-- /.box__conteudo -->
