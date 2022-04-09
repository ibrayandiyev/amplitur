@php
    $master = user()->isMaster();
@endphp
@include('backend.reports.report_detail_booking.filters')
<table id="bookingsTable" class="table color-table inverse-table">
        @php
            $provider_id = $_filter_params['company_id'] ?? null;
            $company_id  = $_filter_params['company_id'] ?? null;
            if($company_id >0){
                $bookings->where("company_id", $company_id);
            }
        @endphp
                        @foreach($bookings as $booking)
                            <thead>
                                <tr>
                                    <td colspan="12">
                                            <span class="label label-inverse">{{ $booking->getProductTypeName() }}</span>
                                            <span class="label label-light-inverse">{{ $booking->package->extendedNameDate }}</span>
                                                @if ( $booking->offer->isLongtrip() )
                                                @if($booking->getLongtripBoardingLocation())
                                                    <span class="label label-light-inverse">
                                                    {{__('frontend.pacotes.local_embarque')}} {{$booking->getLongtripBoardingLocation()->getExtendedNameLocation()}}
                                                    </span>
                                                @endif
                                                @endif
                                                @if ( $booking->offer->isHotel() )
                                                <span class="label label-light-inverse">
                                                {{$booking->offer->hotelOffer->hotel->name}}
                                                </span>
                                                @endif
                                            <span class="label label-light-inverse">
                                                {{ $booking->getProductName() }}
                                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">{{__('report.loc')}}</th>
                                    @if($master)
                                    <th class="text-center">{{__('report.provider')}}</th>
                                    @endif
                                    <th class="text-center">{{__('report.company')}}</th>
                                    <th class="text-center">{{__('report.deadline')}}</th>
                                    <th class="text-center">{{__('report.booking')}}</th>
                                    <th class="text-center">{{__('report.payment')}}</th>
                                    @if($master)
                                    <th class="text-center">{{__('report.document')}}</th>
                                    @endif
                                    <th class="text-center">{{__('report.voucher')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <a href="{{ route('backend.bookings.edit',  $booking->id) }}" class="btn-block" target="_blank">
                                            <i class="fa fa-external-link"></i> {{  $booking->id }}
                                        </a>
                                    </td>
                                    @if($master)
                                    <td class="text-center">{{ $booking->offer->provider->name }}</td>
                                    @endif
                                    <td class="text-center">{{ $booking->offer->company->company_name }}</td>
                                    <td class="text-center"><strong>{{ $booking->expired_at_label }}</strong></td>
                                    <td class="text-center">{!! $booking->StatusLabel !!}</td>
                                    <td class="text-center">{!! $booking->PaymentStatusLabel !!}</td>
                                    @if($master)
                                    <td class="text-center">{!! $booking->DocumentStatusLabel !!}</td>
                                    @endif
                                    <td class="text-center">{!! $booking->VoucherStatusLabel !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="12" style="counter(rowNumber),">
                                    @foreach($booking->bookingPassengers as $passenger)
                                         <label><strong>{{ __("frontend.forms.passenger_name")}}:</strong></label> {{ $passenger->name }}
                                        <ul>
                                            @foreach($passenger->bookingPassengerAdditionals as $bookingPassengerAdditional)
                                                <li><strong>{{ __("report.service") }} </strong>{{$bookingPassengerAdditional->additional->getTitle() }}</li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#bookingsTable').DataTable({
                searching: false,
                ordering: false,
            });
        });
    </script>
@endpush

