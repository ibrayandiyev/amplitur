@extends('frontend.template.page')

@section('title'){{__('frontend.seo.recuperar_conta_titulo')}}@endsection
@section('description'){{__('frontend.seo.recuperar_conta_metadesc')}}@endsection
@section('url'){{ route(getroutebylanguage('frontend.auth.recovery'))}} @endsection

@section('content')
    <main class="conteudo grupo">
        <div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.conta.recuperar_conta') }}</h1>
            </header>

            <div class="flex-grid flex-grid--gutters">
                <div class="box mb grid-cell-xs--12 grid-cell-md--6">
                    <header class="box__header">
                        <h2 class="box__titulo">{{ __('frontend.conta.esqueceu_senha') }}</h2>
                    </header>

                    <div class="box__conteudo form">
                        <div class="corpo-texto fz-s">
                            <p>{{ __('frontend.conta.digite_email') }}</p>
                        </div>
                        <form action="{{ route('frontend.auth.doRecoveryPassword') }}" method="post">
                            @csrf
                            <div class="form__campo">
                                <label class="form__label" for="login">{{ __('frontend.forms.email') }}</label>
                                <input class="form__input" type="text" id="login" name="email" required>
                            </div>
                            <button class="botao botao--submit mb" name="submit">{{ __('frontend.forms.enviar') }}</button>
                        </form>
                    </div>
                </div>
                <div class="box mb grid-cell-xs--12 grid-cell-md--6">
                    <header class="box__header">
                        <h2 class="box__titulo">{{ __('frontend.conta.esqueceu_login') }}</h2>
                    </header>

                    <div class="box__conteudo form">
                        <div class="corpo-texto fz-s">
                            <p>{{ __('frontend.conta.digite_email_documento') }}</p>
                        </div>
                        <form action="{{ route('frontend.auth.doRecoveryUsername') }}" method="post">
                            @csrf
                            <div class="form__campo">
                                <label class="form__label" for="login">{{ __('frontend.forms.email') }}</label>
                                <input class="form__input" type="text" id="login" name="email" required>
                            </div>
                            <button class="botao botao--submit mb" name="submit">{{ __('frontend.forms.enviar') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
