<head>
    <STYLE type=text/css>

    .ct-h1 {
        color:#004673;
        font-family: Arial, Helvetica, sans-serif;
        font-size:1.25em;
        font-weight:bold;
        margin-bottom:0.5em;
        padding-bottom:0.1em;
        text-transform:uppercase;
        text-align:center;
        }

    .ct-p {
        text-align: justify;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }


    .ct-h2 {
        border-bottom:0.05em solid #1e8fdb;
        color:#004673;
        font-family: Arial, Helvetica, sans-serif;
        font-size:1.00em;
        font-weight:normal;
        margin-bottom:0.5em;
        padding-bottom:0.1em;
        text-transform:uppercase;
    }

    .li-p {
        text-align: justify;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    hr{
        display:block;
        height:1px;
        border:0;
        border-top:1px solid #1e8fdb;
        margin:1em 0;
        padding:0;
    }

    .ul-ct{
        padding: 0px;
        margin-left: 30px;
    }

    </style>
    </head>
    <body>

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

        <table width="100%">
            <tr>
                <td width="20%">
                    <img src="https://www.amp-travels.com/images/amp-travel-front-bgwhite2.png" alt="AMP Travels">
                </td>
                <td class="align-left">
                    <h1 class="ct-h1">{{ __('contract.head') }}</h1>
                </td>
            </tr>
        </table>

    <div id="corpo-doc">
        <hr>
        <p class="ct-p">
		<strong>{{ __('contract.main_service') }} {{ mb_strtoupper($booking->getName()) }} - {{ mb_strtoupper($booking->getProductTypeName()) }} - {{ mb_strtoupper($booking->getProductName()) }}</strong>
    </p>

	<p class="ct-p">
        <strong>{{ __('contract.booking_code') }}</strong>: {!! $booking->id ?? '<i>in definition</i>' !!} | <strong>{{ __('contract.boarding_date') }}</strong>: {{ $booking->getStartsAtLabelAttribute() }}
		@if($booking->check_contract != null) | <strong>{{ __('contract.digital_signed') }}</strong>: {{ $booking->getDigitalSigned(1) }} @endif
	</p>

	<hr />


	<p class="ct-p">
        {!!__('contract.contract_sides_1') !!} {{ mb_strtoupper($booking->getClientName()) }},

        @if ($booking->clientHasPassport())
            <strong>{{ __('contract.passport') }}</strong> {{ $booking->getClientPassport() }}
        @else
            <strong>{{ __('contract.cpf') }}</strong> {{ $booking->getClientDocument() }}, <strong>{{ __('contract.id_document') }}</strong> {{ $booking->getClientIdentity() }}
        @endif

        <strong>{{ __('contract.telephone') }}</strong> {{ $booking->getClientPhone() }}
        <strong>{{ __('contract.address') }}</strong> {{ mb_strtoupper($booking->getClientAddress()) }}
		<strong>{{ __('contract.contract_sides_2') }}</strong>
	</p>

	<p class="ct-p">
		{!! __('contract.item_a') !!}
	</p>

    <H2 class="ct-h2">{{ __('contract.inclusions') }}</h2>

        @if($product)
        <ul class="ul-ct">
            @if ($product->getOfferType() == \App\Enums\OfferType::LONGTRIP)
                @if($bookingProduct)
                    @php

                        $hotels = collect();

                    @endphp

                            @foreach($longtripAccommodationHotels as $longtripAccommodationHotel)
                                @php
                                    $hotel = $longtripAccommodationHotel->hotel;
                                @endphp
                                    @if($hotel)
                                        <li class="li-p">{{ $longtripAccommodationHotel->days() }} {{ __('contract.room_nights') }} {{ mb_strtoupper(city($hotel->address->getCityName())) }}
                                            — {{ $longtripAccommodationHotel->longtripAccommodation->type->name }}
                                            — {{ __('frontend.pacotes.check_in') }}: {{ $longtripAccommodationHotel->checkin ? $longtripAccommodationHotel->checkin->format('d/m/Y') : '-' }} - {{ __('frontend.pacotes.check_out') }}: {{ $longtripAccommodationHotel->checkout ? $longtripAccommodationHotel->checkout->format('d/m/Y') : '-' }}
                                    @endif
                            @endforeach

                            @foreach ($product->longtripRoute->inclusions as $inclusion)
                                <li class="li-p">{{ $inclusion->name }}</li>
                            @endforeach
                        </ul>
                                    @if (!empty($longtripRoute->extra_inclusions))
                                    <p class="ct-p"> {!! $longtripRoute->extra_inclusions !!}</p>
                                    @endif

                    @endif

                            @else
                                @forelse($booking->getProduct()->getInclusions() ?? [] as $inclusion)
                                    <li class="li-p">{{ $inclusion->name }}</li>
                                @empty
                                    <li class="li-p"><i>{{ __('frontend.reservas.sem_inclusao') }}</i></li>
                                @endforelse
                            @endif
            @endif
            </ul>

    <H2 class="ct-h2">{{ __('contract.notes') }}</h2>


        @if($product)

        @if ($product->getOfferType() == \App\Enums\OfferType::BUSTRIP)
            <p class="ct-p" data-map-title="{{ $product->getBoardingLocationTitle() }}">
                <strong>{{ __('frontend.pacotes.data_evento') }}: </strong>{{ $product->bustripRoute->getSalesDatesFormattedAttribute() }}<br />
                <strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
                <strong>{{ __('frontend.pacotes.data_embarque') }}: </strong>{{ $product->getFriendlyBoardingDate() }}<br />
                <strong>{{ __('frontend.pacotes.local_embarque') }}: </strong>{{ $product->getBoardingLocationTitle() }}<br />
                @if (!empty($bustripRoute->extra_exclusions))
                    {!! $bustripRoute->extra_exclusions !!}<br />
                @endif

                <ul class="ul-ct">
                    @foreach ($product->bustripRoute->exclusions as $exclusions)
                        <li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
                    @endforeach
                </ul>
            </p>
        @elseif ($product->getOfferType() == \App\Enums\OfferType::SHUTTLE)
            <p class="ct-p" data-map-title="{{ $product->getBoardingLocationTitle() }}">
                <strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $product->shuttleRoute->getSalesDatesFormattedAttribute() }}<br />
                <strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
                <strong>{{ __('frontend.pacotes.data_embarque') }}: </strong>{{ $product->getFriendlyBoardingDate() }}<br />
                <strong>{{ __('frontend.pacotes.local_embarque') }}: </strong>{{ $product->getBoardingLocationTitle() }}<br />
                <strong>{{ __('frontend.forms.endereco') }}: </strong>{{ $product->getaddresslocation() }}<br />
                @if (!empty($shuttleRoute->extra_exclusions))
                    {!! $shuttleRoute->extra_exclusions !!}<br />
                @endif

                <ul class="ul-ct">
                    @foreach ($product->shuttleRoute->exclusions as $exclusions)
                        <li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
                    @endforeach
                </ul>
            </p>
        @elseif ($product->getOfferType() == \App\Enums\OfferType::LONGTRIP && $longtripReady)
            @if($booking->bookingProducts()->count())
                <p class="ct-p">
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

                    <ul class="ul-ct">
                        @foreach ($product->longtripRoute->exclusions as $exclusions)
                            <li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
                        @endforeach
                    </ul>
                </p>
            @endif
        @elseif ($product->getOfferType() == \App\Enums\OfferType::HOTEL)
            <p class="ct-p" data-map-title="{{ $product->getHotelName() }}">
                <strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $package->getFriendlyStartDate() }}<br />
                <strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
                <strong>{{ __('frontend.pacotes.hotel') }}: </strong>
                <span class="sale-date-event">{{ $product->getHotelName() }}</span>
                <BR>
                <strong>{{ __('frontend.forms.endereco') }}: </strong>
                <span class="sale-date-event">{{ $product->gethoteladdress() }}</span>
                <BR>
                <strong>CHECK-IN: </strong>{{ $product->getFriendlyCheckin() }} <strong>/</strong> <strong>CHECK-OUT: </strong>{{ $product->getFriendlyCheckout() }}<br />
                <ul class="ul-ct">
                    @foreach ($product->exclusions as $exclusions)
                        <li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
                    @endforeach
                </ul>
                @if (!empty($product->extra_exclusions))
                    {!! $product->extra_exclusions !!}<br />
                @endif
            </p>
        @endif
    @endif

    <p class="ct-p">
		<strong>{{ __('contract.item_b') }}:</strong>
    </p>


		@foreach ($booking->bookingPassengers as $key => $passenger)
			@php ($key = $key + 1)
			<p class="ct-p">
				<strong>{{ __('contract.name') }}:</strong> <span id="field_passageiro_{{$key}}_nome">{{ mb_strtoupper($passenger->name) }}</span>,
				<strong>{{ __('contract.mobile_phone') }}:</strong>  <span id="field_passageiro_{{$key}}_fone">@if ($mustPreRenderPhone) {{ $passenger->phone }} @endif</span>,
				<strong>{{ __('contract.id_document') }}:</strong>  <span id="field_passageiro_{{$key}}_fone">{{ $passenger->identity}} {{ $passenger->uf }}</span>,
				<strong>{{ __('contract.birth_date') }}:</strong> <span id="field_passageiro_{{$key}}_data_nascimento">{{ $passenger->birthdate ? $passenger->birthdate->format('d/m/Y') : '' }}</span>,

				<ul class="ul-ct">
					@foreach ($passenger->bookingPassengerAdditionals as $bookingPassengerAdditional)
						<li class="li-p">{{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }}</li>
					@endforeach
				</ul>
			</p>
		@endforeach


	@if ($showPaymentDetails)
		<p class="ct-p">
			<strong>{{ __('contract.item_c') }}:</strong>
			{{ __('contract.total_amount') }} {{ $booking->currency->code }} {{ $booking->getTotalLabel() }} ({{ mb_strtoupper(moneyToString($booking->getTotal(), $booking->currency->code, $booking->bookingClient->language)) }}), {{ __('contract.total_paid') }}:
		</p>

		<ul class="ul-ct">
			@foreach ($booking->getBills() as $bill)
				<li class="li-p">{{ $bill->getDescription() }}</li>
			@endforeach
		</ul>

		<p class="ct-p">
			<strong>{{ __('contract.item_d') }}</strong>
			{{ __('contract.item_d_inform') }}:

			@foreach ($booking->getTotalQuoted() as $quotation)
				{{ $quotation }}
			@endforeach

			{{ __('contract.item_d_acording') }}
		</p>
	@endif

    <H2 class="ct-h2">{{ __('contract.genarl_conditions') }}</h2>

	<p class="ct-p">
		<strong>{{ __('contract.cl_1') }}:</strong>
		<br>
		{{ __('contract.cl_1_1') }}
		<br>
		{{ __('contract.cl_1_2') }}
		<br>
		{{ __('contract.cl_1_3') }}
	</p>

	<p class="ct-p">
		<strong>{{ __('contract.cl_2') }}:</strong>
		<br>
		{{ __('contract.cl_2_1') }}
		<br>
		{{ __('contract.cl_2_2') }}
		<br>
		{{ __('contract.cl_2_3') }}
		<br>
        {{ __('contract.cl_2_4') }}
		<br>
		{{ __('contract.cl_2_5') }}
	</p>

	<p class="ct-p">
		<strong>{{ __('contract.cl_3') }}:</strong>
		<br>
		{{ __('contract.cl_3_1') }}
		<br>
		{{ __('contract.cl_3_2') }}
		<br>
		{{ __('contract.cl_3_3') }}
		<br>
		{{ __('contract.cl_3_4') }}
		<br>
		{{ __('contract.cl_3_5') }}
		<br>
		{{ __('contract.cl_3_6') }}
		<br>
		{{ __('contract.cl_3_7') }}
		<br>
		{{ __('contract.cl_3_8') }}
		<br>
		{{ __('contract.cl_3_9') }}
		<br>
		{{ __('contract.cl_3_10') }}
	</p>

	<p class="ct-p">
		<strong>{{ __('contract.cl_4') }}:</strong>
		<br>
		{{ __('contract.cl_4_1') }}
		<br>
		{{ __('contract.cl_4_2') }}
		<br>
		{{ __('contract.cl_4_3') }}
		<br>
		{{ __('contract.cl_4_4') }}
		<br>
		{{ __('contract.cl_4_5') }}
		<br>
		{{ __('contract.cl_4_6') }}
	</p>

	<p class="ct-p">
		<strong>{{ __('contract.cl_5') }}:</strong>
		<br>
		{{ __('contract.cl_5_1') }}
		<br>
		{{ __('contract.cl_5_2') }}
	</p>
	<ul class="ul-ct">
		<li class="li-p">{{ __('contract.cl_5_2_p1') }}</li>
		<li class="li-p">{{ __('contract.cl_5_2_p2') }}</li>
		<li class="li-p">{{ __('contract.cl_5_2_p3') }}</li>
	</ul>
	<p class="ct-p">
		{{ __('contract.cl_5_3') }}
		<br>
		{{ __('contract.cl_5_4') }}
		<br>
		{{ __('contract.cl_5_5') }}
		<br>
		{{ __('contract.cl_5_6') }}
		<br>
		{{ __('contract.cl_5_7') }}
		<br>
		{{ __('contract.cl_5_8') }}
		<br>
		{{ __('contract.cl_5_9') }}
	</p>

	<p class="ct-p">
		<strong>{{ __('contract.cl_6') }}:</strong>
		<br>
		{{ __('contract.cl_6_1') }}
		<br>
		{{ __('contract.cl_6_2') }}
		<br>
		{{ __('contract.cl_6_3') }}
	</p>

	<p class="ct-p">
		@if($booking->check_contract != null) <strong>{{ __('contract.digital_signed') }}</strong>: {{ $booking->getDigitalSigned(1) }} - IP/MAC {{ $booking->ip}}@endif
	</p>

		<hr>
    <p>
		<small>
			{{ __('contract.booking_n') }}: {!! $booking->id ?? '<i>in definition</i>' !!} | {{ date('d/m/Y H:i:s') }}
		</small>
    </p>
</div>

