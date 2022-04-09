@extends('frontend.template.page')

@php
	$product 	= $booking->getProduct();
	$package 	= $booking->package;
	$taxes 		= 0;
	$viewOperations = true;
	switch($booking->status){
		case App\Enums\ProcessStatus::CANCELED:
		case App\Enums\ProcessStatus::REFUNDED:
			$viewOperations = false;
		break;
	}
	$longtripReady = 0;
	$bookingProduct	= null;	// Used for longtrip.
	if($product->getOfferType() == \App\Enums\OfferType::LONGTRIP){
		$bookingProduct 			= $booking->bookingProducts->where('product_type', 'App\Models\LongtripBoardingLocation')->first();
		if($bookingProduct){
			$longtripReady = 1;
		}
		$longtripBoardingLocation 	= $bookingProduct->getProduct();
		$longtripRoute 				= $longtripBoardingLocation->longtripRoute;
		$accommodationTypeId 		= $booking->bookingProducts->where('product_type', 'App\Models\LongtripAccommodationsPricing')->first()->getProduct()->longtrip_accommodation_type_id;
		$longtripAccommodation 		= app(\App\Repositories\OfferRepository::class)->getLongtripRouteAccommodationsType($longtripBoardingLocation->id, $accommodationTypeId);
		$longtripAccommodationHotels = $longtripAccommodation->longtripAccommodationHotels;
	}
@endphp

@section('content')
    <main class="conteudo grupo">
		<div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.conta.minha_conta') }}</h1>
            </header>

            <div class="minha-conta grid">
                <div class="minha-conta__coluna-menu grid-xs--12 grid-md--3">
                    @include('frontend.my-account.partials.navigation')
                </div>

                <div class="minha-conta__coluna-conteudo grid-xs--12 grid-md--9">
                    <div class="minha-conta__pagina">
						<h2 class="pagina__subtitulo">{{ __('frontend.conta.minhas_viagens') }}</h2>
						<div class="grid">
							<div class="grid-xs--12 grid-sm--6 corpo-texto">
								<header class="pacote__titulo pacote__titulo--reservas">
									<h1>
										<span class="pacote__nome">{{ $booking->package->getExtendedTitle() }}</span>
										<span class="pacote__subnome">{{$booking->getProductTypeName() }} - {{ $booking->getProductName() }}</span>
									</h1>
								</header>

								<div class="localizador">
									<div class="localizador__grid">
										<span class="localizador__texto">{{ __('frontend.reservas.localizador') }}</span>
										<span class="localizador__numero">{{ $booking->id }}</span>
									</div>
								</div>
								<p>
									<small>
										{{ __('frontend.reservas.data_hora_compra') }} <time>{{ $booking->createdAtLabel }} {{ $booking->createdAtTimeLabel }}</time>
									</small>
								</p>
							</div>
							<div class="grid-xs--12 grid-sm--6 a-dir">
								<img class="pacote__foto--reservas" src="{{ $booking->package->getThumbnailUrl() }}" />
							</div>
						</div>

						<div class="flex-grid flex-grid--gutters">
							@include('frontend.my-account.bookings.history.history_list')
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
