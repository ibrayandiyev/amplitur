@php 
    $class = "pagamento-cartao";
    if(isset($paymentCardClass)){
        $class = $paymentCardClass;
    }
@endphp
<div class="box {{ $class }}">
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
                    <label>{{ __('frontend.forms.numero_cartao') }} <span>{{ __('frontend.forms.apenas_numeros') }}</span></label>
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