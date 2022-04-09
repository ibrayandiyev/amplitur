@if($event)
<tr class="pacotes-tabela__tr" itemscope="" itemtype="http://schema.org/Event">
    <td class="td--data"></td>
    <td class="td--evento">
        <header class="pacotes-tabela__header">
            <a href="{{ $event->getPrebookingUrl() }}">
                <h2 content="{{ $event->getTitle() }}" class="pacotes-tabela__titulo">{{ $event->getTitle() }}</h2>
                <span class="pacotes-tabela__subnome">
                    <span>{{__("frontend.reservas.a_confirmar")}}</span>
                   <span>
                        <span>{{ ' - ' . $event->getCity() }}</span>
                        <span>{{ ' - ' . $event->getCountry() }}</span>
                    </span>
                </span>
                <span>
            </a>
        </header>
    </td>
    <td class="td--icone"></td>
    <td class="td--botao" style="height: 48px;">
        <a href="{{ $event->getPrebookingUrl() }}" class="pacotes-lista__botao botao botao--pre-reservar--alt">{{ __('frontend.pacotes.pre_reserva') }}</a>
    </td>
</tr>
@endif
