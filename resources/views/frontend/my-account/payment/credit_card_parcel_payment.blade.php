@extends('frontend.template.clean')

@php
	$product 	= $booking->getProduct();
	$package 	= $booking->package;
	$taxes 		= 0;
	$viewOperations = true;
	switch($booking->status){
		case App\Enums\ProcessStatus::CANCELED:
		case App\Enums\ProcessStatus::REFUNDED:
			$viewOperations = false;
		break;
	}
	$longtripReady = 0;
	$bookingProduct	= null;	// Used for longtrip.
	if($product->getOfferType() == \App\Enums\OfferType::LONGTRIP){
		$bookingProduct 			= $booking->bookingProducts->where('product_type', 'App\Models\LongtripBoardingLocation')->first();
		if($bookingProduct){
			$longtripReady = 1;
		}
		$longtripBoardingLocation 	= $bookingProduct->getProduct();
		$longtripRoute 				= $longtripBoardingLocation->longtripRoute;
		$accommodationTypeId 		= $booking->bookingProducts->where('product_type', 'App\Models\LongtripAccommodationsPricing')->first()->getProduct()->longtrip_accommodation_type_id;
		$longtripAccommodation 		= app(\App\Repositories\OfferRepository::class)->getLongtripRouteAccommodationsType($longtripBoardingLocation->id, $accommodationTypeId);
		$longtripAccommodationHotels = $longtripAccommodation->longtripAccommodationHotels;
	}
@endphp

@section('content')
    @php
        $installments = $booking->bookingBills()->where("payment_method_id", $bookingBill->payment_method_id)->count();
        $installments = $booking->bookingBills()->where("payment_method_id", $bookingBill->payment_method_id)->count();
    @endphp
    <div class="largura-site2 ma">
        <header class="pacote__header">
            <span class="pacote__gravata"> {{ $booking->package->getExtendedTitle() }} </span>
            <span class="pacote__nome">{{ __('frontend.forms.do_payment') }}</span>
            <span class="pacote__subnome">{{__('frontend.reservas.localizador')}} {{ $booking->id }}</span>
        </header>
        <div class="corpo-texto">
            <p>
                {{__('frontend.reservas.confira2')}}
            </p>
        </div>
        <br>

        <div class="box mb">
            <header class="box__header">
                <h3 class="box__titulo">{{ __('frontend.reservas.value_details') }}</h3>
            </header>
            <div class="box__conteudo">
                <table class="tabela tabela--valores">

                        <thead>
                            <th>{{ __('frontend.reservas.servicos_adicionais') }}</th>
                            <th>{{ __('frontend.reservas.valor') }}</th>
                        </thead>

                    <tbody>
                        @foreach ($booking->bookingPassengers as $bookingPassenger)
                            <tr>
                                <td class="td--passageiro a-esq" colspan="3">
                                    <strong>{{ mb_strtoupper($bookingPassenger->name) }}</strong>
                                </td>
                            </tr>
                            @if (empty($booking->getDates()))
                                <tr class="tr--servico">
                                    <td class="a-esq">
                                        <span class="tr--servico__icone">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        {{ mb_strtoupper($booking->getProductName()) }}
                                    </td>
                                    <td class="td--valor a-dir">{{ money($booking->getProductPrice(), currency(), $booking->offer->currency) }}</td>
                                    @php
                                        $taxes += ($booking->getProductPrice() - $booking->getProductPriceNet());
                                    @endphp
                                </tr>
                            @else
                            @php
                            $_products	= $booking->getProductByDates($booking->getDates());
                        @endphp
                            @if($_products)
                                @foreach ($_products as $product)
                                        <tr class="tr--servico">
                                            <td class="a-esq">
                                                <span class="tr--servico__icone">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                                {{ mb_strtoupper($product->getTitle()) }} {{ ($product->date->format("d/m/Y")) }}
                                            </td>
                                            <td class="td--valor a-dir">{{ money($product->getPrice(), currency(), $booking->offer->currency) }}</td>
                                            @php
                                                $taxes += ($product->getPrice() - $product->getPriceNet());
                                            @endphp
                                        </tr>
                                @endforeach
                            @endif
                        @endif
                            @foreach ($bookingPassenger->bookingPassengerAdditionals ?? [] as $bookingPassengerAdditional)
                                <tr class="tr--servico">
                                    <td class="a-esq">
                                        <span class="tr--servico__icone">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        {{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }}
                                    </td>
                                    <td class="td--valor a-dir">{{ money($bookingPassengerAdditional->additional->getPrice(), currency(), $booking->offer->currency) }}
                                    </td>
                                    @php
                                        $taxes += ($bookingPassengerAdditional->additional->getPrice() - $bookingPassengerAdditional->additional->getPriceNet());
                                    @endphp
                                </tr>
                            @endforeach
                        @endforeach

                        <tr class="tr--total">
                            <td class="a-dir">{{ __('frontend.reservas.total_geral_servicos') }}</td>
                            <td class="td--valor a-dir">
                                <span class="valor valor--principal campo-valor--principal" data-valor-principal="{{ money($booking->total, currency()) }}">
                                    {{ money($booking->total, currency(), $booking->offer->currency) }}
                                </span>
                            </td>
                        </tr>

                        @if($booking->tax >0)
                            <tr class="tr--detail">
                                <td class="a-esq">
                                    {{ __('frontend.reservas.process_tax') }}
                                </td>
                                <td class="td--valor a-dir">
                                    + {{ money($booking->tax , currency(), $booking->offer->currency) }}
                                </td>
                            </tr>
                        @endif

                        @if($booking->discount >0)
                            <tr class="tr--detail ">
                                <td class="a-esq">
                                    {{ __('frontend.reservas.discounts') }}
                                </td>
                                <td class="td--valor a-dir">
                                    - {{ money($booking->discount , currency(), $booking->offer->currency) }}
                                </td>
                            </tr>
                        @endif

                        @if($booking->discount_promocode >0)
                            <tr class="tr--detail ">
                                <td class="a-esq">
                                    {{ __('frontend.reservas.promo_discount') }}
                                </td>
                                <td class="td--valor a-dir">
                                    - {{ money($booking->discount_promocode, currency(), $booking->offer->currency) }}
                                </td>
                            </tr>
                        @endif

                        @if($booking->discount_promocode_provider >0)
                            <tr class="tr--detail">
                                <td class="a-esq">
                                    {{ __('frontend.reservas.promo_discount') }}
                                </td>
                                <td class="td--valor a-dir">
                                    - {{ money($booking->discount_promocode_provider, currency(), $booking->offer->currency) }}
                                </td>
                            </tr>
                        @endif

                        <tr class="tr--total">
                            <td class="a-dir">{{ __('frontend.reservas.total_pagar') }}</td>
                            <td class="td--valor a-dir">
                                <span class="valor valor--principal campo-valor--principal" data-valor-principal="{{ money($booking->total, currency()) }}">
                                    {{ money($booking->total, currency(), $booking->offer->currency) }}
                                </span>
                            </td>
                        </tr>

                        <tr class="tr--detail">
                                <td class="a-esq">
                                    {!! __('frontend.reservas.tax_fees_process') !!}
                                </td>
                            <td class="td--valor a-dir">
                                {{money($taxes, currency(), $booking->offer->currency)}}
                                <span class="valor-servicos campo-taxa-servicos">  </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="form form--auto-style">
        <div class="box">
            <header class="box__header">
                <h2 class="box__titulo">
                    {{ __('frontend.reservas.charge_payment') }}
                </h2>
            </header>
            <div class="box__conteudo">
                <table class="tabela tabela--pagamentos mb">
                    <tbody>
                        <tr>
                            <th>{{ __('frontend.reservas.parcela_formapag') }}</th>
                            <th>
                                @switch($bookingBill->paymentMethod->code)
                                    @case(\App\Enums\PaymentMethod::PM_CODE_CREDIT_CARD)
                                        {{ __('frontend.reservas.valor_total') }}
                                    @break;
                                    @case(\App\Enums\PaymentMethod::PM_CODE_CREDIT_CARD_RECURRENCE)
                                        {{ __('frontend.reservas.valor_parcela') }}
                                    @break;
                                @endswitch

                            </th>
                        </tr>
                        <tr>

                                @switch($bookingBill->paymentMethod->code)
                                    @case(\App\Enums\PaymentMethod::PM_CODE_CREDIT_CARD)
                                    <td class="a-esq"> {{__('frontend.geral.rodape_cartoes')}} - ({{ $installments }} x {{ __('frontend.reservas.titulo_de') }} {{ money($bookingBill->total, $bookingBill->currency) }})</td>
                                    <td class="td--valor">
                                        <strong class="valor">{{ money($bookingBill->total * $installments, $bookingBill->currency) }}</strong>
                                    </td>
                                    @break;
                                    @case(\App\Enums\PaymentMethod::PM_CODE_CREDIT_CARD_RECURRENCE)
                                    <td class="a-esq"> {{__('frontend.geral.rodape_cartoes')}} - {{ __('frontend.reservas.titulo_parcelas') }} {{ $bookingBill->installment }}</td>
                                    <td class="td--valor">
                                        <strong class="valor">{{ money($bookingBill->total, $bookingBill->currency) }}</strong>
                                    </td>
                                    @break;
                                @endswitch


                        </tr>
                    </tbody>
                </table>
            </div>
        </div><br>

               <form action="{{ route('frontend.my-account.bookings.reservation.do-payment', ['booking' => $booking, 'bookingBill' => $bookingBill]) }}" method="post" accept-charset="utf-8">
                @csrf


                <input type='hidden' name='formapag' value='{{ $bookingBill->payment_method_id }}' />
                <input type='hidden' name='parcelas' value='{{ $installments }}' />
                @include("frontend.my-account.payment.credit_card_form", ['paymentCardClass' => 'pagamento-cartao-ok'])
            </div>
        </div>
                <div class="a-centro">
                    <button class="botao botao--comprar botao--processar" name="finalizar" type="submit">
                        <i class="fas fa-check"></i> {{ __('frontend.forms.do_payment') }}
                    </button>

                    <br /><br />
                    <div class="corpo-texto">
                        <a class="icone text-center" href="{{ route('frontend.my-account.bookings.show', $booking) }}">{{ __('frontend.geral.voltar') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="/frontend/js/vue.min.js"></script>
@endpush
