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
                        <h2 class="pagina__subtitulo">{{ __('frontend.conta.alterar_cadastro') }}</h2>
                        <div class="box mb">
                            <header class="box__header">
                                <h3 class="box__titulo">{{ __('frontend.forms.dados_gerais') }}</h3>
                            </header>
                            <div class="box__conteudo corpo-texto">
                                <p>
                                    @if($client->type == 'fisical')
                                    <strong>{{ __('frontend.forms.nome') }}:</strong> {{ mb_strtoupper($client->name) }}<br />
                                    <strong>{{ __('frontend.forms.email') }}:</strong> {{ mb_strtolower($client->email) }}<br />
                                    <strong>{{ __('frontend.forms.documento') }}:</strong> {{ __('frontend.forms.documento_id') }} {{ $client->identity }} {{ $client->uf }}<br />
                                    @if($client->country == 'BR')
                                    <strong>{{ __('frontend.forms.documento_cpf') }}:</strong> {{ $client->document }}<br />
                                    @endif
                                    <strong>{{ __('frontend.forms.sexo') }}:</strong> {{ mb_strtoupper($client->gender) }}<br />
                                    <strong>{{ __('frontend.forms.data_nascimento') }}:</strong> {{ $client->birthdate ? $client->birthdate->format('d/m/Y') : ''}}<br />
                                    <strong>{{ __('frontend.forms.idioma') }}:</strong> {{ translatedLanguage($client->language) }}<br />
                                    @foreach ($client->contacts as $contact)
                                        <strong>{{ ucwords($contact->type) }} </strong> {{ $contact->value }}<br />
                                    @endforeach
                                    @endif


                                    @if($client->type == 'legal')
                                    <strong>{{ __('frontend.forms.razao_social') }}:</strong> {{ mb_strtoupper($client->company_name) }}<br />
                                    <strong>{{ __('frontend.forms.nome_fantasia') }}:</strong> {{ mb_strtoupper($client->legal_name) }}<br />
                                    <strong>{{ __('frontend.forms.email') }}:</strong> {{ mb_strtolower($client->email) }}<br />
                                    <strong>{{ __('frontend.forms.documento_cnpj') }}:</strong> {{ $client->registry }}<br />
                                    <strong>{{ __('frontend.forms.idioma') }}:</strong> {{ translatedLanguage($client->language) }}<br />
                                    @foreach ($client->contacts as $contact)
                                        <strong>{{ ucwords($contact->type) }} </strong> {{ $contact->value }}<br />
                                    @endforeach
                                    @endif


                                </p>
                            </div>
                        </div>

                        <div class="box mb">
                            <header class="box__header">
                                <h3 class="box__titulo">{{ __('frontend.forms.endereco') }}</h3>
                            </header>
                            <div class="box__conteudo corpo-texto">
                                <p>
                                    {{ mb_strtoupper($client->address->address) }}, {{ mb_strtoupper($client->address->number) }}, {{ mb_strtoupper($client->address->complement) }}<br />
                                    {{ mb_strtoupper($client->address->neighborhood) }}, {{ mb_strtoupper(city($client->address->city)) }} – {{ mb_strtoupper(state($client->address->country, $client->address->state)) }} - {{ mb_strtoupper(country($client->country)) }}<br />
                                    {{ $client->address->zip }}
                                </p>
                            </div>
                        </div>

                        <div class="box mb">
                            <header class="box__header">
                                <h3 class="box__titulo">{{ __('frontend.forms.login') }}</h3>
                            </header>
                            <div class="box__conteudo corpo-texto">
                                <p><strong>{{ __('frontend.forms.login') }}:</strong> {{ mb_strtolower($client->username) }}</p>
                            </div>
                        </div>

                        <div class="corpo-texto">
                            <p>
                                <a class="icone" href="{{ route(getRouteByLanguage('frontend.my-account.edit')) }}"><i class="fas fa-edit"></i> {{ __('frontend.conta.alterar_cadastro') }}</a>
                                <a class="icone" href="{{ route(getRouteByLanguage('frontend.my-account.editPassword')) }}"><i class="fas fa-lock"></i> {{ __('frontend.conta.alterar_senha') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
