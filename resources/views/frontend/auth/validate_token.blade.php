@extends('frontend.template.page')

@section('content')
<main class="conteudo grupo">
    <div class="largura-site ma">
        <div class="form form--cadastro form--auto-style">
            <div class="box mb">
                <header class="box__header">
                    <h2 class="box__titulo">{{ __('frontend.conta.validacao_cadastro') }}</h2>
                </header>
                <div class="box__conteudo">
                    <fieldset class="grid">
                        <div class="campo grid-xs--12 grid-md--6">
                            @if($client == null)
                                <h4 class="card-title">{!!__('mail.client.valid_registry.not_validated')!!}</h4>
                            @else
                                <h4 class="card-title">{{__('mail.client.valid_registry.head')}}</h4>
                            @endif
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
