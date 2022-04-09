@php
    $offers = $package->getHotelOffers();

    $offers = $offers->sortBy(function ($offer) {
        if(!$offer->hotelOffer->hotel){
            return null;
        }
        return $offer->hotelOffer->hotel->name;
    });
@endphp

@foreach ($offers as $offer)
    @if(!$offer->hotelOffer->hotel)
        @continue
    @endif
    @php
        $key = uniqid();
        $providerName       = (isset($offer->company->company_name)?ucwords($offer->company->company_name):null);
    @endphp
    <div class="form__checkbox form__checkbox--radio servico-principal servico-principal__grupo-{{ $offer->type }} servico-principal__fatorpass-1 servico--disponivel" id="servico-principal-{{ $key }}">
        <input class="rd-servico-principal skip-adicionais" type="radio" name="servprin" data-fatorpass="1" value="{{ $offer->id }}" id="rd-servico-principal-{{ $key }}" />
        <label for="rd-servico-principal-{{ $key }}">
            <span class="servico-principal__descricao">
                <span class="servico-principal__nome">{{ ($offer->hotelOffer->hotel->name) }} @if($providerName != null) - <i>{{ __('frontend.pacotes.prov_name') }} {{$providerName}}</i> @endif</span>
                <span class="servico-principal__a-partir-valor">
                    <span class="servico-principal__a-partir">{{ __('frontend.pacotes.a_partir') }}</span>
                    <strong class="servico-principal__valor">{{ $offer->getLowestPriceCurrency() }} {{ decimal($offer->getLowestPrice()) }}</strong>
                    {{ __('frontend.pacotes.por_pessoa') }}
                </span>
            </span>
        </label>
    </div>
@endforeach
@push('scripts')
<script src="{{ asset('/frontend/js/hotel.js') }}"></script>

@endpush
