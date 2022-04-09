@extends('frontend.template.clean')

@section('content')
    <div class="largura-site ma">
        <div class="grid">
            <div class="grid-xs--12 grid-md--9">
                <header class="pacote__header">
                    <span class="pacote__gravata">
                        {{ __('frontend.reservas.compra_pacote') }}
                    </span>
                    <h1 class="pacote__titulo">
                        <span class="pacote__nome">{{ $booking->getName() }}</span>
                    </h1>
                </header>

                <div class="corpo-texto">
                    <p>{{ __('frontend.reservas.continuidade') }}</p>
                </div>

                <div id="passo-de-compra" class="passos form form--auto-style mb2">
                    <form id="bookingForm" action="{{ route('frontend.booking.payment') }}" method="get" accept-charset="uft8">
                        @csrf
                        <div class="passo passo--servico-principal">
                            <header class="passo__header">
                                <span class="passo__num"></span>
                                <h2 class="passo__titulo">{{ __('frontend.forms.pacote_escolhido') }}</h2>
                            </header>
                            <div class="passo__conteudo">
                                <p>
                                    {{ mb_strtoupper($booking->getName()) }}<br />

                                    <strong>{{ mb_strtoupper($booking->product->getTitle()) }}</strong>
                                    <input type="hidden" id="servprin" name="servprin" data-valor="{{ $booking->total }}" data-quantidade="1" value="{{ $booking->offer->id }}">

                                </p>
                                @if ($booking->offer->isHotel())
                                <p>
                                {{ __('frontend.forms.selected_dates')}}: {{ implode(", ", $booking->getFormattedDates())}}
                                </p>
                                @endif
                            </div>
                        </div>

                        <div class="passo passo--contratante">
                            <header class="passo__header"><span class="passo__num"></span>
                                <h2 class="passo__titulo">{{ __('frontend.forms.dados_contratante') }} </h2>
                            </header>
                            <div class="passo__conteudo">
                                <input type="hidden" name="pais" value="30" class="pais">
                                <div class="grid">
                                    <p class="grid-xs--12 grid-sm--6">
                                        {{ __('frontend.forms.nome') }} : {{ mb_strtoupper($booking->bookingClient->name) }}<br/>
                                        {{ __('frontend.forms.email') }} : {{ mb_strtolower($booking->bookingClient->email) }}<br/>
                                        {{ __('frontend.forms.data_nascimento') }} : {{ $booking->getClientBirthdate() }}<br/>
                                        {{ __('frontend.forms.documento') }} : {{ $booking->getClientIdentity() }}<br/>
                                        {{ __('frontend.forms.documento_cpf') }} : {{ $booking->getClientDocument() }}<br/>
                                        {{ __('frontend.forms.telefone_principal') }} : {{ $booking->getClientPhone() }}
                                    </p>
                                    <p class="grid-xs--12 grid-sm--6">
                                        {{ __('frontend.forms.endereco') }} :<br/>
                                        {{ mb_strtoupper($booking->getClientAddress()) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="passo passo--passageiros">
                            <header class="passo__header"><span class="passo__num"></span>
                                <h2 class="passo__titulo">{{ __('frontend.forms.dados_adicionais') }} </h2>
                            </header>
                            <div data-numpass-max="5" class="passo__conteudo">
                                <input type="hidden" name="numpass" id="numpass" value="{{ $booking->passengers }}">
                                <ul id="lista-passageiros" class="lista-passageiros">
                                    @foreach ($booking->bookingPassengers as $key => $passenger)
                                        @php($key += 1)

                                        <li id="pass-{{ $key }}" class="list-passageiros__item passageiro mb">
                                            <header class="passageiro__header">
                                                <h3 class="passageiro__titulo">{{ __('frontend.reservas.passageiro') }}  {{ $key }}</h3>
                                            </header>
                                            <div class="passageiro__conteudo">
                                                <fieldset class="grid">
                                                    <div class="campo grid-xs--12 grid-md--12">
                                                        <label>{{ __('frontend.forms.nome') }} </label>
                                                        <input type="text" name="passenger[{{ $key }}][nome]" id="passageiro_{{ $key }}_nome" value="{{ mb_strtoupper($passenger->name) }}" class="pass-nome input-limpa">
                                                    </div>
                                                    <div class="campo grid-xs--5 grid-md--2">
                                                        <label>{{ __('frontend.forms.documento') }}</label>
                                                        <select name="passenger[{{ $key }}][documento]" data-pass="{{ $key }}" class="pass-doc sl-documento">
                                                            <option value="">{{ __('frontend.forms.selecione') }}</option>
                                                            <option selected="selected" value="rg">{{ __('frontend.forms.documento_id') }}</option>
                                                            <option value="passaporte">{{ __('frontend.forms.documento_passaporte') }}</option>
                                                            <option value="certidao" class="nacional">{{ __('frontend.forms.documento_certidao') }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="campo grid-xs--7 grid-md--3 rg-{{ $key }}">
                                                        <label>{{ __('frontend.forms.documento_id') }} <abbr title="campo obrigatório">*</abbr>
                                                        </label>
                                                        <input type="text" name="passenger[{{ $key }}][rg]" id="passageiro_{{ $key }}_rg" value="{{ mb_strtoupper($passenger->identity) }}" class="pass-rg input-limpa">
                                                    </div>
                                                    @if (!$booking->bookingClient->isForeigner())
                                                        <div class="campo nacional grid-xs--6 grid-md--2 rg-{{ $key }}">
                                                            <label>{{ __('frontend.forms.estado_emissor') }} </label>
                                                            <select name="passenger[{{ $key }}][est_emissor]" class="pass-estemissor">
                                                                <option value="">--</option>
                                                                <option value="AC" @if (mb_strtoupper($passenger->uf) == "AC") selected @endif>AC</option>
                                                                <option value="AL" @if (mb_strtoupper($passenger->uf) == "AL") selected @endif>AL</option>
                                                                <option value="AM" @if (mb_strtoupper($passenger->uf) == "AM") selected @endif>AM</option>
                                                                <option value="AP" @if (mb_strtoupper($passenger->uf) == "AP") selected @endif>AP</option>
                                                                <option value="BA" @if (mb_strtoupper($passenger->uf) == "BA") selected @endif>BA</option>
                                                                <option value="CE" @if (mb_strtoupper($passenger->uf) == "CE") selected @endif>CE</option>
                                                                <option value="DF" @if (mb_strtoupper($passenger->uf) == "DF") selected @endif>DF</option>
                                                                <option value="ES" @if (mb_strtoupper($passenger->uf) == "ES") selected @endif>ES</option>
                                                                <option value="GO" @if (mb_strtoupper($passenger->uf) == "GO") selected @endif>GO</option>
                                                                <option value="MA" @if (mb_strtoupper($passenger->uf) == "MA") selected @endif>MA</option>
                                                                <option value="MG" @if (mb_strtoupper($passenger->uf) == "MG") selected @endif>MG</option>
                                                                <option value="MS" @if (mb_strtoupper($passenger->uf) == "MS") selected @endif>MS</option>
                                                                <option value="MT" @if (mb_strtoupper($passenger->uf) == "MT") selected @endif>MT</option>
                                                                <option value="PA" @if (mb_strtoupper($passenger->uf) == "PA") selected @endif>PA</option>
                                                                <option value="PB" @if (mb_strtoupper($passenger->uf) == "PB") selected @endif>PB</option>
                                                                <option value="PE" @if (mb_strtoupper($passenger->uf) == "PE") selected @endif>PE</option>
                                                                <option value="PI" @if (mb_strtoupper($passenger->uf) == "PI") selected @endif>PI</option>
                                                                <option value="PR" @if (mb_strtoupper($passenger->uf) == "PR") selected @endif>PR</option>
                                                                <option value="RJ" @if (mb_strtoupper($passenger->uf) == "RJ") selected @endif>RJ</option>
                                                                <option value="RN" @if (mb_strtoupper($passenger->uf) == "RN") selected @endif>RN</option>
                                                                <option value="RO" @if (mb_strtoupper($passenger->uf) == "RO") selected @endif>RO</option>
                                                                <option value="RR" @if (mb_strtoupper($passenger->uf) == "RR") selected @endif>RR</option>
                                                                <option value="RS" @if (mb_strtoupper($passenger->uf) == "RS") selected @endif>RS</option>
                                                                <option value="SC" @if (mb_strtoupper($passenger->uf) == "SC") selected @endif>SC</option>
                                                                <option value="SE" @if (mb_strtoupper($passenger->uf) == "SE") selected @endif>SE</option>
                                                                <option value="SP" @if (mb_strtoupper($passenger->uf) == "SP") selected @endif>SP</option>
                                                                <option value="TO" @if (mb_strtoupper($passenger->uf) == "TO") selected @endif>TO</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    @if (!$booking->bookingClient->isForeigner())
                                                        <div class="campo nacional grid-xs--6 grid-md--2 rg-{{ $key }}">
                                                            <label>{{ __('frontend.forms.documento_cpf') }}  <abbr title="campo obrigatório">*</abbr>
                                                            </label>
                                                            <input type="tel" name="passenger[{{ $key }}][cpf]" value="{{ $passenger->document }}" required="required" class="pass-cpf mask-cpf" maxlength="14">
                                                        </div>
                                                    @endif
                                                    <div class="campo grid-md--2 ps-{{ $key }}" style="display: none;">
                                                        <label>{{ __('frontend.forms.documento_passaporte') }} </label>
                                                        <input type="text" name="passenger[{{ $key }}][passaporte]" value="{{ $passenger->passport }}" class="pass-passaporte input-limpa">
                                                    </div>
                                                    <div class="campo nacional grid-xs--7 grid-md--4 cert-{{ $key }}" style="display: none;">
                                                        <label>{{ __('frontend.forms.documento_certidao') }} </label>
                                                        <input type="text" name="passenger[{{ $key }}][certidao]" value="" id="passageiro_{{ $key }}_certidao" class="pass-certidao">
                                                    </div>
                                                    <div class="campo grid-xs--12 grid-md--3">
                                                        <label>{{ __('frontend.forms.data_nascimento') }}  <abbr title="campo obrigatório">*</abbr>
                                                        </label>
                                                        <input type="text" required="required" name="passenger[{{ $key }}][data_nascimento]" data-passageiro="{{ $key }}" id="passageiro_{{ $key }}_data_nascimento" value="{{ $passenger->birthdate ? $passenger->birthdate->format('d/m/Y') : '' }}" class="mask-data pass-data" maxlength="10">
                                                    </div>
                                                    <div class="campo grid-xs--8 grid-md--4">
                                                        <label>{{ __('frontend.forms.celular') }}  <abbr title="campo obrigatório">*</abbr>
                                                        </label>
                                                        <input type="tel" id="passageiro_{{ $key }}_fone" class="phone-flag" name="passenger[{{ $key }}][fone]" value="{{ old('contacts.value.0') }}" maxlength="16" autocomplete="off" required>
                                                    </div>
                                                    <div class="campo grid-xs--12 grid-md--4">
                                                        <label>{{ __('frontend.forms.email') }} </label>
                                                        <input type="email" name="passenger[{{ $key }}][email]" value="{{ $passenger->email }}" class="pass-email input-email">
                                                    </div>
                                                </fieldset>
                                                <div id="passenger[{{ $key }}][passadic]" class="passageiro__adicionais box box--border">
                                                    <header class="box__header">
                                                        <h4 class="box__titulo">{{ __('frontend.forms.adicionais') }} </h4>
                                                    </header>
                                                    <div>
                                                        <ul class="servicos-adicionais__lista">

                                                            @forelse ($passenger->bookingPassengerAdditionals ?? [] as $bookingPassengerAdditional)
                                                                @php($bookingPassengerAdditional->additional->refresh())
                                                                <li class="servicos-adicionais__item">
                                                                    <div class="servicos-adicionais__checkbox form__checkbox form__checkbox--radio">
                                                                        <i class="fas fa-check-circle"></i><span class="servicos-adicionais__nome"> {{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }}</span> –
                                                                        <strong class="servicos-adicionais__valor">{{ money($bookingPassengerAdditional->additional->getPrice(), currency(), $bookingPassengerAdditional->additional->currency) }}</strong>

                                                                        @if ($bookingPassengerAdditional->additional->isOutOfStock())
                                                                            <strong class="servicos-adicionais__selo servicos-adicionais__selo--esgotado">{{ __('frontend.pacotes.esgotado') }} </strong>
                                                                        @elseif ($bookingPassengerAdditional->additional->isRunningOut())
                                                                            <strong class="servicos-adicionais__selo servicos-adicionais__selo--ultimas-unidades">{{ $bookingPassengerAdditional->additional->getStock() }} {{ __('frontend.reservas.ultima') }} {{ __('frontend.reservas.unidade') }} </strong>
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                            @empty
                                                            @if ($booking->offer->isLongtrip() && $booking->getLongtripBoardingLocation())
                                                                <li class="servicos-adicionais__item">
                                                                    <div class="servicos-adicionais__checkbox form__checkbox form__checkbox--radio">
                                                                        <span class="servicos-adicionais__nome">· LONGTRIP - {{ mb_strtoupper($booking->bookingProducts[1]->getProduct()->getTitle()) }}<br /></span> –
                                                                        <strong class="servicos-adicionais__valor">{{ money($booking->bookingProducts[1]->getPrice()) }}</strong>
                                                                    </div>
                                                                </li>
                                                            @else
                                                            {{ __('frontend.misc.nenhum_adicional_selecionado') }}
                                                            @endif

                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="passo passo--contrato">
                            <header class="passo__header">
                                <span class="passo__num"></span>
                                <h2 class="passo__titulo">{{ __('frontend.reservas.contrato') }} </h2>
                            </header>
                            <div class="passo__conteudo">
                                <div class="reserva-contrato__iframe css-documentos">
                                    {!! $contract !!}
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="from_pacote" value="1">

                        <div class="form form--auto-style corpo-texto mt2">
                            <div class="campo">
                                <label>{{ __('frontend.forms.obs') }}  <span>{{ __('frontend.forms.opcional') }} </span>
                                </label>
                                <textarea name="obs" style="height: 5em;"></textarea>
                            </div>
                            <div class="campo confirmacao-campo">
                                <div class="form__checkbox">
                                    <input type="checkbox" id="ck-confirma" name="confirma" value="1">
                                    <label for="ck-confirma">{!! __('frontend.forms.confirmo_termos', ['siteurlpolicy' => $siteurlpolicy, 'siteurlterms' => $siteurlterms]) !!} </label>
                                </div>
                            </div>
                            <div class="campo confirmacao-campo">
                                <div class="form__checkbox">
                                    <input type="checkbox" id="ck-confirma-contrato" name="confirmacontrato" value="1">
                                    <label for="ck-confirma-contrato">{!! __('frontend.forms.confirmo_contrato') !!} </label>
                                </div>
                            </div>
                        </div>

                        <div class="a-centro">
                            <button name="continuar" type="submit" class="botao botao--comprar">
                                <i class="fas fa-angle-double-right"></i> {{ __('frontend.forms.continuar') }}
                            </button>
                        </div>
                        </div>
                    </form>
                </div>

            <div class="grid-xs--12 grid-md--3">
                <div id="resumo-ph" style="height: 0px;"></div>
                <div id="resumo" class="pacote__resumo resumo" style="width: 270px;">
                    <div class="pacote-fotos">
                        <div class="resumo__foto">
                            <img src="{{ $booking->package->getThumbnailUrl() }}" alt="{{ $booking->package->getTitle() }}">
                        </div>
                    </div>

                    <div class="resumo__conteudo">
                        <header class="resumo__header">
                            <h2 class="resumo__titulo">
                                <span class="resumo__nome">{{ $booking->package->getTitle() }}</span>
                                <span class="resumo__subnome">{{ $booking->package->getLocation() }}</span>
                            </h2>
                        </header>

                        <div id="resumo__valor-passageiro" class="valor-passageiro mt keep">
                            <span class="valor-passageiro__texto">Total:</span>
                            <span id="cotacao-geral" data-valor="1" data-simbolo="{{ $booking->currency->symbol }}" class="valor-passageiro__valor valor-passageiro__valor--principal">

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    @php ($initialCountry = 'auto')
    @php ($initialCountry = (isset($passenger->address_country) && strtolower($passenger->address_country) == 'br')?$passenger->address_country:'auto{
    <script type="text/javascript">
        $(document).ready(function () {
            catchFormSubmit($('#bookingForm'));
            initContactCountryFlag();
            function initContactCountryFlag() {
                let textContactInput = $('.phone-flag');
                let inputsLength = textContactInput.length;
                let initialCountry = '{{$initialCountry}}';
                for (let i = 0; i < inputsLength; i++) {
                    let iti = window.intlTelInput(textContactInput[i], {
                        initialCountry: 'BR',
                        separateDialCode: true,
                        autoHideDialCode: false,
                        preferredCountries: [
                            'br',
                            'de',
                            'us',
                        ]
                    });

                    let selectedCountry = iti.getSelectedCountryData().iso2;

                    if (selectedCountry == 'br') {
                        $(iti.a).inputmask(inputMaskContact);
                    } else {
                        $(iti.a).inputmask('remove');
                        $(iti.a).val($(iti.a).val().replace(/\D/g, ''));
                    }

                    textContactInput[i].addEventListener('countrychange', () => {
                        let selectedCountry = iti.getSelectedCountryData().iso2;

                        if (selectedCountry == 'br') {
                            $(iti.a).inputmask(inputMaskContact);
                        } else {
                            $(iti.a).inputmask('remove');
                            $(iti.a).val($(iti.a).val().replace(/\D/g, ''));
                        }
                    });
                }
            }

            @for ($key = 1; $key <= $booking->passengers; $key++)
                $('#passageiro_{{ $key }}_nome').on('keypress change blur', function (e) {
                    $('#field_passageiro_{{ $key }}_nome').text($(this).val());
                });

                $('#passageiro_{{ $key }}_ddi').on('keypress change blur', function (e) {
                    $('#field_passageiro_{{ $key }}_fone').text($(this).val() + '' + $('#passageiro_{{ $key }}_fone').val());
                });

                $('#passageiro_{{ $key }}_fone').on('keypress change blur', function (e) {
                    $('#field_passageiro_{{ $key }}_fone').text($('#passageiro_{{ $key }}_ddi').val() + '' + $(this).val());
                });

                $('#passageiro_{{ $key }}_data_nascimento').on('keypress change blur', function (e) {
                    $('#field_passageiro_{{ $key }}_data_nascimento').text($(this).val());
                });
            @endfor
        });
    </script>
@endpush
