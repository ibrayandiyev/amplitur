@extends('frontend.template.page')

@section('content')
    <main class="conteudo grupo">
        <div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.conta.entre_conta') }}</h1>
            </header>
            <div class="mensagem mensagem--info">{{ __('frontend.reservas.mensagem_login') }}</div>

            <div class="flex-grid flex-grid--gutters">
                <div class="box mb grid-cell-xs--12 grid-cell-md--6">
                    <header class="box__header">
                        <h2 class="box__titulo">{{ __('frontend.conta.ja_cadastrado') }}</h2>
                    </header>

                    <div class="box__conteudo form form--login">
                        <form action="{{ route('frontend.auth.doLogin') }}" method="post">
                            @csrf
                            <div class="form__campo">
                                <label class="form__label" for="login">{{ __('frontend.forms.login') }}</label>
                                <input class="form__input" type="text" id="login" name="username" required>
                            </div>
                            <div class="form__campo">
                                <label class="form__label" for="senha">{{ __('frontend.forms.senha') }}</label>
                                <input class="form__input" type="password" id="senha" name="password" required>
                            </div>
                            <button class="botao botao--submit mb" name="submit">{{ __('frontend.forms.entrar') }}</button>
                        </form>
                        <div class="corpo-texto">
                            <p><a href="{{ route('frontend.auth.recovery') }}">{{ __('frontend.conta.nao_consegue_acessar') }}</a></p>
                        </div>
                    </div>
                </div>
                <div class="box mb grid-cell-xs--12 grid-cell-md--6">
                    <header class="box__header">
                        <h2 class="box__titulo">{{ __('frontend.conta.ainda_nao_cadastrado') }}</h2>
                    </header>
                    <div class="box__conteudo">
                        <p class="a-centro">
                            <a href="{{ route('frontend.auth.register') }}" class="botao botao--submit">{{ __('frontend.conta.cadastre_se_agora') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
