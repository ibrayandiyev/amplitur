@extends('backend.template.document')

@section('content')
    <div id="logo-doc">
        <img src="https://www.amp-travels.com/images/amp-travel-front-bgwhite2.png" alt="AMP Travels ">
    </div>

    <div id="corpo-doc">
        <h1>Confirmação Interna</h1>

        <p>
            <strong>Serviço Principal: {{ mb_strtoupper($booking->getName()) }} &ndash; {{ mb_strtoupper($booking->getCity()) }}&ndash; {{ $booking->getStartsAtLabelAttribute() }} &ndash; {{ mb_strtoupper($booking->getProductName()) }}</strong>
        </p>

        <p>
            <strong>Localizador N°</strong>: {!! $booking->id ?? '<i>Em definição</i>' !!} | <strong>Data de Embarque</strong>: {{ $booking->getStartsAtLabelAttribute() }} | <strong>Promocode</strong>: {{ $booking->promocode ? mb_strtoupper($booking->promocode->name) : '' }}
        </p>

        <h2>Responsável pelo contrato e pagamento</h2>

        <p>
            <strong>{{ mb_strtoupper($booking->bookingClient->name) }}</strong> / {{ mb_strtoupper($booking->bookingClient->identity) }} {{ mb_strtoupper($booking->bookingClient->uf) }} /  {{ mb_strtoupper($booking->bookingClient->phone) }}
        </p>

        <h2>Passageiros</h2>

        <ul class="lista-passageiros">
            @foreach ($booking->bookingPassengers as $passenger)
                <li>
                    <strong>{{ $passenger->name }}</strong> / {{ $passenger->identity }} {{ $passenger->uf }} / {{ $passenger->birthdate ? $passenger->birthdate->format('d/m/Y') : '' }} / {{ $passenger->phone }} / {{ $passenger->email }}
                    <ul class="passageiros-adicionais">
                        @foreach ($passenger->bookingPassengerAdditionals as $bookingPassengerAdditional)
                            <li>{{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>

        @if (user()->canSeeBookingConfirmationPayments())
            <h2>Totais e Formas de Pagamento</h2>

            <ul>
                @foreach ($booking->bookingBills as $key => $bookingBill)
                    <li>@if ($bookingBill->installment) Parcela {{ $bookingBill->installment }}: @endif {{ $bookingBill->paymentMethod->name }} – {{ $bookingBill->currency->code }} {{ decimal($bookingBill->total) }} – Vencimento {{ $bookingBill->expiresAtLabel }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
