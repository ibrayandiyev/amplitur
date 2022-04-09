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
										<span class="pacote__subnome">{{$booking->getProductTypeName() }}
                                            @if ( $booking->offer->isHotel() )
                                            <span class="label label-light-info">
                                            - {{$booking->offer->hotelOffer->hotel->name}}
                                            </span>
                                            @endif
                                            - {{ $booking->getProductName() }}</span>
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

                        @include("frontend.my-account.bookings.partials.services")

                        @include("frontend.my-account.bookings.partials.passengers")

						<div class="box mb">
							<header class="box__header">
								<h3 class="box__titulo">{{ __('frontend.pacotes.obs') }}</h3>
							</header>

							<div class="box__conteudo">
								@if($product)

									@if ($product->getOfferType() == \App\Enums\OfferType::BUSTRIP)
										<p data-map-title="{{ $product->getBoardingLocationTitle() }}">
											<strong>{{ __('frontend.pacotes.data_evento') }}: </strong>{{ $product->bustripRoute->getSalesDatesFormattedAttribute() }}<br />
											<strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
											<strong>{{ __('frontend.pacotes.data_embarque') }}: </strong>{{ $product->getFriendlyBoardingDate() }}<br />
											<strong>{{ __('frontend.pacotes.local_embarque') }}: </strong>{{ $product->getBoardingLocationTitle() }}<br />
											@if (!empty($bustripRoute->extra_exclusions))
												{!! $bustripRoute->extra_exclusions !!}<br />
											@endif

											<ul>
												@foreach ($product->bustripRoute->exclusions as $exclusions)
													<li>{{ mb_strtoupper($exclusions->name) }}</li>
												@endforeach
											</ul>
										</p>
									@elseif ($product->getOfferType() == \App\Enums\OfferType::SHUTTLE)
										<p data-map-title="{{ $product->getBoardingLocationTitle() }}">
                                            <strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $product->shuttleRoute->getSalesDatesFormattedAttribute() }}<br />
											<strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
											<strong>{{ __('frontend.pacotes.data_embarque') }}: </strong>{{ $product->getFriendlyBoardingDate() }}<br />
											<strong>{{ __('frontend.pacotes.local_embarque') }}: </strong>{{ $product->getBoardingLocationTitle() }}<br />
											<strong>{{ __('frontend.forms.endereco') }}: </strong>{{ $product->getaddresslocation() }}<br />
											@if (!empty($shuttleRoute->extra_exclusions))
												{!! $shuttleRoute->extra_exclusions !!}<br />
											@endif

											<ul>
												@foreach ($product->shuttleRoute->exclusions as $exclusions)
													<li>{{ mb_strtoupper($exclusions->name) }}</li>
												@endforeach
											</ul>
										</p>
									@elseif ($product->getOfferType() == \App\Enums\OfferType::LONGTRIP && $longtripReady)
										@if($booking->bookingProducts()->count())
											<p>
												<strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $product->longtripRoute->getLongtripRouteDatesAttribute() }}<br />
												<strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
												<strong>{{ __('frontend.pacotes.start_services') }}: </strong>{{ $booking->getLongtripBoardingLocation()->getFriendlyBoardingDate() }}<br />
												<strong>{{ __('frontend.pacotes.local') }}: </strong>{{$booking->getLongtripBoardingLocation()->getBoardingLocationTitle() }} {{$booking->getLongtripBoardingLocation()->getExtendedNameLocation()}} <br />
                                                <strong>{{ __('frontend.pacotes.hotel_preview') }}: </strong><br />
                                                @if($longtripAccommodation && $longtripAccommodation->longtripAccommodationHotels)
													@foreach($longtripAccommodation->longtripAccommodationHotels as $hotel)
														@php
															$address = $hotel->hotel->address;
														@endphp
														<strong>{{ __('frontend.pacotes.hotel_city') }}:</strong>
														{{ $address->city()->name }} - {{ $hotel->hotel->name}} - {{ __('frontend.pacotes.hotel_similar') }}; <br />
													@endforeach
                                                @endif
												<strong>{{ __('frontend.pacotes.data_fim_servico') }}: </strong>{{ $booking->getLongtripBoardingLocation()->getFriendlyEndDate() }}<br />

												@if (!empty($product->longtripRoute->extra_exclusions))
													{!! $product->longtripRoute->extra_exclusions !!}<br />
												@endif

												<ul>
													@foreach ($product->longtripRoute->exclusions as $exclusions)
														<li>{{ mb_strtoupper($exclusions->name) }}</li>
													@endforeach
												</ul>
											</p>
										@endif
									@elseif ($product->getOfferType() == \App\Enums\OfferType::HOTEL)
										<p data-map-title="{{ $product->getHotelName() }}">
											<strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $package->getFriendlyStartDate() }}<br />
											<strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
                                            <strong>{{ __('frontend.pacotes.hotel') }}: </strong>
                                            <span class="sale-date-event">{{ $product->getHotelName() }}</span>
                                            <BR>
                                            <strong>{{ __('frontend.forms.endereco') }}: </strong>
                                            <span class="sale-date-event">{{ $product->gethoteladdress() }}</span>
                                            <BR>
											<strong>CHECK-IN: </strong>{{ $product->getFriendlyCheckin() }} <strong>/</strong> <strong>CHECK-OUT: </strong>{{ $product->getFriendlyCheckout() }}<br />
											<ul>
												@foreach ($product->exclusions as $exclusions)
													<li>{{ mb_strtoupper($exclusions->name) }}</li>
												@endforeach
											</ul>
											@if (!empty($product->extra_exclusions))
												{!! $product->extra_exclusions !!}<br />
											@endif
										</p>
									@endif
								@endif
							</div>
						</div>

                        @include("frontend.my-account.bookings.partials.detailed_values")

						@include("frontend.my-account.bookings.payments.bill_list")

						<div class="flex-grid flex-grid--gutters">
							<div class="box mb grid-cell-xs--12 grid-cell-md--6">
								<header class="box__header">
									<h3 class="box__titulo">{{ __('frontend.reservas.documentos') }}</h3>
								</header>
								<div class="box__conteudo corpo-texto">
                                    @php
                                    switch($booking->document_status){
                                        case App\Enums\ProcessStatus::PENDING:
                                            $status = "pendente";
                                        break;
                                        case App\Enums\ProcessStatus::PARTIAL_RECEIVED:
                                            $status = "pendente";
                                        break;
                                        case App\Enums\ProcessStatus::CONFIRMED:
                                            $status = "liberado";
                                        break;
                                    }
                                    @endphp
									<p>
										{{ __('frontend.conta.status') }}: <span class="box-status status-{{$status}}">{{ __("resources.process-statues.". $booking->document_status) }}</span>
									</p>
									<ul class="lista-documentos">
										<li class="lista-documentos__item">
											<a href="{{ route('frontend.my-account.bookings.showContract', $booking->id) }}" rel="external">
												<br><span class="icone">
													<i class="fas fa-file-alt fa-fw"></i>
												</span>
												{{ __('frontend.reservas.contrato_viagem') }}
											</a>
										</li>
									</ul>
								</div>
							</div>

							<div class="box mb grid-cell-xs--12 grid-cell-md--6">
								<header class="box__header">
									<h3 class="box__titulo">{{ __('frontend.reservas.vouchers') }}</h3>
								</header>
								<div class="box__conteudo corpo-texto">
								@php
								switch($booking->voucher_status){
									case App\Enums\ProcessStatus::PENDING:
										$status = "pendente";
									break;
									case App\Enums\ProcessStatus::RELEASED:
										$status = "liberado";
									break;
								}
								@endphp
									<p>
										{{ __('frontend.conta.status') }}: <span class="box-status status-{{$status}}">{{ __("resources.process-statues.". $booking->voucher_status) }}</span>
									</p>
									@include('frontend.my-account.bookings.vouchers.vouchers_list')
								</div>
							</div>

							@include('frontend.my-account.bookings.history.history_list')


						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
