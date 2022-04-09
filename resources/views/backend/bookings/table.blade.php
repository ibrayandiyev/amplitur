@include('backend.bookings.filters')

<table id="bookingsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center"  width="10%">#</th>
            <th>{{ __('resources.bookings.model.offer') }}</th>
            <th class="text-center" width="12%">{{ __('resources.bookings.model.status') }}</th>
            <th class="text-center" width="12%">{{ __('resources.bookings.model.created_at') }}</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td class="text-center label-booking align-middle"><a href="{{ route('backend.bookings.edit', $booking) }}">{{ $booking->id }}</a></td>
            <td class="text-uppercase align-middle">
                <span class="label label-inverse">{{ $booking->getProductTypeName() }}</span>
                <span class="label label-light-inverse">{{ $booking->package->extendedNameDate }}</span> <br />
                    @if ( $booking->offer->isLongtrip() )
                        @if($booking->getLongtripBoardingLocation())
                        <span class="label label-light-info">
                        {{__('frontend.pacotes.local_embarque')}} {{$booking->getLongtripBoardingLocation()->getExtendedNameLocation()}}
                        </span>
                        @endif
                    @endif
                    @if ( $booking->offer->isHotel() )
                    <span class="label label-light-info">
                    {{$booking->offer->hotelOffer->hotel->name}}
                    </span>
                    @endif
                <span class="label label-light-info">
                    {{ $booking->getProductName() }}
                </span>
            </td>
            <td class="text-uppercase text-center align-middle">{!! $booking->statusLabel !!}</td>
            <td class="text-uppercase text-center align-middle">{{ $booking->createdAtLabel }}</td>
            <td class="text-center align-middle skip">
                <a href="{{ route('backend.bookings.destroy', $booking) }}"  token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
