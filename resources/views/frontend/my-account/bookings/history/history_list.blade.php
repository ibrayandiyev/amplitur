<div class="box mb mb grid-cell-xs--12 grid-cell-md--12">
    <header class="box__header">
        <h3 class="box__titulo">{{ __('frontend.reservas.historico_transacao') }}</h3>
    </header>
    <div class="box__conteudo">
        <ul class="lista-historico">
            @foreach($booking->bookingLogs->whereIn("level", \App\Enums\Bookings\BookingLogs::LOG_LEVEL_CLIENT)->sortByDesc("created_at") as $bookingLog)
            <li class="lista-historico__item">
                <time datetime="{{$bookingLog->created_at}}" class="lista-historico__data">{{$bookingLog->created_at->format("d/m/Y H:i:s")}}</time>
                {{ $bookingLog->getMessage() }}.
            </li>
            @endforeach
        </ul>
    </div>
</div>
    
   