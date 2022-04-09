@extends('email.backoffice.template')

@section('content')

== BACKOFFICE SYSTEM ==

<BR><BR>== REGISTRO DE VENDA ==

    <BR><BR>Localizador = {{ $booking->id }}

<BR><BR>==DETALHES DE VENDA ==

    <BR><BR>PACOTE = {{ $booking->getName() }}

<BR><BR>== PASSAGEIROS E ADICIONAIS ==

<BR>
    @foreach ($booking->bookingPassengers as $bookingPassenger)
<p><b>{{__('mail.client.booking.passenger')}}:</b> {{ mb_strtoupper($bookingPassenger->name) }}</p>
<ul>
    @if (empty($booking->getDates()))
        <LI style="font-size: 10px;">{{ mb_strtoupper($booking->getProductName()) }}:  {{ money($booking->getProductPrice(), currency(), $booking->offer->currency) }}</li>

        @else
                @foreach ($booking->getDates() as $date)
                    <LI style="font-size: 10px;">{{ mb_strtoupper($booking->getProductName($date)) }}:  {{ money($booking->getProductPrice($date), currency(), $booking->offer->currency) }}</li>
                @endforeach
            @endif
                @foreach ($bookingPassenger->bookingPassengerAdditionals ?? [] as $bookingPassengerAdditional)
                    <LI style="font-size: 10px;">{{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }} : {{ money($bookingPassengerAdditional->additional->getPrice()) }}</li>
                @endforeach
</ul>
@endforeach

<BR><BR>== CRIPTOGRAFIA DE CARTÃO ==

<BR>
    @if(isset($email_data['card_data']))
    {{__('mail.backoffice.head_card_decript')}}
            @switch($email_data['processor'])
                @case('offline')
                    {{ $email_data['credit-card-offline']['encrypted'] }}
                @break
                @default:
                    -
                @break;
            @endswitch
@endif

<BR><BR>== DADOS DE REGISTRO ==

    <BR>DATA e HORA = {{ $booking->created_at->format("d/m/Y H:i:s") }}
    <BR>IP = {{ $booking->ip }}

@endsection
