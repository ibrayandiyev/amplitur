@extends('backend.template.document')

@section('content')

<div class="grupo corpo-doc">
	<div class="grid2-1" id="logo-doc">
		<img src="https://www.amp-travels.com/images/amp-travel-front-bgwhite2.png" alt="Amp Travels">
	</div>
	<div class="titulo-grande grid2-1 ultima">
		<h1>Digital Voucher Nº {{ $booking->id }}</h1>
	</div>
</div>

<div id="corpo-doc">
	<h1>{{ mb_strtoupper($booking->getName()) }} <br> {{ $booking->package->getLocation() }}</h1>

	<p>
		<strong>Localizador N° / <em>Reserva N° / Booking Code</em></strong>: {{ $booking->id }}
		<br>
		<strong>Data de Emissão / <em>Fecha de emisión / Issue Date</em>:</strong> {{$voucher->created_at->format("d/m/Y")}}
		<br>
		<strong>Autorizado por / <em>Autorizado por / Authorized by</em>:</strong> - NOME DE QUEM LIBEROU AQUI
	</p>

	<h2>Fornecedor / <em>Provedor / Supplier</em></h2>
	<p>
		@if($provider)
			{{$provider->registry}}<br />
			{{$provider->address->address}},
			{{$provider->address->number}},
			{{$provider->address->neighborhood}}
			@if($provider->address->complement != "")
				, {{ $provider->address->complement }}
			@endif
			- {{$provider->address->city()->name}} - {{$provider->address->state()->name}} - {{$provider->address->country()->name}}
			+ phone?

		@else
				AMP Travels Ltd.
				<br>
				138, Chapel Street
				Salford - United Kingdom - M3 6DE - TEL +44 (0) 1615 116 350
		@endif
	</p>

	<h2>Cliente / <em>Cliente / Client</em></h2>
	<p>
				AMP Travels Ltd.
				<br>
				138, Chapel Street
				Salford - United Kingdom - M3 6DE - TEL +44 (0) 1615 116 350
	</p>

	<hr>

	<p class="a-centro">
		<strong>
				Favor providenciar para os passageiros abaixo os seguintes serviços:
			<br>
			<em>Por favor haga arreglos para los pasajeros bajo los siguientes servicios:
			<BR>
				Please provide the services to the passengers below:</em>
		</strong>
	</p>

	<h2>Passageiros / <em>Pasajeros / Passengers</em></h2>
	@if($booking->bookingPassengers)

		@foreach ($booking->bookingPassengers as $key => $bookingPassenger)
			<p>
				<strong>
					{{ $loop->iteration }} - {{ mb_strtoupper($bookingPassenger->name) }}
					@if(1==2)
						<!-- Bus seat -->
					@endif
				</strong>
			</p>
		@endforeach
	@else
		<p>
            No Passenger Joined.
		</p>
	@endif


	<h2>Serviços / <em>Servicios / Services</em></h2>
	<p class="box">
		Please check all services that are described below so that there are no problems at the time of delivery. In case of discrepancies, please contact URGENTLY with the Travel Agency selling the package or directly with the AMP Travels Ltd.
	</p>

	{!! $voucher->services !!}
	@if($booking->bookingPassengers)

		@foreach ($booking->bookingPassengers as $key => $bookingPassenger)
			@if (empty($booking->getDates()))
			<p>
				<strong>{{ mb_strtoupper($booking->getProductName()) }}</strong>
				{{ money($booking->getProductPrice(), currency(), $booking->offer->currency) }}
			</p>
			@else
				@foreach ($booking->getDates() as $date)
				<p>
					<strong>{{ mb_strtoupper($booking->getProductName($date)) }}</strong>
						{{ money($booking->getProductPrice($date), currency(), $booking->offer->currency) }}
				</p>
				@endforeach
			@endif
			@foreach ($bookingPassenger->bookingPassengerAdditionals ?? [] as $bookingPassengerAdditional)
			<p>
				<strong>{{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }}</strong>
					{{ money($bookingPassengerAdditional->additional->getPrice()) }}
			</p>
			@endforeach

		@endforeach
	@endif
	@if($voucher->comments != "")
		<h2>Observações / <em>Observaciones / Notes</em></h2>
		{!! $voucher->comments !!}
	@endif

	<p>
		<small>
			AMP Travels Ltd.  | {{ date('d/m/Y H:i:s')}}
		</small>
	</p>
</div>

@endsection
