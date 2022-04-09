@if($package)
<tr class="pacotes-tabela__tr">
    <td class="td--data">
    <time class="pacotes-tabela__data" datetime="{{ $package->getStartDate() }}" content="{{ $package->getStartDate() }}">{{ $package->getDateString() }}</time>
        <time datetime="{{ $package->getStartDate() }}" content="{{ $package->getStartDate() }}"></time>
    </td>
    <td class="td--evento">
        <header class="pacotes-tabela__header">
            <a href="{{ $package->getUrl() }}">
                <h2 content="{{ $package->getTitle() }}" class="pacotes-tabela__titulo">
                    {{ $package->getTitle() }}</h2>
                <span class="pacotes-tabela__subnome">
                    <span>{{ $package->getLocation() }}</span>
                    <span>
                        –
                        <span>{{ $package->getCity() }}</span>,
                        <span>{{ $package->getCountry() }}</span>
                    </span>
                </span>
            </a>
        </header>
    </td>
    <td class="td--icone"></td>
    <td class="td--botao" style="height: 74px;">
        <a href="{{ $package->getUrl() }}" class="pacotes-lista__botao botao botao--comprar--alt">{{ __('frontend.pacotes.comprar') }}</a>
    </td>
</tr>
@endif
