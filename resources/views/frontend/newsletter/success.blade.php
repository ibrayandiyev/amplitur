@extends('frontend.template.default')

@section('content')
    <div class="busca busca--pacotes">
        <div class="largura-site ma">
            <form action="{{ route('frontend.packages.search') }}" method="get" accept-charset="utf-8">
                <div class="busca__grid">
                    <label for="fbusca" class="esconde-vis">{{ __('frontend.geral.buscar') }} </label>
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
        <div class="largura-site2 ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.forms.newsletter') }} </h1>
            </header>
            <div class="corpo-texto">
                <h2>{{ __('frontend.newsletter.success-title') }}</h2>
                <p>{{ __('frontend.newsletter.thanks') }}</p>
            </div>
        </div>
    </main>
@endsection
