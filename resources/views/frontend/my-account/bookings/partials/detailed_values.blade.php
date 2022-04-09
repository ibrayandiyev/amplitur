@php
    $bookingCurrencyCode = $booking->currency->code;
    $taxes = 0;   // Used to show the Fees in the end of details.
@endphp

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
                        @php
                            $product = $booking->getProduct();
                        @endphp
                        <tr class="tr--servico">
                            <td class="a-esq">
                                <span class="tr--servico__icone">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                {{ mb_strtoupper($booking->getProductName()) }}
                            </td>
                            <td class="td--valor a-dir">
                            {{ $product->getExtendedValuePriceAttribute() }}</td>
                            @php
                                $taxes += ($product->getPrice() - $product->getPriceNet());
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
                                            {{ mb_strtoupper($product->getTitle()) }}

                                            @if ( $booking->offer->isHotel() ) -
                                            {{ __('frontend.pacotes.dayly_from') }} {{ ($product->date->format("d/m")) }} {{ __('frontend.pacotes.dayly_to') }} {{ ($product->date->addDay()->format("d/m/Y")) }}
                                            @endif
                                        </td>
                                        <td class="td--valor a-dir">{{ $product->getPriceCurrencyWOConvertedLabel() }}</td>
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
                            <td class="td--valor a-dir">{{ $bookingPassengerAdditional->getPriceCurrencyWOConvertedLabel() }}
                            </td>
                            @php
                                $taxes += ($bookingPassengerAdditional->getPrice() - $bookingPassengerAdditional->getPriceNet());
                            @endphp
                        </tr>
                    @endforeach
                @endforeach

                <tr class="tr--total">
                    <td class="a-dir">{{ __('frontend.reservas.total_geral_servicos') }}</td>
                    <td class="td--valor a-dir">
                        <span class="valor valor--principal campo-valor--principal" data-valor-principal="{{ money($booking->total, currency()) }}">
                            {{ money($booking->subtotal, $booking->currency->code) }}
                        </span>
                    </td>
                </tr>

                @if($booking->tax >0)
                    <tr class="tr--detail">
                        <td class="a-esq">
                            {{ __('frontend.reservas.process_tax') }}
                        </td>
                        <td class="td--valor a-dir">
                            + {{ money($booking->tax , $booking->currency->code) }}
                        </td>
                    </tr>
                @endif

                @if($booking->discount >0)
                    <tr class="tr--detail ">
                        <td class="a-esq">
                            {{ __('frontend.reservas.discounts') }}
                        </td>
                        <td class="td--valor a-dir">
                            - {{ money($booking->discount , $booking->currency->code) }}
                        </td>
                    </tr>
                @endif

                @if($booking->discount_promocode >0)
                    <tr class="tr--detail ">
                        <td class="a-esq">
                            {{ __('frontend.reservas.promo_discount') }}
                        </td>
                        <td class="td--valor a-dir">
                            - {{ money($booking->discount_promocode, $booking->currency->code) }}
                        </td>
                    </tr>
                @endif

                @if($booking->discount_promocode_provider >0)
                    <tr class="tr--detail">
                        <td class="a-esq">
                            {{ __('frontend.reservas.promo_discount') }}
                        </td>
                        <td class="td--valor a-dir">
                            - {{ money($booking->discount_promocode_provider, $booking->currency->code) }}
                        </td>
                    </tr>
                @endif

                <tr class="tr--total">
                    <td class="a-dir">{{ __('frontend.reservas.total_pagar') }}</td>
                    <td class="td--valor a-dir">
                        <span class="valor valor--principal campo-valor--principal" data-valor-principal="{{ money($booking->total, currency()) }}">
                            {{ money($booking->total , $booking->currency->code) }}
                        </span>
                    </td>
                </tr>

                <tr class="tr--detail">
                        <td class="a-esq">
                            {!! __('frontend.reservas.tax_fees_process') !!}
                        </td>
                    <td class="td--valor a-dir">
                        {{money($taxes, $booking->currency->code)}}
                        <span class="valor-servicos campo-taxa-servicos">  </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
