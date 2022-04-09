@if (is_null($product))
@else
    <div class="servico-principal__info servico-principal-produto">
        <div class="lista-passageiros__item passageiro mb">
            <header class="passageiro__header" style="margin-bottom: 0;">
                <h3 class="passageiro__titulo" style="color: white; margin-bottom: 0;">{{ __('frontend.pacotes.selecione_datas') }} </h3>
            </header>
            @foreach ($package->bookablePeriod as $key => $period)
                @php ($price = $product->getPrice($period['date']))
                <div class="form__checkbox form__checkbox--checkbox servico-principal servico-principal__grupo-accommodation servico-principal__fatorpass-{{ $product->getCapacity() }} servico--{{ $product->getStockStatusClass($period['date']) }}" id="servico-adicional-{{ $key }}" style="padding: 10px 10px">
                    <input class="rd-servico-principal-data bookable-period" type="checkbox" name="servprindatas[]" value="{{ $period['date']->format('Y-m-d') }}"
                        data-product-title="{{ $period['date']->format('d/m/Y') }}"
                        data-fatorpass="{{ $product->getCapacity() }}" data-price="{{ $price }}" id="rd-servico-principal-data-{{ $key }}" @if ($product->isOutOfStock($period['date'])) disabled @endif>
                    <label for="rd-servico-principal-data-{{ $key }}">
                        <span class="servico-principal__descricao">
                            <span class="servico-principal__nome">{{ $period['date']->format('d/m/Y') }}</span>
                            <span class="servico-principal__a-partir-valor">
                                <strong class="servico-principal__valor">{{ money($price, currency(), $product->getOffer()->currency) }}</strong>
                                {{ __('frontend.pacotes.por_pessoa') }}
                            </span>
                        </span>

                        @if ($product->isOutOfStock($period['date']))
                            <strong class="servico-principal__selo servico-principal__selo--esgotado">{{ $product->getStockLabel($period['date']) }}</strong>
                        @elseif ($product->isOneAvailable($period['date']))
                            <strong class="servico-principal__selo servico-principal__selo--ultima-unidade">{{ $product->getStockLabel($period['date']) }}</strong>
                        @else
                            <strong class="servico-principal__selo servico-principal__selo--esgotando">{{ $product->getStockLabel($period['date']) }}</strong>
                        @endif

                    </label>
                </div>
            @endforeach
        </div>

        <div class="corpo-texto">
            <h3>{{ __('frontend.pacotes.inclusoes') }}</h3>
            <ul>
                @if ($product instanceof \App\Models\HotelAccommodation)
                    @php ($accommodationStructures = $product->getStructures())
                    @php ($accommodationStructures = !empty($accommodationStructures) ? $accommodationStructures->pluck('name')->toArray() : [])
                    @php ($accommodationStructuresStrings = implode(', ', $accommodationStructures))

                    @foreach ($product->getInclusions() as $inclusion)
                        <li>{{ $inclusion->name }}</li>
                    @endforeach
            </ul>
                    @if (!empty($product->extra_inclusions))
                        {!! $product->extra_inclusions !!}
                    @endif
            <br>
                    @if (!empty($accommodationStructuresStrings))
                        <strong>{{ __('frontend.pacotes.comodidade_acomodacao') }}:</strong> {{ $accommodationStructuresStrings }}.</li>
                    @endif

            <br>
                    @if($product->hotelOffer->hotel)
                        @php ($structures = $product->hotelOffer->hotel->structures)
                        @php ($structures = !empty($structures) ? $structures->pluck('name')->toArray() : [])
                        @php ($structureStrings = implode(', ', $structures))

                        @if (!empty($structureStrings))
                            <strong>{{ __('frontend.pacotes.comodidade_hotel') }}:</strong> {{ $structureStrings }}.</li>
                        @endif
                    @endif
                @endif


            @component('frontend.template.components.gallery')
                @slot('offer', $product->getOffer())
                @slot('class', 'product-gallery')
            @endcomponent

            <h3>{{ __('frontend.pacotes.obs') }}</h3>
            @if ($product->getOfferType() == \App\Enums\OfferType::HOTEL)
                <p data-map-title="{{ $product->getHotelName() }}">
                    <strong>{{ __('frontend.pacotes.data_evento') }}: </strong> <span class="sale-date-event" data-event-date="{{ $package->getFriendlyDate() }}">{{ $package->getFriendlyDate() }}</span><br>
                    <strong>{{ __('frontend.pacotes.local_evento') }}: </strong> {{ $package->getLocation() }}<br>
                    <strong>{{ __('frontend.pacotes.hotel') }}: </strong> <span class="sale-date-event">{{ $product->getHotelName() }} </span> <BR>
                    <strong>{{ __('frontend.forms.endereco') }}: </strong> <span class="sale-date-event">{{ $product->gethoteladdress() }}</span> <BR>
                    <strong>{{ __('frontend.pacotes.check_in_time') }}: </strong>{{ $product->getFriendlyCheckin() }} <strong>|</strong> <strong>{{ __('frontend.pacotes.check_out_time') }}: </strong>{{ $product->getFriendlyCheckout() }}<br><br>

                    <ul>
                        @foreach ($product->exclusions as $exclusions)
                            <li>{{ ($exclusions->name) }};</li>
                        @endforeach
                    </ul>
                    @if (!empty($product->extra_exclusions))
                        {!! $product->extra_exclusions !!}<br />
                    @endif
                </p>

                <div class="pacote-observacoes">
                    <ul>
                        @if ($product->hotelOffer->observations && count($product->hotelOffer->observations))
                            @foreach ($product->hotelOffer->observations as $observation)
                                <li><strong>{{ ($observation->name) }}</strong></li>
                            @endforeach
                        @endif
                    </ul>
                        @if (!empty($product->hotelOffer->hotel->extra_observations))
                            {!! ($product->hotelOffer->hotel->extra_observations) !!}
                        @endif
                    </ul>
                </div>
            @endif

            <div class="pacote-descricao corpo-texto font-smaller">
                <div id="mapdfff5" style="height: 200px;"></div>
            </div>

            @include('frontend.template.scripts.gallery-slideshow', ['class' => '.product-gallery'])

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
                })();
            </script>

            <script type="text/javascript">
                var dateCheckoxes = $('.rd-servico-principal-data');
                var selectedDates = [];
                var loading = '<div class="passo__carregando a-centro"><i class="fas fa-pulse fa-spinner fa-2x"></i></div>';
                var inputTimeout = null;
                var servicosAdicionaisDetalhes = $('#servicos-adicionais');
                var formasPagamentoDetalhes = $('.passo__formas-pagamento');
                var additionalsInputs = $(document).find('.rd-servico-adicional');

                dateCheckoxes.click(function () {
                    let selections = $('.rd-servico-principal-data:checked');
                    let offerType = $(this).attr('data-type');
                    let offerId = '{{ $product->getOffer()->id }}';
                    let productId = '{{ $product->id }}';
                    let price = $(this).attr('data-price');

                    selectedDates = [];

                    for (let i = 0; i < selections.length; i++) {
                        selectedDates.push({
                            'date': $(selections[i]).val(),
                            'price': price,
                            'productId': productId,
                        });
                    }

                    getAdditionals(productId, selectedDates);
                    bookableOfferHandler();

                    carregaFormasPagamento(productId, selectedDates);
                });

                function getAdditionals(productId, selectedDates) {
                    servicosAdicionaisDetalhes.html(loading);

                    $.post(baseurl + 'pacotes/getservadicajax', {
                        pacoate: '{{ $package->id }}',
                        grupo: '{{ $product->getOfferType() }}',
                        product: productId,
                        servprin: '{{ $product->getOffer()->id }}',
                        servadic: getSelectedAdditionals(),
                        selectedDates: selectedDates,
                        numpass: $('#numpass').val(),
                    }, function (data) {
                        $('.pacote__servicos-adicionais').show();

                        if (data) {
                            servicosAdicionaisDetalhes.html(data);
                        } else {
                            servicosAdicionaisDetalhes.html("{{ __('frontend.misc.nenhum_adicional_encontrado') }}");
                        }
                    });
                }

                function carregaFormasPagamento(productId, selectedDates) {
                    $.post('{{ route( $routePaymentDetails ) }}', {
                        pacote: '{{ $package->id }}',
                        grupo: '{{ $product->getOfferType() }}',
                        product: productId,
                        servprin: '{{ $product->getOffer()->id }}',
                        servadic: getSelectedAdditionals(),
                        selectedDates: selectedDates,
                        numpass: $('#numpass').val(),
                    }, function (response) {
                        $('.pacote__formas-pagamento-cupom').show();

                        if (response) {
                            formasPagamentoDetalhes.html(response);

                            // copia conteúdo para resumo fixo
                            $('#resumo__valor-passageiro').html($('#valor-passageiro').html());
                            $('#resumo__valor-passageiro').addClass('mt');
                        } else {
                            formasPagamentoDetalhes.html('<p>Nenhuma forma de pagamento encontrada.</p>');
                        }
                    });
                }

                function getSelectedAdditionals() {
                    let additionals = [];

                    $(document).find('.rd-servico-adicional').each(function () {
                        if ($(this).prop('checked') == true) {
                            additionals.push($(this).val());
                        }
                    });

                    return additionals;
                }
            </script>
        </div>
    </div>
@endif
