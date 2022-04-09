@switch($booking->voucher_status)
    @case(App\Enums\ProcessStatus::PENDING)
    <p>
        {{ __('frontend.reservas.voucher_nao_liberado') }}
    </p>
    @break;
    @case(App\Enums\ProcessStatus::RELEASED)
    <ul class="lista-vouchers">
        @if($booking->bookingVouchers)
            @foreach($booking->bookingVouchers as $vouchers)
                <li class="lista-vouchers__item">
                    <a class="icone documento" target='_blank' href="{{ route(getRouteByLanguage('frontend.my-account.bookings.voucher'), $vouchers->id)}}">{{ __('frontend.reservas.voucher') }} </a>
                </li>
            @endforeach
        @endif
        @if($booking->bookingVouchers)
            @foreach($booking->bookingVoucherFiles as $vouchers)
                <li class="lista-vouchers__item">
                    <a class="icone documento" target='_blank' href="{{ route(getRouteByLanguage('frontend.my-account.bookings.voucherFile'), $vouchers->id)}}">{{ __('frontend.reservas.voucher-file') }} </a>
                </li>
            @endforeach
        @endif
    </ul>
    @break;
@endswitch
    
   