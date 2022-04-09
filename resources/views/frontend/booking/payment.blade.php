@extends('frontend.template.clean')

@section('content')
@php
    $taxes = 0;
    $showCoefficient = 0;
@endphp
    <div class="largura-site2 ma">
        <header class="pacote__header">
            <span class="pacote__gravata">
                {{ __('frontend.reservas.compra_pacote') }}
            </span>
            <h1 class="pacote__titulo">
                <span class="pacote__nome">{{ $booking->getName() }}</span>
            </h1>
        </header>

        <div class="form form--auto-style">
            <form action="{{ route('frontend.booking.store') }}" method="post" accept-charset="utf-8">
                @csrf

                <div class="box mb">
                    <header class="box__header">
                        <h2 class="box__titulo">{{ __('frontend.reservas.servicos_serem_contratados') }}</h2>
                    </header>

                    <div class="box__conteudo">
                        <table class="tabela tabela--valores">
                            <tbody>
                                <tr>
                                    <th class="th--servicos">{{ __('frontend.reservas.servicos_adicionais') }}</th>
                                    <th class="th--valores">{{ __('frontend.reservas.valor') }}</th>
                                </tr>

                                @foreach ($booking->bookingPassengers as $bookingPassenger)
                                    <tr>
                                        <td class="td--passageiro a-esq" colspan="2">
                                            <strong>{{ mb_strtoupper($bookingPassenger->name) }}</strong>
                                        </td>
                                    </tr>
                                    @if (empty($booking->getDates()))
                                        @if ($booking->offer->isLongtrip())
                                            @foreach ($booking->bookingProducts as $bookingProduct)
                                                <tr class="tr--servico">
                                                    <td class="a-esq">
                                                        <span class="tr--servico__icone">
                                                            <i class="fas fa-check-circle"></i>
                                                        </span>
                                                        {{ mb_strtoupper($bookingProduct->getTitle()) }}
                                                    </td>
                                                    <td class="td--valor a-dir">{{ money($bookingProduct->getPrice(), $booking->currency, $booking->offer->currency) }}</td>
                                                </tr>
                                                @php
                                                    $taxes += moneyFloat($bookingProduct->getProduct()->getPriceSaleCoefficientValue(), $booking->currency, $booking->offer->currency);
                                                @endphp
                                            @endforeach
                                        @else
                                            <tr class="tr--servico">
                                                <td class="a-esq">
                                                    <span class="tr--servico__icone">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                    {{ mb_strtoupper($booking->getProductName()) }}
                                                </td>
                                                <td class="td--valor a-dir">{{ money($booking->getProductPrice(), $booking->currency, $booking->offer->currency) }}
                                                @if($showCoefficient)
                                                    <br />{{moneyFloat($booking->getProductPriceSaleCoefficientValue(), $booking->currency, $booking->offer->currency)}}
                                                @endif
                                                </td>
                                            </tr>
                                            @php
                                                $taxes += moneyFloat($booking->getProductPriceSaleCoefficientValue(), $booking->currency, $booking->offer->currency);
                                            @endphp
                                        @endif
                                    @else
                                        @foreach ($booking->getDates() as $date)
                                            <tr class="tr--servico">
                                                <td class="a-esq">
                                                    <span class="tr--servico__icone">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                    {{ mb_strtoupper($booking->getProductName($date)) }}
                                                </td>
                                                <td class="td--valor a-dir">{{ money($booking->getProductPrice($date), $booking->currency, $booking->offer->currency) }}
                                                @if($showCoefficient)
                                                </br> {{moneyFloat($booking->getProduct()->getPriceSaleCoefficientValue($date), $booking->currency, $booking->offer->currency)}}
                                                @endif

                                                </td>
                                            </tr>
                                            @php
                                                $taxes += moneyFloat($booking->getProduct()->getPriceSaleCoefficientValue($date), $booking->currency, $booking->offer->currency);
                                            @endphp
                                        @endforeach
                                    @endif
                                    @foreach ($bookingPassenger->bookingPassengerAdditionals ?? [] as $bookingPassengerAdditional)
                                        <tr class="tr--servico">
                                            <td class="a-esq">
                                                <span class="tr--servico__icone">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                                {{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }}
                                            </td>
                                            <td class="td--valor a-dir">{{ money($bookingPassengerAdditional->additional->getPrice(), $booking->currency, $bookingPassengerAdditional->additional->currency) }}
                                            @if($showCoefficient)
                                            </br> {{moneyFloat($bookingPassengerAdditional->additional->getPriceSaleCoefficientValue(), $booking->currency, $bookingPassengerAdditional->additional->currency)}}
                                            @endif
                                            </td>
                                        </tr>
                                        @php
                                            $taxes += moneyFloat($bookingPassengerAdditional->additional->getPriceSaleCoefficientValue(), $booking->currency, $bookingPassengerAdditional->additional->currency) ;
                                        @endphp
                                    @endforeach
                                @endforeach
                                <tr class="tr--total">
                                    <td class="a-dir">{{ __('frontend.reservas.total_geral_servicos') }}</td>
                                    <td class="td--valor a-dir">
                                        <span class="valor" data-valor-principal="{{ money($booking->total, $booking->currency) }}">
                                            {{ money($booking->subtotal, $booking->currency) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="tr--detail tr--operator-taxes campos-calculos esconde">
                                    <td class="a-esq">
                                        <div class='operator-title reseta-calculo'></div>
                                    </td>
                                    <td class="td--valor a-dir">
                                        + <span class="operator-value reseta-calculo"></span>
                                    </td>
                                </tr>
                                <tr class="tr--detail tr--discount-in-cash campos-calculos esconde">
                                    <td class="a-esq">
                                        <div class='dic-title reseta-calculo'></div>
                                    </td>
                                    <td class="td--valor a-dir">
                                        - <span class="dic-value reseta-calculo"></span>
                                    </td>
                                </tr>

                                @if($promocode != null)
                                <tr class="tr--detail campo-desconto ">
                                    <td>{{$promocode->getName() }}: {{ __('frontend.reservas.promocode_desconto_de') }}
                                        @switch($promocode->payment_method_id)
                                            @case(29)
                                            <span class="valor--desconto-percentual"></span>
                                            @break;
                                        @endswitch
                                    </td>
                                    <td class="td--valor a-dir">- <span class="valor--desconto"> {{ money($promocode->getDiscount(), $booking->currency, $promocode->currency) }} </span></td>
                                </tr>
                                @endif
                                <tr class="tr--total">
                                    <td class="a-dir">{{ __('frontend.reservas.total_pagar') }}</td>
                                    <td class="td--valor a-dir">
                                        <span class="valor valor--principal campo-valor--principal" data-valor-principal="{{ money($booking->total, $booking->currency) }}">
                                            {{ money($booking->total, $booking->currency) }}
                                        </span>
                                    </td>
                                </tr>

                                <tr class="tr--detail">
                                    <td class="a-esq" colspan=2>
                                        {{ __('frontend.reservas.fee_taxes_info', ['taxes' => money($taxes)]) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="box mb formas-pagamento">
                    <header class="box__header">
                        <h2 class="box__titulo">{{ __('frontend.reservas.escolha_formapag') }}</h2>
                    </header>

                    <div class="box__conteudo">
                        <p class="a-centro mb">
                            <small><strong>{{ __('frontend.reservas.taxas_inclusos') }}</strong></small>
                        </p>

                        <ul class="lista-pagamento">
                            @foreach ($paymentMethods as $paymentMethod)
                                @if ($booking->isForeigner() && $paymentMethod->isNational())
                                    @continue
                                @endif

                                @if (!$booking->isForeigner() && $paymentMethod->isInternational())
                                    @continue
                                @endif

                                @php
                                    ($installments = $paymentMethod->getBookingInstallments($booking))
                                @endphp

                                @if (count($installments) == 0)
                                    @continue
                                @endif

                                @php
                                    $gatewayServiceValue    = $gatewayServiceTotal = $discountDicValue = $discountDicTotal = 0;
                                    $pivotData              = $paymentMethod->pivot;
                                    $gatewayPercent         = $pivotData->tax;
                                    $discountDicPercent     = $pivotData->discount;
                                    if($gatewayPercent>0){
                                        $gatewayServiceValue = ($booking->total * ($gatewayPercent/100));
                                        $gatewayServiceTotal = $gatewayServiceValue + $booking->total;
                                    }
                                    // Promocode Check
                                    if($booking->promocode != null){
                                        if($booking->promocode->cancelsCashDiscount()){
                                            $discountDicPercent = 0;
                                        }
                                    }
                                    if($discountDicPercent >0){
                                        $discountDicValue = ($booking->total * ($discountDicPercent/100));
                                        $discountDicTotal = $booking->total - $discountDicValue;
                                    }
                                @endphp

                                <li id="forma-pagamento-{{ $paymentMethod->id }}" class="lista-pagamento__item @if($paymentMethod->isCredit()) fp-credito @elseif($paymentMethod->isBillet()) fp-boleto @elseif($paymentMethod->isDebit()) fp-debito @endif">
                                    <div class="lista-pagamento__grid">
                                        <div
                                            class="lista-pagamento__checkbox rd-fp"
                                            data-tipo=""
                                            data-booking-total="{{ decimal($booking->total) }}"
                                            data-taxa-servico-moeda="{{ $booking->currency->code }}"
                                            data-taxa-servico="{{ __('frontend.reservas.gateway_tax', ['gateway'=> $paymentMethod->name, 'tax' => decimal($gatewayPercent)] )}}"
                                            data-taxa-servico-valor="{{ decimal($gatewayServiceValue) }}"
                                            data-dic="{{ __('frontend.reservas.discount_in_cash', ['discount' => $discountDicPercent]) }}"
                                            data-dic-value="{{ decimal($discountDicValue) }}"
                                            data-gateway-title="{{$paymentMethod->name}}"
                                            data-valor-pricipal-servico="{{decimal($gatewayServiceTotal)}}"
                                            data-valor-pricipal-dic="{{decimal($discountDicTotal)}}"
                                        >
                                            <input
                                                type="radio"
                                                class="rd-formapag rd-formapag"
                                                data-tipo=""
                                                data-forma-pagamento="{{ $paymentMethod->id }}"
                                                name="formapag"
                                                value="{{ $paymentMethod->id }}"
                                            />
                                            <label for="rd-{{ $paymentMethod->id }}-{{ $paymentMethod->code }}">
                                                {{ $paymentMethod->name }}
                                            </label>
                                        </div>

                                        <div class="lista-pagamento__parcelas">
                                            <select
                                                name="parcelas"
                                                class="lista-pagamento__parcelas-select"
                                                data-target="rd-{{ $paymentMethod->id }}-{{ $paymentMethod->code }}"
                                                data-formapag="{{ $paymentMethod->id }}"
                                                data-tipo="{{ $paymentMethod->type }}"
                                                data-parent="forma-pagamento-{{ $paymentMethod->id }}"
                                                data-processador="@if ($paymentMethod->isOffline()) offline @else processador @endif"
                                                data-desabilita-dados-cartao="@if ($paymentMethod->code == 'paypal') 1 @else 0 @endif"
                                                disabled
                                            >
                                                <option value="">{{ __('frontend.forms.parcelamento_formapag') }}</option>
                                                @foreach ($installments as $key => $installment)
                                                @php
                                                    if($loop->index >0){
                                                        $discountDicTotal = $booking->total;
                                                        $discountDicValue = 0;
                                                    }
                                                @endphp
                                                    <option
                                                        id="parcelas{{ $paymentMethod->code }}-{{ $paymentMethod->id }}-{{ $key }}"
						                                value="{{ $key }}"
                                                        data-booking-total="{{ decimal($booking->total) }}"
                                                        data-taxa-servico-moeda="{{ $booking->currency->code }}"
                                                        data-taxa-servico="{{ __('frontend.reservas.gateway_tax', ['gateway'=> $paymentMethod->name, 'tax' => decimal($gatewayPercent)] )}}"
                                                        data-taxa-servico-valor="{{ decimal($gatewayServiceValue) }}"
                                                        data-dic="{{ __('frontend.reservas.discount_in_cash', ['discount' => $discountDicPercent]) }}"
                                                        data-dic-value="{{ decimal($discountDicValue) }}"
                                                        data-gateway-title="{{$paymentMethod->name}}"
                                                        data-valor-pricipal-servico="{{decimal($gatewayServiceTotal)}}"
                                                        data-valor-pricipal-dic="{{decimal($discountDicTotal)}}"
                                                    >
                                                        {{ $paymentMethod->getTranslatedLabel($booking, $paymentMethod, $installment) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="lista-pagamento__conteudo"></div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="passo__servicos-principais-abrir formas-pagamento-abrir">
                            <span class="passo__servicos-principais-abrir-label formas-pagamento-abrir__label">
                                {{ __('frontend.forms.alterar_formapag') }}
                            </span>
                            <span class="passo__servicos-principais-abrir-icone formas-pagamento-abrir__icone">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div id='moeda-compra' class='esconde'>{{ $booking->currency }}</div>

                <div class="box pagamento-cartao">
                    <header class="box__header">
                        <h2 class="box__titulo">
                            {{ __('frontend.forms.informe_cartao') }}
                        </h2>
                    </header>
                    <div class="box__conteudo">
                        <div class="corpo-texto mb">
                        <p>
                            {!! __('frontend.forms.nenhum_dado') !!}
                        </p>
                        </div>

                        <div class="pagamento-cartao__form">
                            <fieldset class="grid">
                                <div class="campo grid-xs--12 grid-sm--8">
                                    <label>{{ __('frontend.forms.numero_cartao') }}<span>{{ __('frontend.forms.apenas_numeros') }}</span></label>
                                    <input type="tel" class="mask-cartao" name="ct-numero" autocomplete="off" value="">
                                </div>
                                <div class="campo grid-xs--12 grid-sm--4">
                                    <label>{{ __('frontend.forms.cvc_cartao') }}</label>
                                    <input type="tel" name="ct-cvc" class="mask-cvc" autocomplete="off" value="">
                                </div>
                            </fieldset>
                            <fieldset class="grid">
                                <div class="campo grid-xs--12 grid-sm--8">
                                    <label>{{ __('frontend.forms.nome_titular_cartao') }} <span>{{ __('frontend.forms.como_impresso') }}</span></label>
                                    <input type="text" name="ct-nome" autocomplete="off" value="">
                                </div>
                                <div class="campo grid-xs--12 grid-sm--4">
                                    <label>{{ __('frontend.forms.validade_cartao') }}</label>
                                    <div class="grid">
                                        <div class="grid-xs--6">
                                            <select name="ct-mes">
                                                <option value>{{ __('frontend.forms.mes') }}</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="grid-xs--6">
                                            <select name="ct-ano">
                                                <option value>{{ __('frontend.forms.ano') }}</option>
                                                @for($i = 0; $i <= 15; $i++)
                                                    @php ($year = date('Y',strtotime("now + $i year")))
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="a-centro">
                    <button class="botao botao--comprar botao--processar" name="finalizar" type="submit">
                        <i class="fas fa-check"></i>{{ __('frontend.forms.confirmar_compra') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="/frontend/js/vue.min.js"></script>
@endpush
