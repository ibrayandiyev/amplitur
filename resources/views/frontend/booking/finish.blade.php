@extends('frontend.template.clean')

@section('content')
<div class="largura-site2 ma">
    <header class="pacote__header">
        <span class="pacote__gravata">
            {{ __('frontend.reservas.compra_pacote') }}
        </span>
        <h1 class="pacote__titulo">
            <span class="pacote__nome">{{ $booking->getName() }}</span>
        </h1>
    </header>

	<div class="a-centro">
		<a class="localizador mb" href='{{ route('frontend.my-account.bookings.show', $booking->id) }}'>
			<div class="localizador__grid">
				<span class="localizador__texto">
					{{ __('frontend.reservas.localizador') }}
				</span>
				<strong class="localizador__numero">
                    {{ $booking->id }}
				</strong>
			</div>
			<small class="localizador__obs">
                {{ __('frontend.reservas.localizador_retornar') }}
			</small>
		</a>
	</div>

	<div class="corpo-texto">
		<p>
            {{ __('frontend.reservas.obrigado_sistema') }}
		</p>

		<p>
            {{ __('frontend.reservas.voce_recebeu_email') }}
			<br>
            {!! __('frontend.reservas.acompanhe_status_compra', ["minha_conta" => $siteurlminhaconta]) !!}
		</p>
	</div>
	<div class="box mb">
		<header class="box__header">
			<h2 class="box__titulo">{{ __('frontend.reservas.pagamento') }}</h2>
		</header>

		@php 
			$firstPaymentBill = 0;
		@endphp 
		@foreach ($booking->getPaymentMethods() as $key => $bookingBill)
			@switch($key)
				@case("credit")
					@include("frontend.booking.partials.credit", ['bills' => $bookingBill, $firstPaymentBill])
				@break;
				@case("billet")
				@php
					if($loop->index == 0){
						$firstPaymentBill = 1;
					}
				@endphp 
					@include("frontend.booking.partials.billet", ['bills' => $bookingBill, $firstPaymentBill])
				@break;
				@case("invoice")
					@include("frontend.booking.partials.invoice", ['bills' => $bookingBill, $firstPaymentBill])
				@break;
			@endswitch
		@endforeach
	</div>
	<!-- /.box -->
	<div class="box mb">
		<header class="box__header">
			<h2 class="box__titulo">{{ __('frontend.reservas.documentacao_necessaria') }}</h2>
		</header>
		<div class="box__conteudo corpo-texto">
			<p>
				{!! __('frontend.reservas.favor_enviar_documentos') !!}
			</p>

			<ul>
				<li>
					<a class="icone documento" href="{{ route('frontend.my-account.bookings.showContract', $booking->id) }}" rel="external">
						{{ __('frontend.reservas.contrato_assinado') }}
					</a>
				</li>
			</ul>
		</div>
		<!-- /.box__conteudo -->
	</div>
	<!-- /.box -->

	<div class="box mb">
		<header class="box__header">
			<h2 class="box__titulo">{{ __('frontend.reservas.importante') }}</h2>
		</header>
		<div class="box__conteudo corpo-texto">
			<p>
				{{ __('frontend.reservas.compra_cancelada') }}
			</p>
			<ul>
				<li>{{ __('frontend.reservas.nao_autorizacao_debito') }}</li>
				<li>{{ __('frontend.reservas.atraso_pagamento') }}</li>
			</ul>

			<p>
				{{ __('frontend.reservas.voucher_embarque') }}
			</p>
		</div>
		<!-- /.box__conteudo -->
	</div>
	<!-- /.box -->

	<div class="corpo-texto">
		<p>
			{!! __('frontend.reservas.qualquer_duvida', ['siteurlcontato' => $siteurlcontato]) !!}
		</p>
	</div>

	<div class="pagina__voltar">
		<a href="{{ route('frontend.my-account.bookings.active') }}" class="voltar">
			<i class="fa fa-angle-left"></i>
			{{ __('frontend.geral.voltar_site') }}
		</a>
	</div>
</div>
<!-- /.largura-site2.ma -->
@endsection

@push('scripts')
    <script type="text/javascript" src="/frontend/js/vue.min.js"></script>
@endpush
