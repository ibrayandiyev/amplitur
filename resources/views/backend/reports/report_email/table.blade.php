<BR><BR>
@include('backend.reports.report_email.filters')
<table id="bookingsTable" class="table full-color-table full-inverse-table">
    <thead>
        <tr>
        <th class="text-center">{{__('report.email')}}</th>
        </tr>
    </thead>
    @foreach($bookings as $booking)
        <tr>
            <td class="text-left">{{ $booking->bookingClient->email}};</td>
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

