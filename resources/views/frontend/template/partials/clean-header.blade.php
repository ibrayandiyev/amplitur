<header class="cabecalho">
    <div class="largura-site--sem-padding ma">
        <div class="cabecalho__grid">
            {{-- Header Logo --}}
            <a class="cabecalho__logo" href="{{ route('frontend.index') }}">
                <img src="/frontend/images/estrutura/amp-travel-front-bgblue2.png" srcset="/frontend/images/estrutura/amp-travel-front-bgblue2.png 1x, /frontend/images/estrutura/amp-travel-front-bgblue@2x.png 2x">
            </a>
            <div class="usuario-meta__container">
                <div class="cabecalho__usuario-meta usuario-meta">
                    <div class="usuario-meta__logado">
                        <span class="usuario-meta__nome">
                            {{ __('frontend.geral.ola') }}, {{ auth('clients')->user()->getFirstName() }}</span>
                        <a href="{{ route('frontend.auth.doLogout') }}">{{ __('frontend.conta.sair') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
