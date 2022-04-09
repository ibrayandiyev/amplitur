@extends('frontend.template.page')

@section('structure-data')
@include('frontend.template.scripts.structure-data-offers')
@endsection

@section('title'){{__('frontend.seo.travel')}} {{ $package->getTitle() }} - {{ $package->getdatestringSEO() }} - {{ $package->getCity() }}, {{ $package->getCountry() }} - Amp Travels @endsection
@section('description'){{ $package->meta_description }}@endsection
@section('width'){{712}}@endsection
@section('height'){{360}}@endsection
@section('image_url'){{ $package->getThumbnail2xUrl() }}@endsection
@section('url'){{ $package->getUrl() }}@endsection

@section('content')

    @php
    function buildRouteArray($routes, &$_data){
        foreach($routes as $route){
            $fields         = $route->fields;
            $string_dates   = "";
            $i=0;
            if(is_array($fields['sale_dates'])){
                foreach($fields['sale_dates'] as $key => $f){
                    $date = \Carbon\Carbon::createFromFormat("Y-m-d", $f);
                    $string_dates .= $date->format("d/m/Y");
                    if($i < (count($fields['sale_dates'])-1)){
                        $string_dates .= ", ";
                    }
                    $i++;
                }
            }else{
                continue;
            }
            $index_date = $string_dates;
            $_data[$index_date][] = $route->id;
        }
        ksort($_data);
        return $_data;
    }
    @endphp


    <main class="conteudo grupo">
		<div class="largura-site ma">
            <div class="grid">
                <div class="grid-xs--12 grid-md--9">
                    @include('frontend.template.scripts.structure-data-offers')
                    <header class="pacote__header">
						<time class="pacote__data">{{ $package->getDateString() }}</time>
                        <h1 class="pacote__titulo">
                            <span class="pacote__nome">{{ $package->getTitle() }}</span>
                            <span class="pacote__subnome">{{ $package->getLocation() }} – {{ $package->getCity() }}, {{ $package->getCountry() }}</span>
                        </h1>
                        @include('frontend.template.components.sharing')
                    </header>

                    <div class="pacote__form passos form mb2">
                        <form action="{{ route('frontend.packages.book', [$package->id, $package->getSlug()]) }}" method="post" accept-charset="utf-8" autocomplete="off">
                            @csrf
                            <input type="hidden" name="pid" value="{{ $package->id }}" />
                            <input type="hidden" name="servprinproduto" value="" />
                            <input type="hidden" name="tipo" value="compra" />
                            <input type="hidden" name="currency" value="{{ currency()->code }}" />

                            @include('frontend.template.components.no-script')

                            @component('frontend.template.components.package-popups')
                                @slot('package', $package)
                            @endcomponent
                            @php
                                $_data_routes = null;
                            @endphp
                            <div class="passo pacote__passageiros-servico">
                                <header class="passo__header">
                                    <span class="passo__num"></span>
                                    <h2 class="passo__titulo">{{ __('frontend.reservas.tipo_servico') }}</h2>
                                </header>
                                <div class="passo__conteudo">
                                    <input type="hidden" id="numpass" name="numpass" value="1" />

                                    <div class="passo__passageiros flex-grid flex-grid--v-center mb">
                                        <label class="passo__label--alt">{{ __('frontend.forms.num_pass') }}</label>
                                        <div data-numpass-min="1" data-numpass-max="5" data-numpass-sessao="1" class="passo__passageiros-seletor passo__campo--alt">
                                            <span class="passo__passageiros-menos passo__passageiros-incremento">
                                                <i class="fas fa-minus-circle"></i>
                                            </span>
                                            <span class="passo__passageiros-numero">1</span>
                                            <span class="passo__passageiros-mais passo__passageiros-incremento">
                                                <i class="fas fa-plus-circle"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="passo__grupo-servico flex-grid flex-grid--v-center">
                                        <label class="passo__label--alt">{{ __('frontend.reservas.servico') }}</label>
                                        <div class="passo__campo--alt">
                                            @if ($package->hasBustripOffer())
                                            @php
                                                /* para filtrar por datas, hoje teremos que montar todo o array em cima do campo "fields."
                                                    No futuro teremos que mudar isso.
                                                */
                                                foreach($package->getBustripOffers() as $bustripOffers){
                                                    buildRouteArray($bustripOffers->bustripRoutes, $_data_routes['bus-trip']);
                                                }
                                            @endphp
                                            <div class="passo__grupo-servico-botao grupo-servico">
                                                <input data-grupo="{{ App\Enums\OfferType::BUSTRIP }}" type="radio" name="gruposervprin" id="grupo-servico-principal-1" alue="BATE E VOLTA" class="grupo-servico__input" />
                                                <label data-grupo="{{ App\Enums\OfferType::BUSTRIP }}" for="grupo-servico-principal-1" class="grupo-servico__label">{{ __('frontend.pacotes.bate_volta') }}</label>
                                            </div>
                                            @endif
                                            @if ($package->hasHotelOffer())
                                            <div class="passo__grupo-servico-botao grupo-servico">
                                                <input data-grupo="{{ App\Enums\OfferType::HOTEL }}" type="radio" name="gruposervprin" id="grupo-servico-principal-2" value="HOSPEDAGEM" class="grupo-servico__input" />
                                                <label data-grupo="{{ App\Enums\OfferType::HOTEL }}" for="grupo-servico-principal-2" class="grupo-servico__label">{{ __('frontend.pacotes.hospedagem') }}</label>
                                            </div>
                                            @endif
                                            @if ($package->hasShuttleOffer())
                                            @php
                                                /* para filtrar por datas, hoje teremos que montar todo o array em cima do campo "fields."
                                                    No futuro teremos que mudar isso.
                                                */
                                                foreach($package->getShuttleOffers() as $shuttleOffers){
                                                    buildRouteArray($shuttleOffers->shuttleRoutes, $_data_routes['shuttle']);
                                                }
                                            @endphp
                                            <div class="passo__grupo-servico-botao grupo-servico">
                                                <input data-grupo="{{ App\Enums\OfferType::SHUTTLE }}" type="radio" name="gruposervprin" id="grupo-servico-principal-3" value="SHUTTLE" class="grupo-servico__input" />
                                                <label data-grupo="{{ App\Enums\OfferType::SHUTTLE }}" for="grupo-servico-principal-3" class="grupo-servico__label">{{ __('frontend.pacotes.shuttle') }}</label>
                                            </div>
                                            @endif
                                            @if ($package->hasLongtripOffer())
                                            @php
                                                /* para filtrar por datas, hoje teremos que montar todo o array em cima do campo "fields."
                                                    No futuro teremos que mudar isso.
                                                */
                                                foreach($package->getLongtripOffers() as $longtripOffers){
                                                    buildRouteArray($longtripOffers->longtripRoutes, $_data_routes['longtrip']);
                                                }
                                            @endphp
                                            <div class="passo__grupo-servico-botao grupo-servico">
                                                <input data-grupo="{{ App\Enums\OfferType::LONGTRIP }}" type="radio" name="gruposervprin" id="grupo-servico-principal-4" value="LONGTRIP" class="grupo-servico__input" />
                                                <label data-grupo="{{ App\Enums\OfferType::LONGTRIP }}" for="grupo-servico-principal-4" class="grupo-servico__label">{{ __('frontend.pacotes.longtrip') }}</label>
                                            </div>
                                            @endif
                                            @if(!$package->hasBustripOffer() && !$package->hasHotelOffer() && !$package->hasShuttleOffer() && !$package->hasLongtripOffer())
                                            <label>{{ __('frontend.geral.em_breve')}}</label>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($_data_routes != null)
                                        <div class="passo__grupo-datas flex-grid flex-grid--v-center">
                                            <label class="passo__label--alt">{{ __('frontend.reservas.dates') }}</label>

                                                @foreach($_data_routes as $keyGroup => $drGroup)
                                                    @if(is_array($drGroup))
                                                        @foreach($drGroup as $key => $dr)
                                                            <div class="passo__grupo-servico-botao grupo-datas" data-keyGroup="{{ $keyGroup }}" data-grupo="{{ implode($dr) }}">
                                                                <input data-grupo="{{ implode($dr) }}" type="radio" name="gruposervprinrota" id="grupo-datas-{{$keyGroup}}{{ implode($dr) }}" value="{{ implode($dr) }}" class="grupo-datas__input" />
                                                                <label data-grupo="{{ implode($dr) }}" for="grupo-datas-{{$keyGroup}}{{ implode($dr) }}" class="grupo-datas__label">{{$key}}</label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach

                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div class="passo pacote__servico-principal">
                                <header class="passo__header">
                                    <span class="passo__num"></span>
                                    <h2 class="passo__titulo">{{ __('frontend.reservas.escolha_pacote') }}</h2>
                                </header>

                                <div class="passo__conteudo">
                                    <div class="passo__servicos-principais">
                                        @if ($package->hasHotelOffer())
                                            @component('frontend.template.components.package.hotel-service-list')
                                                @slot('package', $package)
                                            @endcomponent
                                        @endif
                                        @if ($package->hasBustripOffer())
                                            @component('frontend.template.components.package.bustrip-service-list')
                                                @slot('package', $package)
                                                @slot('_data_routes', $_data_routes['bus-trip'])
                                            @endcomponent
                                        @endif
                                        @if ($package->hasShuttleOffer())
                                            @component('frontend.template.components.package.shuttle-service-list')
                                                @slot('package', $package)
                                                @slot('_data_routes', $_data_routes['shuttle'])
                                            @endcomponent
                                        @endif
                                        @if ($package->hasLongtripOffer())
                                            @component('frontend.template.components.package.longtrip-service-list')
                                                @slot('package', $package)
                                                @slot('_data_routes', $_data_routes['longtrip'])
                                            @endcomponent
                                        @endif
                                        <div class="passo__servicos-principais-abrir">
                                            <span class="passo__servicos-principais-abrir-label"> {{ __('frontend.reservas.ver_servicos') }}</span>
                                            <span class="passo__servicos-principais-abrir-icone"> <i class="fas fa-angle-down"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="servico-principal__detalhes" id="servico-principal__detalhes"></div>
                                </div>
                            </div>

                            <div class="passo pacote__servicos-adicionais">
                                <header class="passo__header grupo">
                                    <span class="passo__num"></span>
                                    <h2 class="passo__titulo">{{ __('frontend.reservas.escolha_adicionais') }}</h2>
                                </header>

                                <div class="passo__conteudo">
                                    <div id="servicos-adicionais" class="passo__servicos-adicionais"></div>
                                </div>
                            </div>

                            <div class="passo pacote__formas-pagamento-cupom">
                                <header class="passo__header grupo">
                                    <span class="passo__num"></span>
                                    <h2 class="passo__titulo">{{ __('frontend.reservas.formapag_cupom') }}</h2>
                                </header>
                                <div class="passo__conteudo">

                                    <div id="passo__formas-pagamento" class="passo__formas-pagamento"></div>

                                    <div class="passo__promocode promocode">
                                        <div class="promocode__grid">
                                            <header class="promocode__header">
                                                <h2 class="promocode__titulo">{{ __('frontend.reservas.promocode') }}</h2>
                                                <label class="promocode__label">{{ __('frontend.reservas.promocode_digite') }}</label>
                                            </header>
                                            <div class="promocode__campo">
                                                <input type="text" name="promocode" class="promocode__input input-limpa" id="ip-promocode" value="" placeholder="código promocional" />
                                                <button class="promocode__botao">{{ __('frontend.reservas.aplicar') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="a-centro">
                                        <button type="submit" name="submit" class="botao botao--comprar">
                                            <i class="fas fa-shopping-cart"></i>
                                            {{ __('frontend.pacotes.comprar') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="pacote__descricao-galeria-mapa1">
                        <h2 class="pacote__subtitulo">{{ __('frontend.pacotes.mais_info') }}</h2>

                        <div class="pacote__descricao">
                            <div class="corpo-texto">
                                {!! $package->getDescription() !!}
                            </div>
                        </div>

                        <div class="pacote__galeria">
                            @component('frontend.template.components.gallery')
                                @slot('package', $package)
                            @endcomponent
                        </div>

                        <div class="pacote__mapa mb">
                            <h2 class="pacote__subtitulo">{{ __('frontend.pacotes.local_evento') }}</h2>
                            <input type="hidden" name="address[latitude]" value="{{ old('address.latitude', (float) $package->address->latitude) }}" />
                            <input type="hidden" name="address[longitude]" value="{{ old('address.longitude', (float) $package->address->longitude) }}" />
                            <div class="col-md-12 map-container" id="map" style="height: 300px;"></div>
                        </div>
                    </div>

                    <div class="pacote__observacoes-roteiro" style="">
						<div class="pacote__observacoes">
                            <h2 class="pacote__subtitulo">{{ __('frontend.pacotes.obs') }}</h2>
                            <div class="corpo-texto">
                                {!! $package->getDescription() !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-xs--12 grid-md--3">
                    <div id="resumo-ph"></div>
                    <div id="resumo" class="pacote__resumo resumo">
                        <div class="resumo__foto">
                            <img src="{{ $package->getThumbnailUrl() }}" alt="{{ $package->getTitle() }}">
                        </div>

                        <div class="resumo__conteudo">
                            <header class="resumo__header">
                                <time class="resumo__data">{{ $package->getDateString() }}</time>
                                <h2 class="resumo__titulo">
                                    <span class="resumo__nome">{{ $package->getTitle() }}</span>
                                    <span class="resumo__subnome">{{ $package->getLocation() }}</span>
                                </h2>
                            </header>

                            <div id="resumo__valor-passageiro" class="valor-passageiro"></div>

                            @include('frontend.template.components.sharing')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
@include('frontend.template.scripts.mapable')
@include('frontend.template.scripts.gallery-slideshow')
@endpush
