@php
    $products = $package->getOffersProducts();

    $products = $products->filter(function ($product) {
        return $product['type'] == \App\Enums\OfferType::SHUTTLE;
    });
@endphp

@if(is_array($_data_routes))

    @foreach($_data_routes as $keyRoute => $dr)
        @php
            $_array_check   = $_data_routes[$keyRoute];
            $keyRoute       = implode($_array_check);
            $productsSt     = $products->filter(function($product) use ($dr){
                return in_array($product['shuttle_route_id'], $dr);
            });
            $productsSt     = $productsSt->toArray();

            array_multisort(array_column($productsSt, 'title'), SORT_ASC, SORT_NATURAL|SORT_FLAG_CASE, $productsSt);
        @endphp

        @foreach ($productsSt as $product)
            <div class="servico-principal-data grupo-shuttle-{{$keyRoute}}">

                @php
                    $key = uniqid();
                    $providerName       = (isset($product['offer']->company->company_name)?ucwords($product['offer']->company->company_name):null);
                @endphp
                @php
                ($boardingLocation = \App\Models\ShuttleBoardingLocation::find($product['id']));
                if($boardingLocation->isAvailable()){
                    $boardingLocation->setDisableStock(); 
                }
                @endphp
                <div class="form__checkbox form__checkbox--radio servico-principal servico-principal__grupo-{{ $product['type'] }} servico-principal__fatorpass-{{ $product['capacity'] }} servico--{{ $boardingLocation->getStockStatusClass() }}" id="servico-principal-{{ $key }}">
                    <input class="rd-servico-principal rd-servico-principal-produto" type="radio" name="servprin" value="{{ $product['offer_id'] }}" data-product="{{ $product['id'] }}" data-valor="{{ moneyFloat($product['price'], currency(), $product['offer']->currency) }}"
                    data-cotacao="1.00" data-moeda="{{ currency()->code }}" data-fatorpass="{{ $product['capacity'] }}"
                    id="rd-servico-principal-{{ $key }}"
                    @if ($boardingLocation->isOutOfStock()) disabled @endif />
                    <label for="rd-servico-principal-{{ $key }}">
                        <span class="servico-principal__descricao">
                            <span class="servico-principal__nome">{{ ($product['title']) }} @if($providerName != null) - <i>{{ __('frontend.pacotes.prov_name') }} {{$providerName}}</i> @endif</span>
                            <span class="servico-principal__a-partir-valor">
                                <span class="servico-principal__a-partir">{{ __('frontend.pacotes.a_partir') }}</span>
                                <strong class="servico-principal__valor">{{ money($product['price'], currency(), $product['offer']->currency)}}</strong>
                                {{ __('frontend.pacotes.por_pessoa') }}
                            </span>
                        </span>
                        @if ($boardingLocation->isOutOfStock())
                            <strong class="servico-principal__selo servico-principal__selo--esgotado">{{ $boardingLocation->getStockLabel() }}</strong>
                        @elseif ($boardingLocation->isOneAvailable())
                            <strong class="servico-principal__selo servico-principal__selo--ultima-unidade">{{ $boardingLocation->getStockLabel() }}</strong>
                        @else
                            <strong class="servico-principal__selo servico-principal__selo--esgotando">{{ $boardingLocation->getStockLabel() }}</strong>
                        @endif
                    </label>
                </div>
            </div>
        @endforeach
    @endforeach
@endif
