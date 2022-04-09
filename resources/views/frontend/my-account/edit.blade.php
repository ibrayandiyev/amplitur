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
                        <div class="form form--auto-style">
							<form action="{{ route(getRouteByLanguage('frontend.my-account.update')) }}" id="clientForm" method="post" accept-charset="utf-8">
								@csrf
								<input type="radio" name="type" value="{{ $client->type }}" style="display: none;" checked="checked">

								<div class="box mb">
									<header class="box__header">
										<h3 class="box__titulo">{{ __('frontend.forms.dados_gerais') }}</h3>
									</header>
									<div class="box__conteudo">
										<div class="campo">
                                            @if($client->type == 'fisical')
											<strong>{{ __('frontend.forms.nome') }}:</strong> {{ mb_strtoupper($client->name) }}
                                            @endif
                                            @if($client->type == 'legal')
                                            <strong>{{ __('frontend.forms.razao_social') }}:</strong> {{ mb_strtoupper($client->company_name) }}<br />
                                            <strong>{{ __('frontend.forms.nome_fantasia') }}:</strong> {{ mb_strtoupper($client->legal_name) }}<br />
										    @endif
                                        </div>

										<fieldset class="grid">
											<div class="campo grid-xs--12 grid-md--8">
												<label>{{ __('frontend.forms.email') }}:</label>
												<input type="email" name="email" value="{{ mb_strtolower($client->email) }}" />
											</div>
											<div class="campo grupo grid-xs--12 grid-md--4">
												<label>{{ __('frontend.forms.idioma') }}</label>
												<select name="language">
													<option value="pt-br" @if ($client->language == 'pt-br') selected @endif>{{ __('frontend.geral.portugues') }}</option>
													<option value="en" @if ($client->language == 'en') selected @endif>{{ __('frontend.geral.ingles') }}</option>
													<option value="es" @if ($client->language == 'es') selected @endif>{{ __('frontend.geral.espanhol') }}</option>
												</select>
											</div>
										</fieldset>
										<fieldset class="grid">
											@foreach ($client->contacts as $contact)
												<div class="campo grid-xs--12">
													<div class="grid">
														<label class="grid-xs--12">{{ __('frontend.forms.telefone') }}</label>
														<div class="grid-xs--7 grid-md--7">
															<input type="hidden" name="contacts[id][]" value="{{ $contact->id ?? null }}">
															<input type="tel" class="phone-flag" name="contacts[value][]" value="{{ $contact->value ?? null }}" maxlength="16" autocomplete="off">
															<input type="hidden" name="contacts[type][]" value="{{ $contact->type }}">
														</div>
													</div>
												</div>
											@endforeach
										</fieldset>
                                        @if($client->type == 'fisical')
										<fieldset class="grid">
											<div class="campo grid-xs--12 grid-sm--6 grid-md--4">
												<label>{{ __('frontend.forms.sexo') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
												<select name="gender">
													<option value="">{{ __('frontend.forms.selecione') }}</option>
													<option value="female" @if ($client->gender == 'female') selected @endif>{{ __('frontend.forms.sexo_feminino') }}</option>
													<option value="male" @if ($client->gender == 'male') selected @endif>{{ __('frontend.forms.sexo_masculino') }}</option>
													<option value="transgender" @if ($client->gender == 'transgender') selected @endif>{{ __('frontend.forms.sexo_trans_g') }}</option>
													<option value="non-binary" @if ($client->gender == 'non-binary') selected @endif>{{ __('frontend.forms.sexo_n_binario') }}</option>
												</select>
											</div>
										</fieldset>
                                        @endif
									</div>
								</div>

								<div class="box mb">
									<header class="box__header">
										<h3 class="box__titulo">{{ __('frontend.forms.documentos') }}</h3>
									</header>
									<div class="box__conteudo">
										<fieldset class="grid">
                                            @if($client->type == 'fisical')
											<div class="campo grid-xs--12 grid-sm--6 grid-md--4">
												<label>{{ __('frontend.forms.documento') }}</label>
												{{ __('frontend.forms.documento_id') }}: {{ $client->identity }} - {{ $client->uf }}
											</div>
                                            @endif
                                            @if($client->type == 'legal')
											<div class="campo grid-xs--12 grid-sm--6 grid-md--4">
												<label>{{ __('frontend.forms.documento') }}</label>
												{{ __('frontend.forms.documento_cnpj') }}: {{ $client->registry }}
											</div>
                                            @endif
                                            @if($client->country == 'BR')
											<div class="campo grid-xs--12 grid-sm--6 grid-md--4">
												<label>{{ __('frontend.forms.documento_cpf') }}</label>
												{{ $client->document }}
											</div>
                                            @endif
                                            @if($client->type == 'fisical')
											<div class="campo grid-xs--12 grid-sm--6 grid-md--4">
												<label>{{ __('frontend.forms.data_nascimento') }}</label>
												{{ $client->birthdate ? $client->birthdate->format('d/m/Y') : '' }}
											</div>
                                            @endif
										</fieldset>
									</div>
								</div>

								<div class="box mb">
									<header class="box__header">
										<h3 class="box__titulo">{{ __('frontend.forms.endereco') }}</h3>
									</header>
									<div class="box__conteudo">
										<fieldset class="grid">
											<div class="campo grid-xs--12 grid-sm--6 grid-md--3">
												<label>{{ __('frontend.forms.pais') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
												<select name="address[country]" id="sl-pais">
													<option value="">{{ __('frontend.forms.selecione') }}</option>
													@include('frontend.template.components.select-country-options', ['selectedValue' => $client->address->country])
												</select>
											</div>
											<div class="campo grid-xs--12 grid-sm--6 grid-md--2" data-state-region>
												<label>{{ __('frontend.forms.estado') }}</label>
												<select name="address[state]" id="sl-estado" disabled>
													<option value="">--</option>
												</select>
											</div>
											<div class="campo grid-xs--12 grid-sm--6 grid-md--6" data-city-region>
												<label>{{ __('frontend.forms.cidade') }}</label>
												<select name="address[city]" id="sl-cidade" disabled>
													<option value="">--</option>
												</select>
											</div>
											<div class="campo grid-xs--12 grid-md--6">
												<label>{{ __('frontend.forms.logradouro') }}</label>
												<input type="text" name="address[address]" class="input-limpa" value="{{ mb_strtoupper($client->address->address) }}">
											</div>
											<div class="campo grid-xs--12 grid-sm--6 grid-md--3">
												<label>{{ __('frontend.forms.numero') }}</label>
												<input type="text" name="address[number]" class="input-limpa" value="{{ mb_strtoupper($client->address->number) }}">
											</div>
											<div class="campo grid-xs--12 grid-sm--6 grid-md--3">
												<label>{{ __('frontend.forms.complemento') }}</label>
												<input type="text" name="address[complement]" class="input-limpa" value="{{ mb_strtoupper($client->address->complement) }}">
											</div>
											<div class="campo grid-xs--12 grid-sm--6 grid-md--4">
												<label>{{ __('frontend.forms.bairro') }}</label>
												<input type="text" name="address[neighborhood]" class="input-limpa" value="{{ mb_strtoupper($client->address->neighborhood) }}">
											</div>
											<div class="campo grid-xs--12 grid-sm--6 grid-md--2">
												<label>{{ __('frontend.forms.cep') }}</label>
												<input type="text" name="address[zip]" class="input-limpa" value="{{ $client->address->zip }}">
											</div>
										</fieldset>
									</div>
								</div>

								<div class="campo">
									<label>{{ __('frontend.forms.newsletter_gostaria') }}</label>
									<div class="form__checkbox">
										<input type="radio" name="is_newsletter_subscriber" id="newsletter-1" @if ($client->is_newsletter_subscriber) checked @endif  value="1">
										<label for="newsletter-1">{{ __('frontend.forms.sim') }}</label>
									</div>
									<div class="form__checkbox">
										<input type="radio" name="is_newsletter_subscriber" id="newsletter-0" @if (!$client->is_newsletter_subscriber) checked @endif value="0">
										<label for="newsletter-0">{{ __('frontend.forms.nao') }}</label>
									</div>
								</div>

								<br />

								<div class="a-centro">
									<button class="botao botao--submit" type="submit">{{ __('frontend.conta.alterar_cadastro') }}</button>
								</div>

							</form>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
	<link rel="stylesheet" href="/frontend/vendors/intl-tel-input/css/intlTelInput.css" />
	<style>
		.iti-mobile .iti--container {
			top: 64%;
			bottom: 30px;
			left: 50%;
			right: 17%;
			position: fixed;
		}
	</style>
@endpush

@push('scripts')
    <script src="/frontend/vendors/bootstrap-inputmask/jquery.inputmask.min.js"></script>
    <script src="/frontend/vendors/bootstrap-inputmask/inputmask.binding.js"></script>
	<script src="/frontend/vendors/intl-tel-input/js/intlTelInput.min.js"></script>
    <script src="/frontend/js/personable.min.js"></script>
	<script>
		$(document).ready(function () {
			catchFormSubmit($('#clientForm'));

			fillAddress({
				country: "{{ $client->address->country }}",
				state: "{{ $client->address->state }}",
				city: "{{ $client->address->city }}"
			});
		});
	</script>
@endpush
