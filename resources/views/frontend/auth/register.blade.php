@extends('frontend.template.page')

@section('title'){{__('frontend.seo.cadastro_titulo')}}@endsection
@section('description'){{__('frontend.seo.cadastro_metadesc')}}@endsection
@section('url'){{ route(getroutebylanguage('frontend.auth.register')) }}@endsection

@section('content')
    <main class="conteudo grupo">
        <div class="largura-site ma">
            <header class="pagina__header">
                <h1 class="pagina__titulo">{{ __('frontend.conta.cadastre_se') }}</h1>
            </header>

            <div class="corpo-texto">
	            <p>{{ __('frontend.forms.campos_obrigatorios') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr> {{ __('frontend.forms.campos_obrigatorios2') }}</p>
            </div>

            <form action="{{ route('frontend.auth.doRegister') }}" id="clientForm" method="post" accept-charset="utf-8">
                @csrf
                <div class="form form--cadastro form--auto-style">
                    <div class="box mb">
                        <header class="box__header">
                            <h2 class="box__titulo">{{ __('frontend.forms.dados_gerais') }}</h2>
                        </header>

                        <div class="box__conteudo">
                            <fieldset class="grid">
                                <div class="campo grid-xs--12 grid-md--6">
                                    <label>{{ __('frontend.forms.tipo_cadastro') }}<abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <div class="form__checkbox form__checkbox--radio">
                                        <input type="radio" class="rd-tipo" id="tipo_1" name="type" value="fisical" @if (old('type') == 'fisical' || empty(old('type'))) checked="checked" @endif>
                                        <label for="tipo_1">{{ __('frontend.forms.tipo_pf') }}</label>
                                    </div>
                                    <div class="form__checkbox form__checkbox--radio">
                                        <input type="radio" class="rd-tipo" name="type" id="tipo_2" value="legal" @if (old('type') == 'legal') checked="checked" @endif>
                                        <label for="tipo_2">{{ __('frontend.forms.tipo_pj') }}</label>
                                    </div>
                                </div>
                                <div class="campo grid-xs--12 grid-sm--6 grid-md--3">
                                    <label>{{ __('frontend.forms.pais') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <select name="address[country]" id="sl-pais">
                                        <option value="">{{ __('frontend.forms.selecione') }}</option>
                                        @include('frontend.template.components.select-country-options', ['selectedValue' => old('address.country')])
                                    </select>
                                </div>
                                <div class="campo grid-xs--12 grid-sm--6 grupo grid-md--3">
                                    <label>{{ __('frontend.forms.idioma') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <select name="language">
                                        <option value="">{{ __('frontend.forms.selecione') }}</option>
                                        <option value="pt-br" @if (old('language') == 'pt-br') selected @endif>{{ __('frontend.geral.portugues') }}</option>
                                        <option value="en" @if (old('language') == 'en') selected @endif>{{ __('frontend.geral.espanhol') }}</option>
                                        <option value="es" @if (old('language') == 'es') selected @endif>{{ __('frontend.geral.ingles') }}</option>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset class="grid">
                                <div id="camponome" class="campo grid-xs--12 grid-md--6 grid-md--8">
                                    <label for="name">
                                        <label id="labelnome">{{ __('frontend.forms.nome_completo') }}</label>
                                        <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr>
                                    </label>
                                    <input type="text" id="nome" class="input-limpa" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="campo pj grid-xs--12 grid-md--6 grid-md--4" style="display: none;">
                                    <label>{{ __('frontend.forms.razao_social') }}<abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" name="legal_name" class="input-limpa" value="{{ old('legal_name') }}">
                                </div>
                                <div class="campo pf grid-xs--12 grid-sm--6 grupo grid-md--3" style="">
                                    <label>{{ __('frontend.forms.sexo') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <select name="gender">
                                        <option value="">{{ __('frontend.forms.selecione') }}</option>
                                        <option value="female" @if (old('gender') == 'female') selected @endif>{{ __('frontend.forms.sexo_feminino') }}</option>
                                        <option value="male" @if (old('gender') == 'male') selected @endif>{{ __('frontend.forms.sexo_masculino') }}</option>
                                        <option value="transgender" @if (old('gender') == 'transgender') selected @endif>{{ __('frontend.forms.sexo_trans_g') }}</option>
                                        <option value="non-binary" @if (old('gender') == 'non-binary') selected @endif>{{ __('frontend.forms.sexo_n_binario') }}</option>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset class="grid">
                                <div class="campo grid-xs--12 grid-sm--6">
                                    <label for="email">{{ __('frontend.forms.email') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="email" id="email" name="email" class="input-email" value="{{ old('email') }}">
                                </div>
                                <div class="campo grid-xs--12 grid-sm--6">
                                    <label for="emailconf">{{ __('frontend.forms.confirme_email') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="email" id="emailconf" name="email_confirmation" class="input-email" value="{{ old('email_confirmation') }}">
                                </div>
                            </fieldset>
                            <fieldset class="grid">
                                <div class="campo grid-xs--12 grid-sm--6 grid-md--6">
                                    <div class="grid">
                                        <label class="grid-xs--12">{{ __('frontend.forms.telefone_principal') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr>
                                        </label>
                                        <div class="grid-xs--6 grid-md--6">
                                            <input type="tel" id="phone-resident" class="phone-flag" name="contacts[value][]" value="{{ old('contacts.value.0') }}" maxlength="16" autocomplete="off">
										    <input type="hidden" name="contacts[type][]" value="{{ old('residential') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="campo grid-xs--12 grid-sm--6 grid-md--6">
                                    <div class="grid">
                                        <label class="grid-xs--12">{{ __('frontend.forms.telefone_secundario') }}</label>
                                        <div class="grid-xs--6 grid-md--6">
                                            <input type="tel" id="phone-mobile" class="phone-flag" name="contacts[value][]" value="{{ old('contacts.value.1') }}" maxlength="16" autocomplete="off">
										    <input type="hidden" name="contacts[type][]" value="{{ old('mobile') }}">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="box mb">
                        <header class="box__header">
                            <h2 class="box__titulo">{{ __('frontend.forms.dados_gerais') }}</h2>
                        </header>
                        <div class="box__conteudo">
                            <fieldset class="grid">
                                <div class="campo pf grid-xs--12 grid-sm--6 grid-md--3 grid-nm--2" style="">
                                    <label>{{ __('frontend.forms.documentos') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <select name="primary_document" id="sl-documento"  @if(old('primary_document')!= "") data-edit="1" @endif >
                                        <option value="">{{ __('frontend.forms.selecione') }}</option>
                                        <option value="identity" @if (old('primary_document') == 'identity') selected @endif>{{ __('frontend.forms.documento_id') }}</option>
                                        <option value="passport" @if (old('primary_document') == 'passport') selected @endif>{{ __('frontend.forms.documento_passaporte') }}</option>
                                    </select>
                                </div>
                                <div class="campo pf rg grid-xs--12 grid-sm--6 grid-md--3 grid-nm--2" style="" data-identity-required>
                                    <label>{{ __('frontend.forms.documento_id') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" name="identity" class="input-limpa" value="{{ old('identity') }}">
                                </div>
                                <div class="campo pf rg nacional grid-xs--12 grid-sm--6 grid-md--3 grid-nm--2" style="display: none;" data-identity-required>
                                    <label>{{ __('frontend.forms.estado_emissor') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <select name="uf" id="uf">
                                        <option value="">--</option>
                                        @include('frontend.template.components.select-brazil-state-options', ['selectedValue' => old('uf')])
                                    </select>
                                </div>
                                <div class="campo pf ps grid-xs--12 grid-sm--6 grid-md--3 grid-nm--2" style="display: none;" data-passport-required>
                                    <label>{{ __('frontend.forms.documento_passaporte') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" name="passport" class="input-limpa" value="{{ old('passport') }}">
                                </div>
                                <div class="campo pf nacional grid-xs--12 grid-sm--6 grid-md--3 grid-nm--2" style="display: none;" data-brazil-only>
                                    <label>{{ __('frontend.forms.documento_cpf') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="cpf" name="document" class="input-limpa" value="{{ old('document') }}" maxlength="14">
                                </div>
                                <div class="campo pf grid-xs--12 grid-sm--6 grid-md--3" style="">
                                    <label>{{ __('frontend.forms.data_nascimento') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="birthdate" name="birthdate" class="input-limpa" value="{{ old('birthdate') }}" maxlength="10">
                                </div>
                            </fieldset>
                            <fieldset class="grid">
                                <div class="campo pj grid-xs--12 grid-sm--6 grid-md--4" style="display: none;">
                                    <label>{{ __('frontend.forms.documento_cnpj') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="cnpj" name="registry" class="input-limpa" value="{{ old('registry') }}" maxlength="18">
                                </div>
                            </fieldset>
                            <fieldset class="grid">
                                <div class="campo pj grid-xs--12 grid-sm--6 grid-md-4" styçe="display: none;">
                                    <label>{{ __('frontend.forms.nome_responsavel') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="responsible_name" name="responsible_name" class="input-limpa" value="{{ old('responsible_name') }}" maxlength="50">
                                </div>
                                <div class="campo pj grid-xs--12 grid-sm--6 grid-md-4" styçe="display: none;">
                                    <label>{{ __('frontend.forms.email') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="responsible_email" name="responsible_email" class="input-email" value="{{ old('responsible_email') }}" maxlength="60">
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="box mb">
                        <header class="box__header">
                            <h2 class="box__titulo">{{ __('frontend.forms.endereco') }}</h2>
                        </header>
                        <div class="box__conteudo">
                            <fieldset class="grid">
                                <div class="campo grid-xs--6 grid-md--2" data-state-region>
                                    <label>{{ __('frontend.forms.estado') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <select name="address[state]" id="sl-estado" disabled>
                                        <option value="">--</option>
                                    </select>
                                </div>
                                <div class="campo grid-xs--6 grid-md--3 grid-nm--3" data-city-region>
                                    <label>{{ __('frontend.forms.cidade') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr> </label>
                                    <select name="address[city]" id="sl-cidade" disabled>
                                        <option value="">--</option>
                                    </select>
                                </div>
                                <div class="campo grid-xs--12 grid-md--6">
                                    <label>{{ __('frontend.forms.logradouro') }}<abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="address[address]" name="address[address]" class="input-limpa" value="{{ old('address.address') }}">
                                </div>
                                <div class="campo grid-xs--6 grid-md--3">
                                    <label>{{ __('frontend.forms.numero') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="address[number]"  name="address[number]" class="input-limpa" value="{{ old('address.number') }}">
                                </div>
                                <div class="campo grid-xs--6 grid-md--3">
                                    <label>{{ __('frontend.forms.complemento') }}</label>
                                    <input type="text" id="address[complement]" name="address[complement]" class="input-limpa" value="{{ old('address.complement') }}">
                                </div>
                                <div class="campo grid-xs-12 grid-md-3 grid-nm--2">
                                    <label>{{ __('frontend.forms.bairro') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="address[neighborhood]" name="address[neighborhood]" class="input-limpa" value="{{ old('address.neighborhood') }}">
                                </div>
                                <div class="campo grid-xs--12 grid-md--2">
                                    <label>{{ __('frontend.forms.contato_cep') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr>
                                    </label>
                                    <input type="text" id="address[zip]" name="address[zip]" class="input-limpa" value="{{ old('address.zip') }}">
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="box mb">
                        <header class="box__header">
                            <h2 class="box__titulo">{{ __('frontend.forms.dados_login') }}</h2>
                        </header>
                        <div class="box__conteudo">
                            <fieldset class="grid">
                                <div class="campo grid-xs--12 grid-md--3">
                                    <label>{{ __('frontend.forms.login') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="text" id="username" name="username" value="{{ old('username') }}">
                                    <small>{{ __('frontend.misc.minimo_caracteres_login') }}</small>
                                </div>
                            </fieldset>

                            <fieldset class="grid">
                                <div class="campo grid-xs--12 grid-sm--6 grid-md--3">
                                    <label for="senha">{{ __('frontend.forms.senha') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="password" id="senha" name="password" value="">
                                    <small>{{ __('frontend.misc.minimo_caracteres_senha') }}</small>
                                </div>

                                <div class="campo grid-xs--12 grid-sm--6 grid-md--3">
                                    <label for="senhaconf">{{ __('frontend.forms.confirme_senha') }} <abbr title="{{ __('frontend.forms.campos_obrigatorios2') }}">*</abbr></label>
                                    <input type="password" id="senhaconf" name="password_confirmation" value="">
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="campo esconde">
                        <label for="naopreencher">Não preencher este campo (apenas para barrar spam)</label>
                        <input type="text" id="naopreencher" name="naopreencher" value="">
                    </div>

                    <div class="campo">
                        <label>{{ __('frontend.forms.newsletter_gostaria') }}</label>
                        <div class="form__checkbox form__checkbox--radio">
                            <input type="radio" name="is_newsletter_subscriber" id="newsletter_1" checked="checked" value="1">
                            <label for="newsletter_1">{{ __('frontend.forms.sim') }}</label>
                        </div>
                        <div class="form__checkbox form__checkbox--radio">
                            <input type="radio" name="is_newsletter_subscriber" id="newsletter_2" value="0">
                            <label for="newsletter_2">{{ __('frontend.forms.nao') }} </label>
                        </div>
                    </div>

                    <div class="a-centro mb">
                        <button type="submit" name="submit" class="botao botao--submit">{{ __('frontend.forms.confirmar_cadastro') }}</button>
                    </div>
                </div>
            </form>
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
    <script type="text/javascript">
        var tipo;
        var nacional;

        $(document).ready(function() {
            catchFormSubmit($('#clientForm'));

            $('.rd-tipo').click(function() {
                checkTipo();
            });

            $('#sl-documento').change(function() {
                checkDoc();
            });

            $('#sl-pais').change(function() {
                checkPais();
            })

            checkPais();
            checkTipo();
            checkDoc();
        });

        function checkPais() {
            var val = $('#sl-pais').val();
            nacional = val == 'BR';
            checkTipo();
            refreshRegistryMask();
        }

        function checkTipo() {
            $('.rd-tipo').each(function() {
                if($(this).prop('checked')) {
                    var valor = $(this).val();
                    if(valor == 'legal') {
                        tipo = 'legal';
                        toggleAgencia();
                        return;
                    } else {
                        tipo = 'fisical';
                        toggleCliente();
                        return;
                    }
                }

                //padrão
                toggleCliente();
            });
        }

        function toggleCliente() {
            $('.pj').hide();
            $('.pf').show();

            $('#labelnome').text('Nome Completo');

            if(nacional) {
                $(".estrangeiro").hide();
                $(".pf.nacional").show();
            } else {
                $(".pj.estrangeiro").show();
                $(".nacional").hide();
            }

            checkDoc();
        }

        function toggleAgencia() {
            $('.pj').show();
            $('.pf').hide();

            $('#labelnome').text('Nome Fantasia');

            if(nacional) {
                $(".estrangeiro").hide();
                $(".pj.nacional").show();
            } else {
                $(".pj.estrangeiro").show();
                $(".nacional").hide();
            }
        }

        function checkDoc() {
            if(tipo == 'legal') {
                return;
            }

            var valor = $('#sl-documento').val();

            if(valor == 'passport') {
                togglePass();
            } else {
                toggleRG();
            }
        }

        function toggleRG() {
            $('.rg').show();
            $('.ps').hide();

            if (!nacional) {
                setTimeout(() => {
                    $('.rg.nacional').hide();
                }, 1);
            }
        }

        function togglePass() {
            $('.rg').hide();
            $('.ps').show();
        }
    </script>
    <script>
        $(document).ready(function () {
            fillAddress({
                country: "{{ old('address.country') }}",
                state: "{{ old('address.state') }}",
                city: "{{ old('address.city') }}"
            });
        });
    </script>
@endpush
