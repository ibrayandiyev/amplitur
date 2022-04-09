<div class="box mb">
    <header class="box__header">
        <h3 class="box__titulo">{{ __('frontend.reservas.servicos_contratados') }}</h3>
    </header>
    <div class="box__conteudo">
        <h3>{{ __('frontend.pacotes.inclusoes') }}</h3>

        <div class="corpo-texto">
            <ul>

            @if($product)
                @if ($product->getOfferType() == \App\Enums\OfferType::LONGTRIP)
                    @if($bookingProduct)
                        @php

                        $hotels = collect();

                        @endphp
                        @foreach($longtripAccommodationHotels as $longtripAccommodationHotel)
                            @php
                                $hotel = $longtripAccommodationHotel->hotel;
                            @endphp
                            @if($hotel)
                                <li class="li-p">{{ $longtripAccommodationHotel->days() }} {{ __('contract.room_nights') }} {{ mb_strtoupper(city($hotel->address->getCityName())) }}
                                    — {{ $longtripAccommodationHotel->getLabelName() }}
                                    — {{ __('frontend.pacotes.check_in') }}: {{ $longtripAccommodationHotel->checkin ? $longtripAccommodationHotel->checkin->format('d/m/Y') : '-' }} - {{ __('frontend.pacotes.check_out') }}: {{ $longtripAccommodationHotel->checkout ? $longtripAccommodationHotel->checkout->format('d/m/Y') : '-' }}
                            @endif
                        @endforeach

                        @foreach ($product->longtripRoute->inclusions as $inclusion)
                            <li>{{ $inclusion->name }}</li>
                        @endforeach
                        @if (!empty($longtripRoute->extra_inclusions))
                            {!! $longtripRoute->extra_inclusions !!}
                        @endif
                    @endif
                @else
                    @forelse($booking->getProduct()->getInclusions() ?? [] as $inclusion)
                        <li>{{ $inclusion->name }}</li>
                    @empty
                        <li><i>{{ __('frontend.reservas.sem_inclusao') }}</i></li>
                    @endforelse
                @endif
            @endif
            </ul>
        </div>
    </div>
</div>
