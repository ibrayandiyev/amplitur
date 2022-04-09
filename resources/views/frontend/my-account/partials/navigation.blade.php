<nav class="minha-conta__menu mb">
    <span class="minha-conta__menu-trigger">
        <i class="fas fa-bars"></i> Menu
    </span>
    <ul class="minha-conta__menu-lista">
        <li class="minha-conta__menu-item">
            <a class="minha-conta__menu-link @if(Route::is('frontend.my-account.index')) ativo  @endif" href="{{ route('frontend.my-account.index') }}">Home</a>
        </li>
        <li class="minha-conta__menu-item">
            <a class="minha-conta__menu-link @if(Route::is('frontend.my-account.bookings.active')) ativo  @endif" href="{{ route('frontend.my-account.bookings.active') }}">{{ __('frontend.conta.minhas_viagens_ativas') }}</a>
        </li>
        <li class="minha-conta__menu-item">
            <a class="minha-conta__menu-link @if(Route::is('frontend.my-account.bookings.past')) ativo  @endif" href="{{ route('frontend.my-account.bookings.past') }}">{{ __('frontend.conta.minhas_viagens_passadas') }}</a>
        </li>
        <li class="minha-conta__menu-item">
            <a class="minha-conta__menu-link @if(Route::is('frontend.my-account.show')) ativo  @endif" href="{{ route(getRouteByLanguage('frontend.my-account.show')) }}">{{ __('frontend.conta.meu_cadastro') }}</a>
        </li>
        <li class="minha-conta__menu-item">
            <a class="minha-conta__menu-link" href="{{ route('frontend.auth.doLogout') }}"><i class="fas fa-angle-left"></i> {{ __('frontend.conta.sair') }}
            </a>
        </li>
    </ul>
</nav>
