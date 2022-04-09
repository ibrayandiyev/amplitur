@php
    $offers = $package->getLongtripOffers();
    $products = $package->getOffersProducts();

    $products = $products->filter(function ($product) {
        return $product['type'] == \App\Enums\OfferType::LONGTRIP;
    });

    $products = $products->toArray();

    array_multisort(array_column($products, 'title'), SORT_ASC, SORT_NATURAL|SORT_FLAG_CASE, $products);

@endphp

<input type="hidden" name="servprinproduto2" value="" />
@if(is_array($_data_routes))
    @foreach($_data_routes as $keyRoute => $dr)
        @php
            $keyRoute = implode($_data_routes[$keyRoute]);
        @endphp


        @foreach($dr as $route)
            <div class="servico-principal-data grupo-longtrip-{{$keyRoute}}">
                @php
                    $longtripRoute      = app(\App\Models\LongtripRoute::class);
                    $longtripRoute      = $longtripRoute->where("id", $route)->first();
                    $offer              = $longtripRoute->offer;
                    $longtripAccommodationsPricings = $longtripRoute->longtripAccommodationsPricings()->get()->groupBy("longtrip_accommodation_type_id");
                    $longtripBoardingLocations = $longtripRoute->longtripBoardingLocations();
                    $providerName       = (isset($offer->company->company_name)?ucwords($offer->company->company_name):null);
                @endphp

                @if($longtripBoardingLocations)
                    @foreach($longtripBoardingLocations->get() as $boardingLocation)
                        @php
                            $key = uniqid();
                            // This is the servprinproduto
                            $address = $boardingLocation->address;
                            $boardingInfo = ( __('frontend.pacotes.start_boarding')) . " - " . city($address->city) ." - ". state($address->country, $address->state) ." - ". country($address->country);
                            if($boardingLocation->isAvailable()){
                                $boardingLocation->setDisableStock(); 
                            }
                        @endphp
                        <div class="form__checkbox form__checkbox--radio servico-principal servico-principal__grupo-longtrip  grupo-longtrip-{{$keyRoute}} servico--{{ $boardingLocation->getStockStatusClass() }} "  id="servico-principal-{{ $key }}">
                            <input class="rd-servico-principal rd-servico-principal-produto rd-longtrip skip-adicionais" 
                            type="radio" 
                            name="servprin" 
                            value="{{ $offer->id }}" 
                            data-product="{{ $boardingLocation->id }}" 
                            data-auxvar-1="{{ $longtripRoute->id }}"  
                            ata-product2="lap-$longtripAccommodationsPricing->id " 
                            data-cotacao="1.00" data-moeda="{{ currency() }}" 
                            data-fatorpass="1" id="rd-servico-principal-{{ $key }}" 
                            onchange="handleLongtripBoardingLocation()"
                            @if ($boardingLocation->isOutOfStock()) disabled @endif
                            />
                            <label for="rd-servico-principal-{{ $key }}">
                                <span class="servico-principal__descricao">
                                    <span class="servico-principal__nome"> @if($longtripRoute->name != null) <strong><i>{{$longtripRoute->label_name}}  </i></strong> @endif{{ ($boardingInfo) }} - {{ $boardingLocation->boardingAtLabel }}
                    {{__('frontend.reservas.as')}} {{ $boardingLocation->boardingAtTimeLabel }} @if($providerName != null) - <i>{{ __('frontend.pacotes.prov_name') }} {{$providerName}}</i> @endif</span>
                                    <span class="servico-principal__a-partir-valor">
                                        <span class="servico-principal__a-partir"></span>
                                        <!-- <strong class="servico-principal__valor">{{ money($boardingLocation->getPrice(), currency(), $offer->currency) }}</strong>
                                        {{ __('frontend.pacotes.por_pessoa') }} -->
                                    </span>
                                </span>

                                @if ($boardingLocation->isOutOfStock())
                                    <strong class="servico-principal__selo servico-principal__selo--esgotado">{{ $boardingLocation->getStockLabel() }}</strong>
                                @elseif ($boardingLocation->isOneAvailable())
                                    <strong class="servico-principal__selo servico-principal__selo--ultima-unidade">{{ $boardingLocation->getStockLabel() }}</strong>
                                @else
                                @endif
                            </label>
                        </div>
                    @endforeach
                @endif

            </div>
        @endforeach
    @endforeach
@endif
@push('scripts')
<script src="{{ asset('/frontend/js/longtrip.js') }}"></script>

@endpush
