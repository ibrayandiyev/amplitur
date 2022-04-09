
<header class="cabecalho">
    <div class="largura-site--sem-padding ma">
        <div class="cabecalho__grid">
            {{-- Header Logo --}}
            <a class="cabecalho__logo" href="{{ route('frontend.index') }}">
                <img src="/frontend/images/estrutura/amp-travel-front-bgblue2.png" srcset="/frontend/images/estrutura/amp-travel-front-bgblue2.png 1x, /frontend/images/estrutura/amp-travel-front-bgblue@2x.png 2x">
            </a>

            {{-- Language and Currency Selection --}}
            <div class="selecao-linguas__container">

                {{-- Language Selection --}}
                <div class="cabecalho__selecao-linguas selecao-linguas">
                    <div class="selecao-linguas__selecao">
                        <span class="selecao-linguas__lingua">
                            @if (language() == 'pt-br')
                                <span class="selecao-linguas__ext">Português</span>
                                <span class="selecao-linguas__abbr">PT</span>
                            @elseif (language() == 'es')
                                <span class="selecao-linguas__ext">Español</span>
                                <span class="selecao-linguas__abbr">ES</span>
                            @else
                                <span class="selecao-linguas__ext">English</span>
                                <span class="selecao-linguas__abbr">EN</span>
                            @endif
                        </span>
                        <span class="selecao-linguas__flecha">
                            <i class="fas fa-angle-down selecao-linguas__icone"></i>
                        </span>
                    </div>
                    <ul class="selecao-linguas__lista">
                        <li class="selecao-linguas__item">
                            <a class="selecao-linguas__link selecao-linguas__pt" href="{{ route('frontend.language.change', 'pt-br') }}">
                                <span class="selecao-linguas__ext">Português</span>
                                <span class="selecao-linguas__abbr">PT</span>
                            </a>
                        </li>
                        <li class="selecao-linguas__item">
                            <a class="selecao-linguas__link selecao-linguas__es" href="{{ route('frontend.language.change', 'es') }}">
                                <span class="selecao-linguas__ext">Español</span>
                                <span class="selecao-linguas__abbr">ES</span>
                            </a>
                        </li>
                        <li class="selecao-linguas__item">
                            <a class="selecao-linguas__link selecao-linguas__en" href="{{ route('frontend.language.change', 'en') }}">
                                <span class="selecao-linguas__ext">English</span>
                                <span class="selecao-linguas__abbr">EN</span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Currency Selection --}}
                <div class="selecao-moedas__container">
                    <div class="cabecalho__selecao-moedas selecao-moedas">
                        <div class="selecao-moedas__selecao">
                            @if (!empty(currency()) && currency()->code == \App\Enums\Currency::EURO)
                                <span class="selecao-moedas__lingua">
                                    <span class="selecao-moedas__ext">EUR - €</span>
                                    <span class="selecao-moedas__abbr">EUR - €</span>
                                </span>
                            @elseif (!empty(currency()) && currency()->code == \App\Enums\Currency::DOLLAR)
                                <span class="selecao-moedas__lingua">
                                    <span class="selecao-moedas__ext">USD - US$</span>
                                    <span class="selecao-moedas__abbr">USD - US$</span>
                                </span>
                            @elseif (!empty(currency()) && currency()->code == \App\Enums\Currency::LIBRA)
                                <span class="selecao-moedas__lingua">
                                    <span class="selecao-moedas__ext">GBP - £</span>
                                    <span class="selecao-moedas__abbr">GBP - £</span>
                                </span>
                            @else
                                <span class="selecao-moedas__lingua">
                                    <span class="selecao-moedas__ext">BRL - R$</span>
                                    <span class="selecao-moedas__abbr">BRL - R$</span>
                                </span>
                            @endif
                            <span class="selecao-moedas__flecha">
                                <i class="fas fa-angle-down selecao-moedas__icone"></i>
                            </span>
                        </div>
                        <ul class="selecao-moedas__lista">
                            <li class="selecao-moedas__item">
                                <a class="selecao-moedas__link selecao-moedas_2" title="Moedas" href="{{ route('frontend.currency.change', \App\Enums\Currency::DOLLAR) }}">
                                    <span class="selecao-moedas__ext">USD - US$</span>
                                    <span class="selecao-moedas__abbr">USD - US$</span>
                                </a>
                            </li>
                            <li class="selecao-moedas__item">
                                <a class="selecao-moedas__link selecao-moedas_3" title="Moedas" href="{{ route('frontend.currency.change', \App\Enums\Currency::EURO) }}">
                                    <span class="selecao-moedas__ext">EUR - €</span>
                                    <span class="selecao-moedas__abbr">EUR - €</span>
                                </a>
                            </li>
                            <li class="selecao-moedas__item">
                                <a class="selecao-moedas__link selecao-moedas_11" title="Moedas" href="{{ route('frontend.currency.change', \App\Enums\Currency::LIBRA) }}">
                                    <span class="selecao-moedas__ext">GBP - £</span>
                                    <span class="selecao-moedas__abbr">GBP - £</span>
                                </a>
                            </li>
                            <li class="selecao-moedas__item">
                                <a class="selecao-moedas__link selecao-moedas_11" title="Moedas" href="{{ route('frontend.currency.change', \App\Enums\Currency::REAL) }}">
                                    <span class="selecao-moedas__ext">BRL - R$</span>
                                    <span class="selecao-moedas__abbr">BRL - R$</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>



            {{-- Authentication --}}
            <div class="usuario-meta__container">
                <div class="cabecalho__usuario-meta usuario-meta">
                    @if (auth('clients')->check())
                        <div class="usuario-meta__logado">
                            <span class="usuario-meta__nome">
                                {{ __('frontend.geral.ola') }}, {{ auth('clients')->user()->getFirstName() }}
                            </span>
                            <a href="{{ route('frontend.my-account.index') }}">{{ __('frontend.conta.minha_conta') }}</a> | <a href="{{ route('frontend.auth.doLogout') }}">{{ __('frontend.conta.logout') }}</a>
                        </div>
                    @else
                        <div class="usuario-meta__form">
                            <form id="" action="{{ route('frontend.auth.doLogin') }}" method="post">
                                @csrf
                                <div class="flex-grid flex-grid--gutters usuario-meta__grid">
                                    <div class="usuario-meta__campo">
                                        <label for="ftlogin" class="esconde-vis">{{ __('frontend.forms.login_email') }}</label>
                                        <input type="text" placeholder="{{ __('frontend.forms.login_email') }}" id="ftlogin" name="username" value="">
                                    </div>
                                    <div class="usuario-meta__campo">
                                        <label for="ftsenha" class="esconde-vis">{{ __('frontend.forms.senha') }}</label>
                                        <input type="password" placeholder="{{ __('frontend.forms.senha') }}" name="password" value="">
                                    </div>

                                    <button type="submit" class="usuario-meta__botao" name="submit">{{ __('frontend.forms.entrar') }}</button>
                                </div>
                            </form>
                        </div>

                        <p class="usuario-meta__opcoes">
                            {{ __('frontend.conta.ainda_nao_cadastro') }} <a href="{{ route('frontend.auth.register') }}">{{ __('frontend.conta.cadastre_se_agora') }}</a>
                            <br>
                            <a href="{{ route('frontend.auth.recovery') }}">{{ __('frontend.conta.nao_consegue_acessar') }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
