@php
    $master = user()->isMaster();
    $totals = null;
@endphp
@include('backend.reports.report_payment_providers.filters')
<table id="bookingsTable" class="table table-bordered table-striped table-hover table-linked-row">
    <thead>
        <tr>
            <th class="text-center">{{__('report.loc')}}</th>
            @if($master)
            <th class="text-center">{{ __('report.provider') }}</th>
            @endif
            <th class="text-center">{{ __('report.company') }}</th>
            <th class="text-center">{{ __('report.sale_date') }}</th>
            @if($master)
            <th class="text-center">{{ __('report.booking') }}</th>
            @endif
            <th class="text-center">{{ __('report.net_receive') }}</th>
            <th class="text-center">{{ __('report.pay_date') }}</th>
            <th class="text-center">{{ __('report.payment') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings->sortByDesc("created_at") as $booking)
        @php
            $passengerAdditionalsTotalNet = $booking->bookingPassengerAdditionals->sum("price_net");
            $productTotalNet = $booking->bookingProducts->sum("price_net");
            $totalNet       = $passengerAdditionalsTotalNet + $productTotalNet;
        @endphp
        <tr data-href="
        @if(!$booking->isCanceled()) {{ route('backend.bookings.edit', $booking) }} @else # @endif">
            <td width="10%" class="text-uppercase text-center align-middle">
                <a href="{{ route('backend.bookings.edit', $booking->id) }}" class="btn-block" target="_blank">
                    <i class="fa fa-external-link"></i> {{ $booking->id }}
                </a>
            </td>
            @if($master)
            <td width="12%" class="text-uppercase text-center align-middle">{{ $booking->offer->provider->company_name }}</td>
            @endif
            <td width="12%" class="text-uppercase text-center align-middle">{{ $booking->offer->company->company_name }}</td>
            <td width="10%" class="text-uppercase text-center align-middle">{{ $booking->createdAtLabel }}</td>
            <td width="10%" class="text-uppercase text-center align-middle">{!! $booking->StatusLabel !!}</td>
            <td width="10%" class="text-uppercase text-center align-middle">
            @if(!$booking->isCanceled())
                {!! money($totalNet, $booking->currency->code) !!}
            @endif
            </td>
            <td width="10%" class="text-lowercase text-center"></td>
            <td width="10%" class="text-uppercase text-center align-middle"></td>
        </tr>
        @php
            if(!isset($totals[$booking->currency->code])){
                $totals[$booking->currency->code] = 0;
            }
            if(!$booking->isCanceled()){
                $totals[$booking->currency->code] += $totalNet;
            }
        @endphp
        @endforeach
    </tbody>
    <tfooter>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            @if($master)
            <td></td>
            @endif
            <td class="text-center"><strong>{{ __('report.total') }}</strong></td>
            <td class="text-center"><strong>
                @if($totals != null)
                    @foreach($totals as $key => $total)
                        @php
                            echo money($total, $key)
                        @endphp
                    @endforeach
                @endif
            </strong>
            </td>
            <td></td>
            <td></td>
        </tr>
    </tfooter>
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

