@include('backend.reports.report_refund.filters')
<table id="bookingsTable" class="table full-color-table full-inverse-table hover-table">
        <thead>
            <tr>
                <th class="text-center">{{__('report.loc')}}</th>
                <th class="text-center">{{__('report.date')}}</th>
                <th class="text-center">{{__('report.historic')}}</th>
                <th class="text-center">{{__('report.authorized')}}</th>
            </tr>
        </thead>
        @foreach($reports as $report)
            <tr>
                <td class="text-uppercase text-center align-middle">
                    <a href="{{ route('backend.bookings.edit', $report->targetBooking->id) }}" class="btn-block" target="_blank">
                        <i class="fa fa-external-link"></i> {{ $report->targetBooking->id }}
                    </a>
                </td>
                <td class="text-uppercase text-center align-middle">{{ $report->created_at->format("d/m/Y H:i:s")}}</td>
                <td class="text-uppercase text-center align-middle">{{ $report->message}}</td>
                <td class="text-uppercase text-center align-middle">{{ $report->user->name}}</td>
            </tr>
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

