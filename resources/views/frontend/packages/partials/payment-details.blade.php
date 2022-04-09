<div class="valor-passageiro" id="valor-passageiro">
    <strong class="valor-passageiro__valor valor-passageiro__valor--principal">
        {{ money($total, currency()) }}
    </strong>
    <span class="valor-passageiro__texto">{{ __('frontend.reservas.total_geral') }} </span>
</div>
@php 
$_cards = ['master','visa','amex'];
@endphp
<ul class="pacote-fp__lista">
    @foreach ($paymentMethods as $paymentMethod)
        @php ($installments = $paymentMethod->getRemainingInstallments($paymentMethod->pivot->max_installments) > 1 ? $paymentMethod->getRemainingInstallments($paymentMethod->pivot->max_installments) . ' × de ' : ' à vista: ')
        @if ($paymentMethod->isCredit() && $paymentMethod->isNational() && currency()->code == 'BRL')
            @foreach($_cards as $c)
                <li class="pacote-fp__item pacote-fp__item--{{$c}} pacote-fp__item" style="display: block;">
                    @if ($paymentMethod->firstInstallmentMustBeBillet())
                        <div class="pacote-fp__forma">
                            1 ({{ __("frontend.reservas.boleto")}}) de<strong class="pacote-fp__valor"> {{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong> + {{ $installments }}<strong class="pacote-fp__valor"> {{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong>
                        </div>
                    @else
                        <div class="pacote-fp__forma">
                            {{ $installments }}<strong class="pacote-fp__valor"> {{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong>
                        </div>
                    @endif
                </li>
            @endforeach
        @elseif ($paymentMethod->isCredit() && $paymentMethod->isInternational() && currency()->code != 'BRL')
            @foreach($_cards as $c)
                <li class="pacote-fp__item pacote-fp__item--{{$c}} pacote-fp__item" style="display: block;">
                    @if ($paymentMethod->firstInstallmentMustBeBillet())
                        <div class="pacote-fp__forma">
                            1 (boleto) de<strong class="pacote-fp__valor"> {{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong> + {{ $installments }}<strong class="pacote-fp__valor"> {{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong>
                        </div>
                    @else
                        <div class="pacote-fp__forma">
                            {{ $installments }}<strong class="pacote-fp__valor"> {{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong>
                        </div>
                    @endif
                </li>
            @endforeach
        @endif
        @if ($paymentMethod->isBillet() && currency()->code == 'BRL')
            @php ($class = $paymentMethod->code == 'boleto-bancario-bradesco' ? 'bradesco' : ($paymentMethod->code == 'boleto-bancario-itau' ? 'shopline' : null))
            <li class="pacote-fp__item pacote-fp__item--boleto pacote-fp__item--{{ $class }}">
                <div class="pacote-fp__forma">{{ __("frontend.reservas.a_vista")}}:
                    <strong class="pacote-fp__valor">
                    {{ money($paymentMethod->getInCashDiscountedValue($total), currency()) }}
                    </strong>
                    @if ($paymentMethod->hasInCashDiscount())
                        <span class="pacote-fp__desconto">-{{ percent($paymentMethod->pivot->discount/100) }}</span>
                    @endif
                </div>
                <div class="pacote-fp__forma">{{ $installments }}<strong class="pacote-fp__valor">
                @if($installments <=1)
                {{ money($paymentMethod->getInCashDiscountedValue($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments)), currency()) }}
                @else
                {{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}
                @endif
                </strong></div>

                
            </li>
        @endif
        @if ($paymentMethod->isDebit() && $paymentMethod->isNational() && currency()->code == 'BRL')
            @php ($class = $paymentMethod->code == 'transferencia-bancaria-bradesco' ? 'bradesco' : ($paymentMethod->code == 'transferencia-bancaria-itau' ? 'shopline' : null))
            <li class="pacote-fp__item pacote-fp__item--transferencia pacote-fp__item--{{ $class }}">
                <div class="pacote-fp__forma">
                    {{ $installments }}
                    <strong class="pacote-fp__valor">{{ money($paymentMethod->getInCashDiscountedValue($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments)), currency()) }}</strong>
                    @if ($paymentMethod->hasInCashDiscount() && $installments <=1)
                        <span class="pacote-fp__desconto">-{{ percent($paymentMethod->pivot->discount/100) }}</span>
                    @endif
                </div>

                <div class="pacote-fp__forma"></div>
            </li>
        @endif
    @endforeach
</ul>

{{-- <ul class="pacote-fp__lista">
    @foreach ($paymentMethods as $paymentMethod)
        <li class="pacote-fp__item pacote-fp__item--{{ $paymentMethod->code }}">
            <span class="esconde-vis">{{ $paymentMethod->name }} &ndash;</span>

            @if ($paymentMethod->firstInstallmentMustBeBillet())
                <div class="pacote-fp__forma">1 (boleto) de
                    <strong class="pacote-fp__valor">{{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong> + {{ $paymentMethod->getRemainingInstallments($paymentMethod->pivot->max_installments) }} &times; de
                    <strong class="pacote-fp__valor">{{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong>
                </div>
            @else
                <div class="pacote-fp__forma">
                    <strong class="pacote-fp__valor">{{ $paymentMethod->getRemainingInstallments($paymentMethod->pivot->max_installments) }} &times; de
                    <strong class="pacote-fp__valor">{{ money($paymentMethod->getInstallmentValue($total, $paymentMethod->pivot->max_installments), currency()) }}</strong>
                </div>
            @endif
	    </li>
    @endforeach
</ul> --}}
