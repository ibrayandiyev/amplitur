@php
    $hotels = collect();
@endphp
<div class="servico-principal__info">
    <div class="corpo-texto">
    	<h3>{{ __('frontend.pacotes.inclusoes') }}</h3>
    	    <ul>
                @if($longtripAccomodation && $longtripAccomodation->longtripAccommodationHotels)
                    @foreach($longtripAccomodation->longtripAccommodationHotels as $hotel)
                    <li>
                        @php
                            if($hotel->checkin == null || $hotel->checkout == null){
                                continue;
                            }
                            $datediff = $hotel->checkin->diffInDays($hotel->checkout);
                            $address = json_decode($hotel->hotel->address->city());
                            $labelName = $hotel->getLabelName();
                        @endphp
                        {{ $datediff }} {{__('frontend.pacotes.noites_hospedagem')}}
                        {{ $address->name }} - {{ $labelName }} - {{ __('frontend.pacotes.check_in')}} {{ $hotel->checkin->format("d/m")}} - {{ __('frontend.pacotes.check_out')}} {{ $hotel->checkout->format("d/m")}},
                    </li>
                    @endforeach
                @endif

                @foreach ($longtripRoute->inclusions as $inclusion)
                    <li>{{ $inclusion->name }}</li>
                @endforeach
            </ul>
                @if (!empty($longtripRoute->extra_inclusions))
                    {!! $longtripRoute->extra_inclusions !!}
                @endif
            <br><br>
            <h3>{{ __('frontend.pacotes.obs') }}</h3>
            <p data-map-title="{{ $product->getBoardingLocationTitle() }}">
                <strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $product->longtripRoute->getLongtripRouteDatesAttribute() }}<br />
                <strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
                <strong>{{ __('frontend.pacotes.start_services') }}: </strong>{{ $product->getFriendlyBoardingDate() }}<br />
                <strong>{{ __('frontend.pacotes.local') }}: </strong>{{ $product->getBoardingLocationTitle() }} {{ $product->getExtendedNameLocation()}}<br />
                <strong>{{ __('frontend.pacotes.hotel_preview') }}: </strong><br />
                @if($longtripAccomodation && $longtripAccomodation->longtripAccommodationHotels)
                @foreach($longtripAccomodation->longtripAccommodationHotels as $hotel)
                <strong>{{ __('frontend.pacotes.hotel_city') }}:</strong>
                {{ $address->name }} - {{ $hotel->hotel->name}} - {{ __('frontend.pacotes.hotel_similar') }}; <br />
                @endforeach
                @endif
                <ul>
                    @foreach ($longtripRoute->exclusions as $exclusions)
                        <li>{{ ($exclusions->name) }};</li>
                    @endforeach
                </ul>
                @if (!empty($longtripRoute->extra_exclusions))
                    {!! $longtripRoute->extra_exclusions !!}<br />
                @endif
            </p>

        <div class="pacote-observacoes">
            <ul>
                @if ($longtripRoute->observations && count($longtripRoute->observations))
                    @foreach ($longtripRoute->observations as $observation)
                    <li><strong>{{ ($observation->name) }}</strong></li>
                    @endforeach
                @endif
            </ul>
                @if (!empty($longtripRoute->extra_observations))
                    {!! ($longtripRoute->extra_observations) !!}
                @endif
        </div>
        <div class="pacote-descricao corpo-texto font-smaller">
            <div id="mapdfff5" style="height: 200px;"></div>
        </div>
        @if($product)
        <script type="text/javascript">
            (function initMap() {
                var myLatlng = {lat: {{ $product->getLatitude()  }}, lng: {{ $product->getLongitude()  }}};

                var mapdfff5 = new google.maps.Map(document.getElementById('mapdfff5'), {
                    zoom: 13,
                    center: myLatlng,
                    zoomControl: true,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: false
                });

                var image0 = '{{ $product->getMapMarkUrl() }}';

                var beachMarker0 = new google.maps.Marker({
                    position: {lat: "{{ $product->getLatitude()  }}", lng: "{{ $product->getLongitude()  }}"},
                    map: mapdfff5,
                    icon: image0,
                    title: $('p[data-map-title]').attr('data-map-title')
                });

                var image1 = '{{ $package->getMapMarkUrl() }}';

                var beachMarker1 = new google.maps.Marker({
                    position: {lat: {{ $package->getLatitude()  }}, lng: {{ $package->getLongitude() }}},
                    map: mapdfff5,
                    icon: image1,
                    title: '{{ $package->getLocation() }}'
                });
            })();
        </script>
        @endif
    </div>
</div>
