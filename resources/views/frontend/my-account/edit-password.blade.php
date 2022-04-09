@extends('frontend.template.page')

@section('content')
    <main class="conteudo grupo">
		<div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.conta.minha_conta') }}</h1>
            </header>

            <div class="minha-conta grid">
                <div class="minha-conta__coluna-menu grid-xs--12 grid-md--3">
                    @include('frontend.my-account.partials.navigation')
                </div>

                <div class="minha-conta__coluna-conteudo grid-xs--12 grid-md--9">
                    <div class="minha-conta__pagina">
                        <h2 class="pagina__subtitulo">{{ __('frontend.conta.alterar_senha') }}</h2>
                        <div class="form form--auto-style">
                            <form action="{{ route(getRouteByLanguage('frontend.my-account.updatePassword')) }}" method="post" accept-charset="utf-8">
                                @csrf
                                <fieldset class="grid">
                                    <div class="campo grid-xs--12 grid-md--4">
                                        <label>{{ __('frontend.forms.senha_atual') }}</label>
                                        <input type="password" name="passwordactive" />
                                    </div>
                                </fieldset>
                                <fieldset class="grid">
                                    <div class="campo grid-xs--12 grid-md--4">
                                        <label>{{ __('frontend.forms.nova_senha') }}</label>
                                        <input type="password" name="password" />
                                        <small>{{ __('frontend.misc.minimo_caracteres_senha') }}</small>
                                    </div>

                                    <div class="campo grid-xs--12 grid-md--4">
                                        <label>{{ __('frontend.forms.confirme_nova_senha') }}</label>
                                        <input type="password" name="password_confirmation" />
                                    </div>
                                </fieldset>

                                <div class="a-centro">
                                    <button class="botao botao--submit" type="submit" name="submit">{{ __('frontend.conta.alterar_senha') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
