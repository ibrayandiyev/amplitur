@extends('frontend.template.default')

@section('title'){{ $page->name }}@endsection
@section('description') {{$page->og_description}}@endsection
@section('keywords') {{$page->og_keywords}}@endsection
@section('url'){{ route(getRouteByLanguage('frontend.pages.show'), ['slug' => $slug]) }}@endsection

@section('content')
    <div class="busca busca--pacotes">
        <div class="largura-site ma">
            <form action="{{ route('frontend.packages.search') }}" method="get" accept-charset="utf-8">
                <div class="busca__grid">
                    <label for="fbusca" class="esconde-vis">{{ __('frontend.geral.buscar') }}</label>
                    <input class="busca__campo" type="search" placeholder="{{ __('frontend.geral.busca_placeholder') }}" id="fbusca" name="q" value="" required>
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
            <div class="largura-site2 ma">
	            <h1 class="pacotes-categoria__titulo mt">{{ $page->getTranslation('title', language()) }}</h1>
                <div class="corpo-texto">
	                {!! $page->getTranslation('content', language()) !!}
                </div>
            </div>
        </div>
    </main>
@endsection
