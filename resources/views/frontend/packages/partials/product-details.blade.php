<div class="servico-principal__info">
    <div class="corpo-texto">
    	<h3>{{ __('frontend.pacotes.inclusoes') }}</h3>
        @if ($product->getOfferType() == \App\Enums\OfferType::BUSTRIP)
    	    <ul>
                @foreach ($product->getInclusions() as $inclusion)
                    <li>{{ $inclusion->name }}</li>
                @endforeach
            </ul>

            @if (!empty($product->bustripRoute->extra_inclusions))
                {!! $product->bustripRoute->extra_inclusions !!}
            @endif

            @component('frontend.template.components.gallery')
                @slot('offer', $product->getOffer())
                @slot('class', 'product-gallery')
            @endcomponent

            <h3>{{ __('frontend.pacotes.obs') }}</h3>

                <p data-map-title="{{ $product->getBoardingLocationTitle() }}">
                    <strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $product->bustripRoute->getSalesDatesFormattedAttribute() }}<br />
                    <strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
                    <strong>{{ __('frontend.pacotes.data_embarque') }}: </strong>{{ $product->getFriendlyBoardingDate() }}<br />
                    <strong>{{ __('frontend.pacotes.local_embarque') }}: </strong>{{ $product->getBoardingLocationTitle() }}<br />
                </p>

                    <ul>
                            @foreach ($product->bustripRoute->exclusions as $exclusions)
                                <li>{{ ($exclusions->name) }}</li>
                            @endforeach
                    </ul>
                             @if (!empty($product->bustripRoute->extra_exclusions))
                             {!! $product->bustripRoute->extra_exclusions !!}
                             @endif
                <BR>
                <div class="pacote-observacoes">
                    <ul>
                        @if (count($product->bustripRoute->observations))
                            @foreach ($product->bustripRoute->observations as $observation)
                                <li><strong>{{ ($observation->name) }}</li></strong>
                            @endforeach
                        @endif
                    </ul>
                        @if (!empty($product->bustripRoute->extra_observations))
                            {!! ($product->bustripRoute->extra_observations) !!}
                        @endif

                    </div>
            @elseif ($product->getOfferType() == \App\Enums\OfferType::SHUTTLE)

            <ul>
                @foreach ($product->getInclusions() as $inclusion)
                    <li>{{ $inclusion->name }} </li>
                @endforeach
            </ul>

            @if (!empty( $product->shuttleRoute->extra_inclusions ))
                {!! $product->shuttleRoute->extra_inclusions !!}
            @endif

            @component('frontend.template.components.gallery')
                @slot('offer', $product->getOffer())
                @slot('class', 'product-gallery')
            @endcomponent

            <h3>{{ __('frontend.pacotes.obs') }}</h3>
                <p data-map-title="{{ $product->getBoardingLocationTitle() }}">
                    <strong>{{ __('frontend.pacotes.data_evento') }}: </strong> {{ $product->shuttleRoute->getSalesDatesFormattedAttribute() }}<br />
                    <strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br />
                    <strong>{{ __('frontend.pacotes.data_embarque') }}: </strong>{{ $product->getFriendlyBoardingDate() }}<br />
                    <strong>{{ __('frontend.pacotes.local_embarque') }}: </strong>{{ $product->getBoardingLocationTitle() }}<br />
                    <strong>{{ __('frontend.forms.endereco') }}: </strong>{{ $product->getaddresslocation() }}<br />
                </p>

                    <ul>
                            @foreach ($product->shuttleRoute->exclusions as $exclusions)
                                <li>{{ ($exclusions->name) }}</li>
                            @endforeach
                    </ul>
                            @if (!empty($product->shuttleRoute->extra_exclusions))
                                {!! $product->shuttleRoute->extra_exclusions !!}
                            @endif
                <br>
                <div class="pacote-observacoes">
                    <ul>
                        @if (count($product->shuttleRoute->observations))
                            @foreach ($product->shuttleRoute->observations as $observation)
                                <li><strong>{{ ($observation->name) }}</li></strong>
                            @endforeach
                        @endif
                    </ul>
                        @if (!empty($product->shuttleRoute->extra_observations))
                            {!! ($product->shuttleRoute->extra_observations) !!}
                        @endif
                </div>
            @endif

            <div class="pacote-descricao corpo-texto font-smaller">
                <div id="mapdfff5" style="height: 200px;"></div>
            </div>

        @include('frontend.template.scripts.gallery-slideshow', ['class' => '.product-gallery'])

        <script>
            function initMap() {
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
                    position: {lat: {{ $product->getLatitude()  }}, lng: {{ $product->getLongitude()  }}},
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
            }
        </script>

        <script>
            initMap();
        </script>
    </div>
</div>
