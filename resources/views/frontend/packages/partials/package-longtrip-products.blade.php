
<div class="mensagem--erro-fatorpass" data-original="{{ __('frontend.reservas.escolha_pacote_fator_pass') }} " style="display: none;"></div>

@php
$longtripAccommodationsPricings = $longtripRoute->longtripAccommodationsPricings()->get()->groupBy("longtrip_accommodation_type_id");
@endphp

@if (count($longtripAccommodationsPricings) > 0)

    @foreach ($longtripAccommodationsPricings as $longtripAccommodationsPricingType)
        @foreach ($longtripAccommodationsPricingType as $longtripAccommodationsPricing)

            @php
            $key = uniqid();
            $longtripAccomodationType       = $longtripAccommodationsPricing->type;
            $price  =   $longtripAccommodationsPricing->getPrice() + $longtripBoardingLocation->getPrice();
            @endphp
                <div class="form__checkbox form__checkbox--radio servico-principal servico-principal-secundario servico-principal__grupo-{{ $offer->type }}   servico--{{ $longtripAccommodationsPricing->getStockStatusClass() }}" id="servico-principal-sec-{{ $key }}">
                    <input class="rd-servico-secundario rd-servico-principal-produto longtrip" type="radio"
                        name="adicionais-lp[{{ $longtripRoute->id }}][{{ $longtripBoardingLocation->id }}]"
                        data-product="{{ $longtripBoardingLocation->id }}"
                        data-fatorpass="{{ $longtripAccommodationsPricing->getCapacity() }}"
                        value="{{ $longtripAccommodationsPricing->id }}"
                        id="rd-servico-principal-produto-{{ $key }}"
                        postUrl="{{ route(getRouteByLanguage('frontend.ajax.longtrip-accommodation-details')) }}"
                        grupo="{{ $offer->type }}"
                        packageId="{{ $package->id }}"
                        longtripRoute="{{ $longtripRoute->id }}"
                        longtripAccomodationType="{{ $longtripAccomodationType->id }}"
                        longtripBoardingLocation="{{ $longtripBoardingLocation->id }}"
                        @if ($longtripAccommodationsPricing->isOutOfStock()) disabled @endif
                        />
                    <label for="rd-servico-principal-produto-{{ $key }}">
                        <span class="servico-principal__descricao">
                            <span class="servico-principal__nome">{{ __('frontend.pacotes.acomodation') }}{{ ($longtripAccommodationsPricingType[0]->type->name) }}</span>
                            <span class="servico-principal__a-partir-valor">
                                <strong class="servico-principal__valor">{{ money($price, currency(), $offer->currency) }}</strong>
                                {{ __('frontend.pacotes.por_pessoa') }}
                            </span>
                        </span>
                        @if ($longtripAccommodationsPricing->isOutOfStock())
                            <strong class="servico-principal__selo servico-principal__selo--esgotado">{{ $longtripAccommodationsPricing->getStockLabel() }}</strong>
                        @elseif ($longtripAccommodationsPricing->isOneAvailable())
                            <strong class="servico-principal__selo servico-principal__selo--ultima-unidade">{{ $longtripAccommodationsPricing->getStockLabel() }}</strong>
                        @else
                        @endif
                    </label>
                </div>
        @endforeach
    @endforeach
@endif
