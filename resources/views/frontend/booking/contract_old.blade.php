<head>
    <STYLE type=text/css>

    .ct-h1 {
        color:#004673;
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

    .li-p {
            text-align: justify;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .ct-h2 {
        border-bottom:0.05em solid #1e8fdb;
        color:#004673;
        font-size:1.00em;
        font-weight:normal;
        margin-bottom:0.5em;
        padding-bottom:0.1em;
        text-transform:uppercase
    }

    hr{
        display:block;
        height:1px;
        border:0;
        border-top:1px solid #1e8fdb;
        margin:1em 0;
        padding:0
    }

    </style>
    </head>
    <body>

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
		@if ($booking->getProduct() instanceof \App\Models\LongtripAccommodationsPricing || $booking->getProduct() instanceof \App\Models\LongtripBoardingLocation)
			@php
				$longtripBoardingLocation = $booking->bookingProducts->where('product_type', 'App\Models\LongtripBoardingLocation')->first()->getProduct();
				$longtripRoute = $longtripBoardingLocation->longtripRoute;
				$accommodationTypeId 	= $booking->bookingProducts->where('product_type', 'App\Models\LongtripAccommodationsPricing')->first()->getProduct()->longtrip_accommodation_type_id;
				$longtripAccommodation 	= app(\App\Repositories\OfferRepository::class)->getLongtripRouteAccommodationsType($longtripBoardingLocation->id, $accommodationTypeId);
				$hotels = collect();
			@endphp

			<ul>
				@foreach($longtripAccommodation->longtripAccommodationHotels as $hotel)
					@php
						$hotels->push(mb_strtoupper($hotel->hotel->name));
					@endphp
				<li class="li-p">{{ $longtripRoute->getLongtripRouteDays() }} {{ __('contract.room_nights') }} <strong>{{ mb_strtoupper(city($longtripAccommodation->city)) }}</strong> — {{ __('contract.checkin') }}: <strong>{{ $longtripAccommodation->checkin ? $longtripAccommodation->checkin->format('d/m/Y') : '-' }}</strong> {{ __('contract.checkout') }}: <strong>{{ $longtripAccommodation->checkout ? $longtripAccommodation->checkout->format('d/m/Y') : '-' }}</strong>
                @endforeach
                @foreach ($longtripRoute->inclusions as $inclusion)
					<li class="li-p">{{ $inclusion->name }}</li>
				@endforeach
			</ul>

			@if (!empty($longtripRoute->extra_inclusions))
				<p class="ct-p">{!! $longtripRoute->extra_inclusions !!}</p>
			@endif
		@elseif ($booking->getProduct() instanceof \App\Models\HotelAccommodation)

            <ul>
				@php ($accommodationStructures = $booking->getProduct()->getStructures())
				@php ($accommodationStructures = !empty($accommodationStructures) ? $accommodationStructures->pluck('name')->toArray() : [])
				@php ($accommodationStructuresStrings = implode(', ', $accommodationStructures))

				@foreach ($booking->getProduct()->getInclusions() as $inclusion)
					<li class="li-p">{{ $inclusion->name }}</li>
				@endforeach

				@if (!empty($booking->getProduct()->extra_inclusions))
                    <p class="ct-p">{!! $booking->getProduct()->extra_inclusions !!}</p>
				@endif

			</ul>

		@else
			<ul>
				@forelse($booking->getProduct()->getInclusions() ?? [] as $inclusion)
					<li class="li-p">{{ $inclusion->name }}</li>
				@empty
					<li class="li-p"><i>{{ __('contract.no_inclusion') }}</i></li>
				@endforelse
			</ul>
		@endif

    <H2 class="ct-h2">{{ __('contract.notes') }}</h2>
	@if ($booking->offer->isBustrip())
		<p class="ct-p" data-map-title="{{ $booking->getProduct()->getBoardingLocationTitle() }}">
			<strong>{{ __('contract.date_event') }}: </strong> {{ $booking->getProduct()->bustripRoute->getSalesDatesFormattedAttribute() }}<br />
			<strong>{{ __('contract.venue') }}: </strong> {{ $booking->package->getLocation() }}<br />
			<strong>{{ __('contract.boarding_date') }}: </strong>{{ $booking->getProduct()->getFriendlyBoardingDate() }}<br />
			<strong>{{ __('contract.boarding_place') }}: </strong>{{ $booking->getProduct()->getBoardingLocationTitle() }}<br />
			@if (!empty($booking->getProduct()->bustripRoute->extra_exclusions))
				{!! $booking->getProduct()->bustripRoute->extra_exclusions !!}<br />
			@endif

			<ul>
				@foreach ($booking->getProduct()->bustripRoute->exclusions as $exclusions)
					<li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
				@endforeach
			</ul>
		</p>
	@elseif ($booking->offer->isHotel())
		<p class="ct-p" data-map-title="{{ $booking->getProduct()->getHotelName() }}">
			<strong>{{ __('contract.date_event') }}: </strong> {{ $booking->package->getFriendlyStartDate() }}<br />
			<strong>{{ __('contract.venue') }}: </strong> {{ $booking->package->getLocation() }}<br>
            <strong>{{ __('frontend.pacotes.hotel') }}: </strong> <span>{{ $booking->getProduct()->getHotelName() }} </span><BR>
            <strong>{{ __('frontend.forms.endereco') }}: </strong> <span class="sale-date-event">{{ $booking->getProduct()->gethoteladdress() }}</span><br>
            <strong>{{ __('contract.checkin') }}: </strong>{{ $booking->getProduct()->getFriendlyCheckin() }} <strong>/</strong> <strong>{{ __('contract.checkout') }}: </strong>{{ $booking->getProduct()->getFriendlyCheckout() }}
            @if (!empty($booking->getProduct()->extra_exclusions))
              {!! $booking->getProduct()->extra_exclusions !!}<br>
            @endif
			<ul>
				@foreach ($booking->getProduct()->exclusions as $exclusions)
					<li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
				@endforeach
			</ul>
		</p>
	@elseif ($booking->offer->isShuttle())
		<p class="ct-p" data-map-title="{{ $booking->getProduct()->getBoardingLocationTitle() }}">
            <strong>{{ __('contract.date_event') }}: </strong> {{ $booking->getProduct()->shuttleRoute->getSalesDatesFormattedAttribute() }}<br />
			<strong>{{ __('contract.venue') }}: </strong> {{ $booking->package->getLocation() }}<br />
			<strong>{{ __('contract.boarding_date') }}: </strong>{{ $booking->getProduct()->getFriendlyBoardingDate() }}<br />
			<strong>{{ __('contract.boarding_place') }}: </strong>{{ $booking->getProduct()->getBoardingLocationTitle() }}<br />
            <strong>{{ __('frontend.forms.endereco') }}: </strong>{{ $booking->getproduct()->getaddresslocation() }}<br />
			@if (!empty($booking->getProduct()->shuttleRoute->extra_exclusions))
				{!! $booking->getProduct()->shuttleRoute->extra_exclusions !!}<br />
			@endif

			<ul>
				@foreach ($booking->getProduct()->shuttleRoute->exclusions as $exclusions)
					<li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
				@endforeach
			</ul>
		</p>
	@elseif ($booking->offer->isLongtrip())
		<p class="ct-p" data-map-title="{{ $longtripBoardingLocation->getBoardingLocationTitle() }}">
			<strong>{{ __('contract.date_event') }}: </strong> {{ $booking->getProduct()->longtripRoute->getLongtripRouteDatesAttribute() }}<br />
			<strong>{{ __('contract.venue') }}: </strong> {{ $booking->package->getLocation() }}<br />
			<strong>{{ __('contract.start_service') }}: </strong>{{ $longtripBoardingLocation->getFriendlyBoardingDate() }}<br />
			<strong>{{ __('contract.start_location') }}: </strong>{{ $longtripBoardingLocation->getBoardingLocationTitle() }} {{$booking->getLongtripBoardingLocation()->getExtendedNameLocation()}}<br />
			<strong>{{ __('frontend.pacotes.hotel_preview') }}: </strong> <br>
            @foreach($longtripAccommodation->longtripAccommodationHotels as $hotel)
            <strong>{{ __('frontend.pacotes.hotel_city') }}:</strong>
            nome da cidade - {{ implode([$hotel->hotel->name]) }} {{ __('contract.similar') }}<br />

            @endforeach

            @if (!empty($longtripRoute->extra_exclusions))
				{!! $longtripRoute->extra_exclusions !!}<br />
			@endif

			<ul>
				@foreach ($longtripRoute->exclusions as $exclusions)
					<li class="li-p">{{ mb_strtoupper($exclusions->name) }}</li>
				@endforeach
			</ul>
		</p>
	@endif

    <p class="ct-p">
		<strong>{{ __('contract.item_b') }}:</strong>
    </p>

	<ul>
		@foreach ($booking->bookingPassengers as $key => $passenger)
			@php ($key = $key + 1)
			<li class="li-p">
				<strong>{{ __('contract.name') }}:</strong> <span id="field_passageiro_{{$key}}_nome">{{ mb_strtoupper($passenger->name) }}</span>,
				<strong>{{ __('contract.mobile_phone') }}:</strong>  <span id="field_passageiro_{{$key}}_fone">@if ($mustPreRenderPhone) {{ $passenger->phone }} @endif</span>,
				<strong>{{ __('contract.id_document') }}:</strong>  <span id="field_passageiro_{{$key}}_fone">{{ $passenger->identity}} {{ $passenger->uf }}</span>,
				<strong>{{ __('contract.birth_date') }}:</strong> <span id="field_passageiro_{{$key}}_data_nascimento">{{ $passenger->birthdate ? $passenger->birthdate->format('d/m/Y') : '' }}</span>,

				<ul>
					@foreach ($passenger->bookingPassengerAdditionals as $bookingPassengerAdditional)
						<li class="li-p">{{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }}</li>
					@endforeach
				</ul>
			</li>
		@endforeach
	</ul>

	@if ($showPaymentDetails)
		<p class="ct-p">
			<strong>{{ __('contract.item_c') }}:</strong>
			{{ __('contract.total_amount') }} {{ $booking->currency->code }} {{ $booking->getTotal() }} ({{ mb_strtoupper(moneyToString($booking->getTotal(), $booking->currency->code, $booking->bookingClient->language)) }}), {{ __('contract.total_paid') }}:
		</p>

		<ul>
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
	<ul>
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

