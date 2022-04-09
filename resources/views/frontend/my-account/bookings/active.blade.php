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
                        <h2 class="pagina__subtitulo">{{ __('frontend.conta.minhas_viagens_ativas') }}</h2>
                        <table class="tabela tabela--reservas mb">
                            <tbody>
                                <tr>
                                    <th>{{ __('frontend.conta.detalhes') }}</th>
                                    <th>{{ __('frontend.conta.pacote_servico') }}</th>
                                    <th>{{ __('frontend.conta.data_saida') }}</th>
                                    <th>{{ __('frontend.conta.status') }}</th>
                                    <th>{{ __('frontend.conta.voucher') }}</th>
                                </tr>
                                @forelse ($bookings as $booking)
                                    <tr>
                                        <td>
                                            <a class="localizador localizador--mini" href="{{ route('frontend.my-account.bookings.show', $booking->id) }}">{{ $booking->id }}</a>
                                        </td>
                                        <td class="a-esq">
                                            {{$booking->getProductTypeName() }} - {{ $booking->getName() }}<br>

                                            @if ( $booking->offer->isLongtrip() )
                                                @if($booking->getLongtripBoardingLocation())
                                                    <strong>{{__('frontend.pacotes.local_embarque')}}</strong> {{$booking->getLongtripBoardingLocation()->getExtendedNameLocation()}} |
                                                @endif
                                            @endif
                                            @if ( $booking->offer->isHotel() )
                                            <span class="label label-light-info">
                                            {{ mb_strtoupper($booking->offer->hotelOffer->hotel->name)}} -
                                            </span>
                                            @endif
                                            {{ mb_strtoupper($booking->getProductName()) }}
                                        </td>
                                        <td>{{ $booking->startsAtLabel }}</td>
                                        <td class="uc {{ $booking->getFrontendStatusClass() }}">
                                            {{ __("resources.process-statues.". $booking->status) }}
                                        </td>
                                        <td class="uc {{ $booking->getFrontendVoucherStatusClass() }}">
                                            {{ __("resources.process-statues.". $booking->voucher_status) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">{{ __('frontend.conta.ainda_nao_reserva') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
