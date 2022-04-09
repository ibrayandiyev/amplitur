@if($package)
<div class="pacotes-lista__grid">
    <div class="pacotes-lista__foto">
        <a class="pacotes-lista__link" href="{{ $package->getUrl() }}">
            <img class="pacotes-lista__img" data-src="{{ $package->getThumbnailUrl() }}" src="{{ $package->getThumbnailUrl() }}" alt="{{ $package->getThumbnailAlt() }}" />
            <time class="pacotes-lista__data" datetime="{{ $package->getStartDate() }}" content="{{ $package->getStartDate() }}">{{ $package->getDateString() }}</time>
            <time datetime="{{ $package->getEndDate() }}" content="{{ $package->getEndDate() }}"></time>
            <header class="pacotes-lista__header">
                <h2 content="{{ $package->getTitle() }}" class="pacotes-lista__titulo">{{ $package->getTitle() }}</h2>
                <span class="pacotes-lista__subnome">
                    <span>{{ $package->getLocation() }}</span>
                    <span>
                        –
                        <span>{{ $package->getCity() }}</span>,
                        <span>{{ $package->getCountry() }}</span>
                    </span>
                </span>
            </header>
        </a>
    </div>

    <div class="pacotes-lista__desc">
        <div class="pacotes-lista__comprar flex-grid">
            @php
            $lowerPrice         = $package->getLowerPrice();
            $lowerPriceCurrency = money($lowerPrice, currency(), $package->getLowerPriceCurrency());
            @endphp
            <div class="pacotes-lista__valores valores">
            @if($lowerPrice != null && $lowerPrice >0)

                <span class="pacotes-lista__a-partir">{{ __('frontend.pacotes.a_partir') }} </span>
                <div class="pacotes-lista__valores-lista">
                    <strong class="valores__container">
                        <span class="valores__valor valores__valor--real">{{ $lowerPriceCurrency }} </span>
                    </strong>
                </div>
            @else
                <span class="pacotes-lista__a-partir">{{ __('frontend.geral.em_breve')}}</span>
            @endif
            </div>
            <a href="{{ $package->getUrl() }}" class="pacotes-lista__botao botao botao--comprar--alt">{{ __('frontend.pacotes.comprar') }} </a>
        </div>
    </div>
</div>
@endif
