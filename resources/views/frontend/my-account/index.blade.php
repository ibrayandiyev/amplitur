@extends('frontend.template.page')

@section('content')
    <main class="conteudo grupo">
		<div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.conta.minha_conta') }} </h1>
            </header>

            <div class="minha-conta grid">
                <div class="minha-conta__coluna-menu grid-xs--12 grid-md--3">
                    @include('frontend.my-account.partials.navigation')
                </div>

                <div class="minha-conta__coluna-conteudo grid-xs--12 grid-md--9">
                    <div class="minha-conta__pagina">
                        <div class="corpo-texto">
                            <p>
                                @if($client->type == 'fisical')
                                {{ __('frontend.conta.ola') }}, {{ mb_strtoupper($client->name) }}! <br/>
                                @endif
                                @if($client->type == 'legal')
                                {{ __('frontend.conta.ola') }}, {{ mb_strtoupper($client->legal_name) }}! <br/>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
