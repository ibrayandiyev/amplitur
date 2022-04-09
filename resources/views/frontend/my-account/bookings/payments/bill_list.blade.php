<div id="agenda-pagamento" class="box mb">
    <header class="box__header">
        <h3 class="box__titulo">{{ __('frontend.reservas.agenda_pagamentos') }}</h3>
    </header>

    <div class="box__conteudo corpo-texto">
        <p>
            {{ __('frontend.conta.status') }}:
            @php
                $paymentStatusClass 		= 'status-pendenteconf';
                $firstBillNotPaid           = 1;
                switch($booking->payment_status){
                    default:
                    case App\Enums\ProcessStatus::PENDING:
                        $paymentStatusClass = 'status-pendenteconf';
                        break;
                    case App\Enums\ProcessStatus::CONFIRMED:
                        $paymentStatusClass = 'status-pago';
                        break;
                    case App\Enums\ProcessStatus::CANCELED:
                        $paymentStatusClass = 'status-cancelada';
                        break;
                    case App\Enums\ProcessStatus::REFUNDED:
                        $paymentStatusClass = 'status-estornada';
                        break;
                    case App\Enums\ProcessStatus::BLOCKED:
                        $paymentStatusClass = 'status-bloqueada';
                        break;
                }
            @endphp
            <span class="box-status {{ $paymentStatusClass }}">{{ __("resources.process-statues.". $booking->payment_status) }}</span>
        </p><br>

        <table class="tabela tabela--pagamentos mb">
            <tbody>
                <tr>
                    <th>{{ __('frontend.reservas.parcela_formapag') }}</th>
                    <th>{{ __('frontend.reservas.valor') }}</th>
                    <th>{{ __('frontend.reservas.vencimento') }}</th>
                    <th>{{ __('frontend.reservas.acoes') }}</th>
                    <th>{{ __('frontend.reservas.pagamento') }}</th>
                </tr>
                @foreach ($booking->bookingBills->sortBy("installment") as $key => $bookingBill)
                @php
                $itauTitle = null;
                @endphp
                    <tr>
                        <td class="a-esq">{{ $bookingBill->paymentMethod->name }} - {{ __('frontend.reservas.titulo_parcelas') }} {{ $bookingBill->installment }}</td>
                        <td class="td--valor">
                            <strong class="valor">{{ money($bookingBill->total, $bookingBill->currency->code) }}</strong>
                        </td>
                        <td>{{ $bookingBill->expiresAtLabel }}</td>
                        <td>
                        @if($viewOperations)
                            @if(!$bookingBill->isPaid())
                                @switch($bookingBill->paymentMethod->code)
                                    @case(\App\Enums\PaymentMethod::PM_CODE_ITAU_BILLET)
                                    @php
                                        $itauTitle = __('frontend.reservas.gerar_boleto');
                                    @endphp
                                    @case(\App\Enums\PaymentMethod::PM_CODE_ITAU_TRANSFER)
                                    @php
                                        $itauTitle = ($itauTitle == null)?__('frontend.reservas.acess_internetbank'):$itauTitle;
                                    @endphp
                                        @if ($bookingBill->isExpired())
                                            <strong class="ultimas-unidades">{{ __('frontend.reservas.boleto_atraso') }}</strong> <br/>
                                        @endif
                                        <form action="{{ route('frontend.my-account.bookings.generateBilletBill', [$booking, $bookingBill]) }}" id="shopline_{{ $key }}" target="_blank" method="post" accept-charset="utf-8">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                            <input type="hidden" name="booking_bill_id" value="{{ $bookingBill->id }}">
                                            <button type="submit" class="icone pagar">{{$itauTitle}}</button>
                                        </form>
                                    @break;
                                    @case(\App\Enums\PaymentMethod::PM_CODE_INVOICE)
                                        <a class="icone boleto" href="{{ route('frontend.my-account.bookings.showInvoice', ['booking' => $bookingBill->booking_id, $bookingBill->id])}}" rel="external">
                                        {{ __('frontend.reservas.visualizar') }}
                                        </a>

                                    @break;
                                    @case(\App\Enums\PaymentMethod::PM_TYPE_PAYPAL)
                                        @include('frontend.my-account.bookings.payments.paypal_parcel')
                                    @break;
                                    @case(\App\Enums\PaymentMethod::PM_CODE_CREDIT_CARD)
                                        @if($bookingBill->installment == 1)
                                            @include('frontend.my-account.bookings.payments.credit_card_parcel')
                                        @endif
                                    @break;
                                    @case(\App\Enums\PaymentMethod::PM_CODE_CREDIT_CARD_RECURRENCE)
                                        @if($bookingBill->installment == 1 && !$bookingBill->isPaid())
                                            @include('frontend.my-account.bookings.payments.credit_card_parcel')
                                            @php
                                            $firstBillNotPaid = 0;
                                            @endphp
                                        @else
                                            @if($firstBillNotPaid == 1)
                                                @include('frontend.my-account.bookings.payments.credit_card_parcel')
                                            @endif
                                        @endif
                                    @break;

                                @endswitch
                            @endif
                        @endif
                        </td>
                        <td class="pago-{{ $bookingBill->isPaid() }}">

                        {{ __("resources.process-statues.". $bookingBill->status) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($booking->canChangePaymentMethod())
            <div class="a-centro">
                <a href="{{ route('frontend.my-account.bookings.changePaymentMethod', $booking) }}" class="botao botao--submit botao-formas-pagamento" style="color: white;">{{ __('frontend.forms.alterar_formapag') }}</a>
            </div>
        @endif
    </div>
</div>
