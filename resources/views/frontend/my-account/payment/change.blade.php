@extends('frontend.template.clean')

@section('content')
    <div class="largura-site2 ma">
        <header class="pacote__header">
            <span class="pacote__gravata">
                {{ __('frontend.forms.alterar_formapag') }}
            </span>
        </header>

        <div class="form form--auto-style">
            <form action="{{ route('frontend.my-account.bookings.doChangePaymentMethod', $booking) }}" method="post" accept-charset="utf-8">
                @csrf
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
                                
                                @php ($installments = $paymentMethod->getBookingInstallments($booking))

                                @if (count($installments) == 0)
                                    @continue
                                @endif

                                <li id="forma-pagamento-{{ $paymentMethod->id }}" class="lista-pagamento__item @if($paymentMethod->isCredit()) fp-credito @elseif($paymentMethod->isBillet()) fp-boleto @elseif($paymentMethod->isDebit()) fp-debito @endif">
                                    <div class="lista-pagamento__grid">
                                        <div
                                            class="lista-pagamento__checkbox rd-fp"
                                            data-tipo=""
                                            data-taxa-servico-moeda=""
                                            data-taxa-servico=""
                                            data-taxa-servico-valor=""
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
                                                <option value="">{{ __('frontend.reservas.plano_parcelamento') }}</option>
                                                @foreach ($paymentMethod->getBookingInstallments($booking) as $key => $installment)
                                                    <option
                                                        id="parcelas{{ $paymentMethod->code }}-{{ $paymentMethod->id }}-{{ $key }}"
						                                value="{{ $key }}"
                                                        data-taxa-servico="0"
                                                        data-taxa-servico-valor="{{ $booking->total }}"
                                                        data-desconto-valor="{{ $booking->discount }}"
                                                        data-desconto-percentual="{{ $booking->discount * 100 }}"
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
                                {{ __('frontend.reservas.ver_formapag') }}
                            </span>
                            <span class="passo__servicos-principais-abrir-icone formas-pagamento-abrir__icone">
                                <i class="fas fa-angle-down"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div id='moeda-compra' class='esconde'>{{ $booking->currency }}</div>

                @include("frontend.my-account.payment.credit_card_form")

                <div class="a-centro">
                    <button class="botao botao--comprar botao--processar" name="finalizar" type="submit">
                        <i class="fas fa-check"></i> {{ __('frontend.forms.confirmar_compra') }}
                    </button>

                    <br /><br />

                    <div class="corpo-texto">
                        <a class="icone" href="{{ route('frontend.my-account.bookings.show', $booking) }}">{{ __('frontend.geral.voltar') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="/frontend/js/vue.min.js"></script>
@endpush
