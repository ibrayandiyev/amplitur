@php
    $master = user()->isMaster();
@endphp
@include('backend.reports.report_event.filters')
<table id="bookingsTable" class="table full-color-table full-inverse-table hover-table">
    <thead>
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
    @php
        $provider_id = $_filter_params['company_id'] ?? null;
        $company_id  = $_filter_params['company_id'] ?? null;
        if($company_id >0){
            $bookings->where("company_id", $company_id);
        }
    @endphp
        @foreach($bookings as $booking)
            <tr>
                <td class="text-center">
                    <a href="{{ route('backend.bookings.edit', $booking->id) }}" class="btn-block" target="_blank">
                        <i class="fa fa-external-link"></i> {{ $booking->id }}
                    </a>
                </td>
                @if($master)
                <td class="text-center">{{ $booking->offer->provider->name }}</td>
                @endif
                <td class="text-center">{{ $booking->offer->company->company_name }}</td>
                <td class="text-center">{{ $booking->expired_at_label }}</td>
                <td class="text-center">{!! $booking->StatusLabel !!}</td>
                <td class="text-center">{!! $booking->PaymentStatusLabel !!}</td>
                @if($master)
                <td class="text-center">{!! $booking->DocumentStatusLabel !!}</td>
                @endif
                <td class="text-center">{!! $booking->VoucherStatusLabel !!}</td>
            </tr>
        @endforeach

    </tbody>
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

