@extends('frontend.template.default')

@section('content')

    <div class="busca busca--pacotes">
        <div class="largura-site ma">
            <form action="{{ route('frontend.packages.search') }}" method="get" accept-charset="utf-8">
                <div class="busca__grid">
                    <label for="fbusca" class="esconde-vis">{{ __('frontend.geral.buscar') }}</label>
                    <input class="busca__campo" type="text" placeholder="{{ __('frontend.geral.busca_placeholder') }} " id="fbusca" name="q" value="" required>
                    <button class="busca__botao" type="submit">
                        <i class="fas fa-search"></i>
                        <span class="esconde-vis">{{ __('frontend.geral.buscar') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <main class="conteudo grupo">
        <div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.geral.busca_por') }} “{{ $query }}”</h1>
            </header>

            @if (count($packages) > 0 || count($events) > 0)
                <ul class="pacotes-lista flex-grid flex-grid--gutters mb">
                    @if (count($packages) > 0)
                        @foreach ($packages as $package)
                            <li itemscope="" itemtype="http://schema.org/Event" class="pacotes-lista__item mb">
                                @component('frontend.template.components.package')
                                    @slot('package', $package)
                                @endcomponent
                            </li>
                        @endforeach
                    @endif
                    @if (count($events) > 0)
                        @foreach ($events as $event)
                            <li itemscope="" itemtype="http://schema.org/Event" class="pacotes-lista__item mb">
                                @component('frontend.template.components.prebooking')
                                    @slot('event', $event)
                                @endcomponent
                            </li>
                        @endforeach
                    @endif
                </ul>
            @else
                <p class="a-centro">{{ __('frontend.geral.busca_nenhum') }}
            @endif

            <div class="largura-site2 ma">
	            <h1 class="pacotes-categoria__titulo mt">{{ __('frontend.geral.busca_confira') }}</h1>
	            <table class="pacotes-tabela mb">
	                <tbody>
				        <tr class="pacotes-tabela__categoria">
			                <td colspan="4">{{ __('frontend.pacotes.excursao') }}</td>
		                </tr>
			            @foreach ($nextPackages as $package)
                            @component('frontend.template.components.package-minimal')
                                @slot('package', $package)
                            @endcomponent
                        @endforeach
				        <tr class="pacotes-tabela__categoria">
			                <td colspan="4">{{ __('frontend.pacotes.pre_reserva') }}</td>
		                </tr>
                        @foreach ($otherEvents as $event)
                            @component('frontend.template.components.prebooking-minimal')
                                @slot('event', $event)
                            @endcomponent
                        @endforeach
			        </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
