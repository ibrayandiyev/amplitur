@if (!empty($event))
<div class="pacotes-lista__grid">
    <div class="pacotes-lista__foto">
        <a class="pacotes-lista__link" href="{{ $event->getPrebookingUrl() }}">
            <img class="pacotes-lista__img" data-src="{{ $event->getThumbnailUrl() }}" src="{{ $event->getThumbnailUrl() }}" alt="{{ $event->getThumbnailAlt() }}" />
            <header class="pacotes-lista__header">
            <h2 class="pacotes-lista__titulo">{{ $event->getTitle() }}</h2>
                <span class="pacotes-lista__subnome">
                    <span>{{__("frontend.reservas.a_confirmar")}}</span>
                    <span>
                        <span>{{ ' - ' . $event->getCity() }}</span>
                        <span>{{ ' - ' . $event->getCountry() }}</span>
                    </span>
                </span>
            </header>
        </a>
    </div>
    <div class="pacotes-lista__desc">
        <a href="{{ $event->getPrebookingUrl() }}" class="pacotes-lista__botao botao botao--pre-reservar--alt">{{ __('frontend.pacotes.pre_reserva') }} </a>
    </div>
</div>
@endif
