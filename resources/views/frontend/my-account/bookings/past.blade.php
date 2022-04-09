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
                        <h2 class="pagina__subtitulo">{{ __('frontend.conta.minhas_viagens_passadas') }}</h2>
                        <table class="tabela tabela--reservas mb">
                            <tbody><tr>
                                <th>{{ __('frontend.conta.numero') }}</th>
                                <th>{{ __('frontend.conta.pacote_servico') }}</th>
                                <th>{{ __('frontend.conta.data_saida') }}</th>
                                <th>{{ __('frontend.conta.status') }}</th>
                            </tr>
                                @forelse ($bookings as $booking)
                                <tr>
                                    <td>
                                        <a class="localizador localizador--mini" href="{{ route('frontend.my-account.bookings.show', $booking->id) }}">{{ $booking->id }}</a>
                                    </td>
                                    <td class="a-esq">
                                        {{$booking->getProductTypeName() }} - {{ $booking->getName() }}<br>
                                        {{ mb_strtoupper($booking->getProductName()) }}
                                    </td>
                                    <td>{{ $booking->startsAtLabel }}</td>
                                    <td class="uc">
                                    @php
										$paymentStatusClass 		= 'status-pendenteconf';
										switch($booking->status){
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
                                    <span class="box-status {{ $paymentStatusClass }}">{{ __("resources.process-statues.". $booking->status) }}</span>
                                    </td>
                                </tr>
                                @empty

                                    @forelse ($bookingLegacies as $booking)
                                    <tr>
                                        <td>
                                            <a class="localizador localizador--mini" href="#">{{ $booking->booking_id }}</a>
                                        </td>
                                        <td class="a-esq">
                                            {{ $booking->name }}<br>
                                        </td>
                                        <td>{{ $booking->startsAtLabel }}</td>
                                        <td class="uc">
                                        @php
                                            $paymentStatusClass 		= 'status-pendenteconf';
                                            switch($booking->status){
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
                                        <span class="box-status {{ $paymentStatusClass }}">{{ __("resources.process-statues.". $booking->status) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">{{ __('frontend.conta.ainda_nao_reserva') }}</td>
                                        </tr>
                                    @endforelse
                                @endforelse
	                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
