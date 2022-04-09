@extends('frontend.template.page')

@section('content')
    <main class="conteudo grupo">
        <div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.conta.recuperar_conta') }}</h1>
            </header>

            <div class="flex-grid flex-grid--gutters">
                <div class="box mb grid-cell-xs--12 grid-cell-md--6">
                    <header class="box__header">
                        <h2 class="box__titulo">{{ __('frontend.forms.cadastrar_nova_senha') }}</h2>
                    </header>

                    <div class="box__conteudo form">
                        <div class="corpo-texto fz-s">
                            <p></p>
                        </div>
                        <form action="{{route(getRouteByLanguage('frontend.auth.do-recover-password'))}}" method="post">
                            @csrf
                            <input class="form__input" type="hidden" id="token" name="token" value="{{ $token }}">
                            <input class="form__input" type="hidden" id="verification_token" name="verification_token" value="{{ $client->verification_token }}">
                            <div class="form__campo">
                                <label class="form__label" for="password">{{ __('frontend.forms.nova_senha') }}</label>
                                <input class="form__input" type="password" id="password" name="password" required>
                            </div>
                            <div class="form__campo">
                                <label class="form__label" for="password_confirmation">{{ __('frontend.forms.confirme_nova_senha') }}</label>
                                <input class="form__input" type="password" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button class="botao botao--submit mb" name="submit">{{ __('frontend.forms.enviar') }}</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
